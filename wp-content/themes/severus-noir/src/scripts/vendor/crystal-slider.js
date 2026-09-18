/**
 * CrystalSlider — WebGL2-слайдер с набором шейдерных переходов.
 * Без зависимостей: один полноэкранный треугольник + фрагментный шейдер.
 *
 * Эффекты: crystal | shatter | prism | caustics | depth
 * Переключение на лету: slider.setEffect('prism')
 */
(function (global) {
  'use strict';

  const VERT = `#version 300 es
  void main() {
    vec2 p = vec2((gl_VertexID << 1) & 2, gl_VertexID & 2);
    gl_Position = vec4(p * 2.0 - 1.0, 0.0, 1.0);
  }`;

  /* ------------------------------------------------------------------ */
  /*  Общая часть всех шейдеров                                          */
  /* ------------------------------------------------------------------ */

  const PRELUDE = `#version 300 es
  precision highp float;

  uniform sampler2D uFrom;
  uniform sampler2D uTo;
  uniform vec2  uRes;
  uniform vec2  uFromSize;
  uniform vec2  uToSize;
  uniform vec2  uPointer;
  uniform vec2  uDir;
  uniform float uProgress;
  uniform float uTime;
  uniform float uCells;
  uniform float uFacet;
  uniform float uGlow;
  uniform float uChurn;   // скорость дрейфа ячеек
  uniform float uSwell;   // насколько сетка укрупняется к пику перехода

  out vec4 frag;

  #define PI 3.14159265

  const float SPREAD = 0.55;
  const float ABER   = 0.26;
  const vec3  GLOWC  = vec3(0.30, 1.00, 0.92);

  float hash1(vec2 p) {
    return fract(sin(dot(p, vec2(12.9898, 78.233))) * 43758.5453123);
  }

  vec2 hash2(vec2 p) {
    p = vec2(dot(p, vec2(127.1, 311.7)), dot(p, vec2(269.5, 183.3)));
    return fract(sin(p) * 43758.5453123);
  }

  float vnoise(vec2 p) {
    vec2 i = floor(p), f = fract(p);
    vec2 u = f * f * (3.0 - 2.0 * f);
    return mix(mix(hash1(i), hash1(i + vec2(1, 0)), u.x),
               mix(hash1(i + vec2(0, 1)), hash1(i + vec2(1, 1)), u.x), u.y);
  }

  float fbm(vec2 p) {
    float v = 0.0, a = 0.5;
    for (int i = 0; i < 4; i++) { v += a * vnoise(p); p *= 2.03; a *= 0.5; }
    return v;
  }

  // вписывание по принципу background-size: cover
  vec2 coverUV(vec2 uv, vec2 tex) {
    float sa = uRes.x / uRes.y;
    float ia = tex.x / tex.y;
    vec2 s = sa > ia ? vec2(1.0, ia / sa) : vec2(sa / ia, 1.0);
    return (uv - 0.5) * s + 0.5;
  }

  // сэмпл с хроматической аберрацией
  vec3 sampleRGB(sampler2D t, vec2 tex, vec2 uv, vec2 ca) {
    return vec3(
      texture(t, coverUV(uv + ca, tex)).r,
      texture(t, coverUV(uv,      tex)).g,
      texture(t, coverUV(uv - ca, tex)).b
    );
  }

  // Voronoi: центр ячейки, id, расстояние до ребра
  void facets(vec2 p, out vec2 center, out vec2 id, out float edge) {
    vec2 n = floor(p), f = p - n;
    float d1 = 8.0, d2 = 8.0;
    center = n; id = n;
    for (int j = -1; j <= 1; j++) {
      for (int i = -1; i <= 1; i++) {
        vec2 g = vec2(float(i), float(j));
        vec2 o = hash2(n + g);
        vec2 r = g + o - f;
        float d = dot(r, r);
        if (d < d1)      { d2 = d1; d1 = d; id = n + g; center = n + g + o; }
        else if (d < d2) { d2 = d; }
      }
    }
    edge = sqrt(d2) - sqrt(d1);
  }

  // то же самое, но точки ячеек дрейфуют по замкнутым траекториям
  void facetsAnim(vec2 p, float t, out vec2 center, out vec2 id, out float edge) {
    vec2 n = floor(p), f = p - n;
    float d1 = 8.0, d2 = 8.0;
    center = n; id = n;
    for (int j = -1; j <= 1; j++) {
      for (int i = -1; i <= 1; i++) {
        vec2 g = vec2(float(i), float(j));
        vec2 h = hash2(n + g);
        vec2 o = 0.5 + 0.45 * sin(t + 6.2831 * h);   // дрейф вместо фиксированной точки
        vec2 r = g + o - f;
        float d = dot(r, r);
        if (d < d1)      { d2 = d1; d1 = d; id = n + g; center = n + g + o; }
        else if (d < d2) { d2 = d; }
      }
    }
    edge = sqrt(d2) - sqrt(d1);
  }

  vec2 aspect() { return vec2(uRes.x / uRes.y, 1.0); }

  // мягкая виньетка + тон
  vec3 finish(vec3 col, vec2 uv) {
    float vig = smoothstep(1.35, 0.30, length((uv - 0.5) * aspect()));
    return col * mix(0.86, 1.0, vig);
  }

  // Severus: the slides are lit objects on black. Brightness becomes alpha
  // (premultiplied — alpha never drops below the colour), so the black
  // falls away and the page shows through; x3 keeps mid-tones solid. The
  // near-black floor (JPEG noise, the vignette) is cut to zero first, or it
  // would leave a faint plate.
  vec4 keyed(vec3 col) {
    float peak = max(col.r, max(col.g, col.b));
    col *= smoothstep(0.035, 0.09, peak);
    float a = clamp(max(col.r, max(col.g, col.b)) * 3.0, 0.0, 1.0);
    return vec4(col, a);
  }
  `;

  /* ------------------------------------------------------------------ */
  /*  Переходы                                                           */
  /* ------------------------------------------------------------------ */

  const TRANSITIONS = {

    /* 1. Кристаллические фасетки: преломление по граням + свечение рёбер */
    crystal: `
    void main() {
      vec2 uv = gl_FragCoord.xy / uRes;
      vec2 asp = aspect();

      vec2 center, id; float edge;
      facets(uv * asp * uCells, center, id, edge);

      vec2  cuv  = center / (asp * uCells);
      float grad = clamp(dot(cuv - 0.5, uDir) + 0.5, 0.0, 1.0);
      grad = mix(grad, hash1(id), 0.35);

      float lp = smoothstep(0.0, 1.0, clamp(uProgress * (1.0 + SPREAD) - grad * SPREAD, 0.0, 1.0));
      float burst = sin(lp * PI);

      vec2 nrm  = normalize(hash2(id) - 0.5 + 1e-5);
      vec2 disp = nrm * uFacet * burst;
      vec2 par  = uPointer * 0.014;

      vec2 uvA = (uv - 0.5) * mix(1.00, 0.94, burst) + 0.5 + par + disp;
      vec2 uvB = (uv - 0.5) * mix(1.12, 1.00, lp)    + 0.5 + par * 0.6 - disp * 0.8;

      vec3 a = sampleRGB(uFrom, uFromSize, uvA,  disp * ABER);
      vec3 b = sampleRGB(uTo,   uToSize,   uvB, -disp * ABER * 0.8);
      vec3 col = sqrt(mix(a * a, b * b, lp));

      float line    = 1.0 - smoothstep(0.0, 0.085, edge);
      float shimmer = 0.75 + 0.25 * sin(uTime * 3.0 + hash1(id) * 30.0);
      col += GLOWC * line * burst * shimmer * uGlow;

      frag = keyed(finish(col, uv));
    }`,

    /* 2. Живой кристалл: сетка дрейфует, вращается и дышит по ходу перехода */
    flux: `
    void main() {
      vec2 uv = gl_FragCoord.xy / uRes;
      vec2 asp = aspect();
      float t = uTime;

      // к середине перехода грани укрупняются, к концу возвращаются
      float swell = sin(clamp(uProgress, 0.0, 1.0) * PI);
      float dens  = uCells * mix(1.0, 1.0 - uSwell, swell);

      // медленный разворот решётки — чтобы она не читалась как статичный узор
      float ang = t * 0.05;
      mat2  R   = mat2(cos(ang), -sin(ang), sin(ang), cos(ang));
      vec2  cp  = (R * ((uv - 0.5) * asp)) * dens;

      vec2 center, id; float edge;
      facetsAnim(cp, t * uChurn, center, id, edge);

      // центр ячейки обратно в экранные uv
      vec2 cuv = (transpose(R) * (center / dens)) / asp + 0.5;

      float grad = clamp(dot(cuv - 0.5, uDir) + 0.5, 0.0, 1.0);
      grad = mix(grad, vnoise(cuv * asp * 3.0 + t * 0.10), 0.35);

      float lp    = smoothstep(0.0, 1.0, clamp(uProgress * (1.0 + SPREAD) - grad * SPREAD, 0.0, 1.0));
      float burst = sin(lp * PI);

      // направление преломления берём из гладкого поля, а не из id ячейки:
      // сетка движется, и на дискретном id грани «щёлкали» бы при перескоке
      vec2 fld = vec2(fbm(cuv * asp * 3.0 + vec2(1.7, 8.3) + t * 0.06),
                      fbm(cuv * asp * 3.0 + vec2(5.2, 2.9) - t * 0.05)) - 0.5;
      vec2 nrm  = normalize(fld + 1e-5);
      vec2 disp = nrm * uFacet * burst;
      vec2 par  = uPointer * 0.014;

      vec2 uvA = (uv - 0.5) * mix(1.00, 0.94, burst) + 0.5 + par + disp;
      vec2 uvB = (uv - 0.5) * mix(1.12, 1.00, lp)    + 0.5 + par * 0.6 - disp * 0.8;

      vec3 a = sampleRGB(uFrom, uFromSize, uvA,  disp * ABER);
      vec3 b = sampleRGB(uTo,   uToSize,   uvB, -disp * ABER * 0.8);
      vec3 col = sqrt(mix(a * a, b * b, lp));

      // рёбра дышат по толщине, вдоль граней бежит световая волна
      float pulse  = 0.026 + 0.026 * vnoise(cuv * asp * 5.0 + t * 0.35);
      float line   = 1.0 - smoothstep(0.0, pulse, edge);
      float travel = 0.55 + 0.45 * sin(t * 4.0 - dot(cuv, uDir) * 18.0);
      col += GLOWC * line * burst * travel * uGlow * 1.7;

      frag = keyed(finish(col, uv));
    }`,

    /* 3. Раскол: осколки разлетаются с поворотом, под ними новый кадр */
    shatter: `
    void main() {
      vec2 uv = gl_FragCoord.xy / uRes;
      vec2 asp = aspect();

      vec2 center, id; float edge;
      facets(uv * asp * uCells * 0.75, center, id, edge);

      vec2  cuv  = center / (asp * uCells * 0.75);
      float grad = clamp(dot(cuv - 0.5, uDir) + 0.5, 0.0, 1.0);
      grad = mix(grad, hash1(id), 0.30);

      float lp = clamp(uProgress * (1.0 + SPREAD) - grad * SPREAD, 0.0, 1.0);
      vec2  pr  = uPointer * 0.012;

      // осколок улетает с ускорением и доворотом
      float ang = (hash1(id + 7.3) - 0.5) * 1.4 * lp * lp;
      mat2  R   = mat2(cos(ang), -sin(ang), sin(ang), cos(ang));
      vec2  off = (normalize(hash2(id) - 0.5 + 1e-5) * 0.6 + uDir * 0.9) * 0.45 * lp * lp;

      // поворот считаем в квадратных координатах, иначе осколок перекашивает
      vec2 uvA = cuv + (R * ((uv - cuv) * asp)) / asp - off + pr;
      vec2 uvB = (uv - 0.5) * mix(1.10, 1.00, smoothstep(0.0, 1.0, lp)) + 0.5 + pr * 0.6;

      float fade = 1.0 - smoothstep(0.30, 0.95, lp);
      vec2  ca   = off * ABER * 0.6;

      vec3 a = sampleRGB(uFrom, uFromSize, uvA, ca);
      vec3 b = sampleRGB(uTo,   uToSize,   uvB, vec2(0.0));
      vec3 col = sqrt(mix(b * b, a * a, fade));

      // светящаяся кромка скола — ярче в начале разлёта
      float rim = 1.0 - smoothstep(0.0, 0.11, edge);
      col += GLOWC * rim * sin(clamp(lp * 1.6, 0.0, 1.0) * PI) * uGlow * 1.15;

      frag = keyed(finish(col, uv));
    }`,

    /* 4. Призма: по кадру идёт луч, на фронте — дисперсия в спектр */
    prism: `
    void main() {
      vec2 uv = gl_FragCoord.xy / uRes;
      vec2 asp = aspect();

      float grad = dot(uv - 0.5, uDir) + 0.5;
      // лёгкая волнистость фронта, чтобы луч не был линейкой
      grad += (fbm(uv * asp * 3.0 + uTime * 0.15) - 0.5) * 0.09;

      const float W = 0.22;                       // ширина луча
      float d    = grad - (uProgress * (1.0 + 2.0 * W) - W);
      float band = exp(-(d / W) * (d / W) * 3.0); // мягкое ядро луча
      float core = pow(band, 6.0);

      vec2 par  = uPointer * 0.014;
      vec2 refr = uDir * band * uFacet * 1.6;     // преломление в теле луча
      vec2 ca   = uDir * band * 0.06;

      vec3 a = sampleRGB(uFrom, uFromSize, uv + refr + par,        ca);
      vec3 b = sampleRGB(uTo,   uToSize,   uv - refr * 0.7 + par, -ca * 0.8);

      float k = smoothstep(0.35, -0.35, d);       // что уже за лучом
      vec3 col = sqrt(mix(a * a, b * b, k));

      // спектральный ореол + белое ядро
      col += vec3(0.55, 0.15, 0.9) * band * band * 0.25 * uGlow;
      col += GLOWC * core * 1.6 * uGlow;

      frag = keyed(finish(col, uv));
    }`,

    /* 5. Каустики: плавное перетекание сквозь жидкое стекло */
    caustics: `
    void main() {
      vec2 uv = gl_FragCoord.xy / uRes;
      vec2 asp = aspect();

      float n    = fbm(uv * asp * 2.6 + uTime * 0.05);
      float grad = clamp(dot(uv - 0.5, uDir) + 0.5, 0.0, 1.0);
      float mask = mix(grad, n, 0.65);

      float lp = smoothstep(0.0, 1.0, clamp(uProgress * (1.0 + SPREAD) - mask * SPREAD, 0.0, 1.0));
      float burst = sin(lp * PI);

      vec2 warp = vec2(fbm(uv * asp * 4.0 + vec2(3.7, 1.2) + uTime * 0.08),
                       fbm(uv * asp * 4.0 + vec2(9.1, 6.3) - uTime * 0.06)) - 0.5;
      vec2 disp = warp * uFacet * 2.4 * burst;
      vec2 par  = uPointer * 0.014;

      vec3 a = sampleRGB(uFrom, uFromSize, uv + disp + par,        disp * ABER);
      vec3 b = sampleRGB(uTo,   uToSize,   uv - disp * 0.8 + par, -disp * ABER);
      vec3 col = sqrt(mix(a * a, b * b, lp));

      // тонкие световые жилы — как блики на стекле
      float caus = pow(1.0 - abs(2.0 * fract(n * 5.0 + uTime * 0.12) - 1.0), 9.0);
      col += GLOWC * caus * burst * uGlow * 1.3;

      frag = keyed(finish(col, uv));
    }`,

    /* 6. Глубина: кадр проносится мимо камеры, новый выплывает из темноты */
    depth: `
    const int TAPS = 6;

    vec3 zoomBlur(sampler2D t, vec2 tex, vec2 uv, float s0, float s1, float ca) {
      vec3 acc = vec3(0.0);
      for (int i = 0; i < TAPS; i++) {
        float f  = float(i) / float(TAPS - 1);
        float sc = mix(s0, s1, f);
        acc += vec3(
          texture(t, coverUV((uv - 0.5) * (sc * (1.0 + ca)) + 0.5, tex)).r,
          texture(t, coverUV((uv - 0.5) *  sc              + 0.5, tex)).g,
          texture(t, coverUV((uv - 0.5) * (sc * (1.0 - ca)) + 0.5, tex)).b
        );
      }
      return acc / float(TAPS);
    }

    void main() {
      vec2 uv = gl_FragCoord.xy / uRes;
      vec2 par = uPointer * 0.014;
      vec2 p   = uv + par;

      float lp    = smoothstep(0.0, 1.0, uProgress);
      float burst = sin(lp * PI);
      float amt   = uFacet * 6.0 * burst;      // длина шлейфа

      vec3 a = zoomBlur(uFrom, uFromSize, p, 1.0 - 0.55 * lp, 1.0 - 0.55 * lp - amt, 0.010 * burst);
      vec3 b = zoomBlur(uTo,   uToSize,   p, 1.0 + 1.10 * (1.0 - lp), 1.0 + 1.10 * (1.0 - lp) + amt, 0.008 * burst);

      float k   = smoothstep(0.30, 0.95, lp);
      vec3  col = sqrt(mix(a * a, b * b, k));

      // вспышка света в момент пролёта
      float flare = pow(burst, 2.5) * smoothstep(0.9, 0.0, length((uv - 0.5) * aspect()));
      col += GLOWC * flare * uGlow * 0.45;
      col *= mix(1.0, 0.52, burst);            // провал в темноту на пике

      frag = keyed(finish(col, uv));
    }`,
    /* 7. Безумие: калейдоскоп + глитч-разрывы + ударная волна + спектральный разлёт */
    madness: `
    vec2 rot2(vec2 p, float a) {
      float c = cos(a), sn = sin(a);
      return mat2(c, -sn, sn, c) * p;
    }

    // N-лучевое зеркало вокруг центра; amt=0 — обычный кадр
    vec2 kaleido(vec2 uv, vec2 asp, float n, float spin, float amt) {
      vec2 p = (uv - 0.5) * asp;
      float r = length(p);
      float a = atan(p.y, p.x) + spin;
      float seg = 2.0 * PI / n;
      float fa = abs(mod(a, seg) - seg * 0.5);
      return mix(uv, vec2(cos(fa), sin(fa)) * r / asp + 0.5, amt);
    }

    // каналы разлетаются по спирали
    vec3 spectral(sampler2D tex, vec2 size, vec2 p, float amt) {
      return vec3(
        texture(tex, coverUV(rot2(p - 0.5,  amt) * (1.0 + amt * 0.6) + 0.5, size)).r,
        texture(tex, coverUV(p, size)).g,
        texture(tex, coverUV(rot2(p - 0.5, -amt) * (1.0 - amt * 0.6) + 0.5, size)).b
      );
    }

    void main() {
      vec2  uv  = gl_FragCoord.xy / uRes;
      vec2  asp = aspect();
      float t   = uTime;

      float lp    = smoothstep(0.0, 1.0, uProgress);
      float burst = sin(lp * PI);
      float amp   = uFacet * 20.0;          // общий множитель безумия

      // 1. кадр рвётся на горизонтальные ленты со скачущим сдвигом
      float row   = floor(uv.y * 15.0);
      float g     = hash1(vec2(row, floor(t * 14.0)));
      vec2  guv   = uv + vec2(step(0.55, g) * (g - 0.5) * 0.40 * burst * amp, 0.0);

      // 2. ударная волна от центра
      vec2  d0   = (guv - 0.5) * asp;
      float r0   = length(d0);
      float wave = sin(r0 * 38.0 - lp * 26.0) * 0.022 * burst * amp;
      vec2  wuv  = guv + normalize(d0 + 1e-5) / asp * wave;

      // 3. калейдоскоп: число лучей скачет, вся конструкция раскручивается
      float n    = 3.0 + floor(hash1(vec2(floor(t * 1.5), 3.0)) * 6.0);
      vec2  kuv  = kaleido(wuv, asp, n, t * 0.9 + lp * 6.0, burst);

      // 4. зум-панч в момент пика
      kuv = (kuv - 0.5) * mix(1.0, 0.55, burst * 0.8) + 0.5 + uPointer * 0.014;

      float disp = 0.20 * burst * amp;
      vec3 a = spectral(uFrom, uFromSize, kuv,  disp);
      vec3 b = spectral(uTo,   uToSize,   kuv, -disp);

      // подмена кадра прячется в самой гуще хаоса
      vec3 col = sqrt(mix(a * a, b * b, smoothstep(0.35, 0.65, lp)));

      // 5. энергетические кольца
      float ring = pow(max(0.0, sin(r0 * 22.0 - lp * 20.0)), 12.0);
      col += GLOWC * ring * burst * uGlow * 1.4;

      // 6. искры и мигание экспозиции
      float spark = step(0.9965, hash1(floor(uv * vec2(220.0, 380.0)) + floor(t * 30.0)));
      col += vec3(0.65, 1.0, 0.95) * spark * burst;
      col *= 1.0 + 0.30 * sin(t * 40.0) * burst;

      // 7. редкие инвертированные кадры на пике
      float flip = step(0.93, hash1(vec2(floor(t * 20.0), 7.0))) * burst;
      col = mix(col, vec3(0.85, 1.0, 0.98) - col, flip * 0.55);

      frag = keyed(finish(col, uv));
    }`,
  };

  const EFFECTS = Object.keys(TRANSITIONS);

  /* ------------------------------------------------------------------ */

  const clamp = (v, a, b) => Math.min(b, Math.max(a, v));
  const easeInOut = (t) => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);

  const UNIFORMS = ['uFrom', 'uTo', 'uRes', 'uFromSize', 'uToSize',
    'uPointer', 'uDir', 'uProgress', 'uTime', 'uCells', 'uFacet', 'uGlow',
    'uChurn', 'uSwell'];

  class CrystalSlider {
    static get effects() { return EFFECTS.slice(); }

    constructor(canvas, options = {}) {
      this.canvas = canvas;
      this.opts = Object.assign({
        images: [],
        effect: 'crystal',
        duration: 1100,
        autoplay: 0,
        cells: 5.0,
        facet: 0.065,
        glow: 0.55,
        churn: 1.4,
        swell: 0.28,
        parallax: true,
        loop: true,
        scrollDriven: false,
        onChange: null,
      }, options);

      // индивидуальные настройки под характер каждого перехода
      this.presets = Object.assign({
        crystal:  { cells: 5.0, facet: 0.055, glow: 0.60, duration: 1150 },
        flux:     { cells: 5.5, facet: 0.050, glow: 0.60, duration: 1400, churn: 1.1, swell: 0.28 },
        shatter:  { cells: 5.0, facet: 0.060, glow: 0.55, duration: 1250 },
        prism:    { cells: 5.0, facet: 0.055, glow: 0.75, duration: 1300 },
        caustics: { cells: 5.0, facet: 0.045, glow: 0.50, duration: 1500 },
        depth:    { cells: 5.0, facet: 0.026, glow: 0.65, duration: 1250 },
        madness:  { cells: 5.0, facet: 0.050, glow: 0.70, duration: 1600 },
      }, options.presets || {});

      this.index = 0;
      this.next = null;
      this.p = 0;
      this.dir = [1, 0];
      this.state = 'idle';
      this.pointer = [0, 0];
      this.pointerTarget = [0, 0];
      this.dirty = true;
      this.visible = true;
      this.textures = [];
      this.sizes = [];
      this.programs = new Map();

      this.gl = canvas.getContext('webgl2', {
        antialias: false, alpha: true, premultipliedAlpha: true, depth: false, stencil: false,
        powerPreference: 'high-performance',
      });

      if (!this.gl) { this.#domFallback(); return; }

      this.vao = this.gl.createVertexArray();
      this.gl.bindVertexArray(this.vao);
      this.setEffect(this.opts.effect);
      this.#bindEvents();

      this.#load().then(() => {
        this.canvas.classList.add('is-ready');
        this.#emit();
        this.#resize();
        this.#loop();
        if (this.opts.autoplay) this.#scheduleAuto();
      });
    }

    /* ---------------- публичный API ---------------- */

    setEffect(name) {
      if (!TRANSITIONS[name]) throw new Error('Unknown effect: ' + name);
      this.effect = name;
      this.active = this.#program(name);
      this.gl.useProgram(this.active.prog);
      const preset = this.presets[name] || {};
      this.params = {
        cells: preset.cells ?? this.opts.cells,
        facet: preset.facet ?? this.opts.facet,
        glow: preset.glow ?? this.opts.glow,
        churn: preset.churn ?? this.opts.churn,
        swell: preset.swell ?? this.opts.swell,
        duration: preset.duration ?? this.opts.duration,
      };
      this.dirty = true;
      return this;
    }

    /** Seek adjacent frames directly; a fixed direction makes reverse scrolling reversible. */
    seek(position) {
      const last = this.opts.images.length - 1;
      if (last < 0) return this;
      const value = clamp(position, 0, last);
      this.index = Math.floor(value);
      this.next = Math.min(this.index + 1, last);
      this.p = value - this.index;
      this.dir = [1, 0.15];
      this.scrollTime = value * 0.9;
      this.dirty = true;
      if (!this.gl && this.el) {
        [...this.el.children].forEach((img, i) => {
          img.style.transition = 'none';
          img.style.opacity = i === this.index ? 1 : i === this.next ? this.p : 0;
        });
      }
      return this;
    }

    goTo(i, dirVec) {
      if (this.opts.scrollDriven) return;
      if (this.state === 'anim' || !this.textures.length) return;
      const n = this.textures.length;
      const target = this.opts.loop ? ((i % n) + n) % n : clamp(i, 0, n - 1);
      if (target === this.index) return;
      this.next = target;
      this.dir = dirVec || (target > this.index ? [1, 0.15] : [-1, -0.15]);
      this.#animate(1);
    }

    /** Принудительная отрисовка кадра (нужна редко: печать, ручной цикл, отладка). */
    render() { if (this.gl) this.#draw(); return this; }

    nextSlide() { this.goTo(this.index + 1, [1, 0.15]); }
    prevSlide() { this.goTo(this.index - 1, [-1, -0.15]); }

    pause() { this.autoOn = false; clearTimeout(this.autoTimer); }
    resume() { if (this.opts.autoplay) { this.autoOn = true; this.#scheduleAuto(); } }

    destroy() {
      cancelAnimationFrame(this.raf);
      cancelAnimationFrame(this.animRaf);
      clearTimeout(this.autoTimer);
      this.ro && this.ro.disconnect();
      this.io && this.io.disconnect();
      this.aborts && this.aborts.forEach((fn) => fn());
      if (this.gl) {
        this.textures.forEach((t) => this.gl.deleteTexture(t));
        this.programs.forEach((p) => this.gl.deleteProgram(p.prog));
      }
    }

    /* ---------------- WebGL ---------------- */

    #compile(type, src) {
      const gl = this.gl;
      const s = gl.createShader(type);
      gl.shaderSource(s, src);
      gl.compileShader(s);
      if (!gl.getShaderParameter(s, gl.COMPILE_STATUS)) {
        throw new Error(this.effect + ': ' + gl.getShaderInfoLog(s));
      }
      return s;
    }

    // программы компилируются лениво и кешируются
    #program(name) {
      if (this.programs.has(name)) return this.programs.get(name);
      const gl = this.gl;
      const prog = gl.createProgram();
      gl.attachShader(prog, this.#compile(gl.VERTEX_SHADER, VERT));
      gl.attachShader(prog, this.#compile(gl.FRAGMENT_SHADER, PRELUDE + TRANSITIONS[name]));
      gl.linkProgram(prog);
      if (!gl.getProgramParameter(prog, gl.LINK_STATUS)) {
        throw new Error(name + ': ' + gl.getProgramInfoLog(prog));
      }
      const u = {};
      for (const n of UNIFORMS) u[n] = gl.getUniformLocation(prog, n);
      gl.useProgram(prog);
      gl.uniform1i(u.uFrom, 0);
      gl.uniform1i(u.uTo, 1);
      const entry = { prog, u };
      this.programs.set(name, entry);
      return entry;
    }

    #texFromSource(src) {
      const gl = this.gl;
      const t = gl.createTexture();
      gl.bindTexture(gl.TEXTURE_2D, t);
      gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
      gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, src);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR_MIPMAP_LINEAR);
      gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
      gl.generateMipmap(gl.TEXTURE_2D);
      const aniso = gl.getExtension('EXT_texture_filter_anisotropic');
      if (aniso) {
        const max = gl.getParameter(aniso.MAX_TEXTURE_MAX_ANISOTROPY_EXT);
        gl.texParameterf(gl.TEXTURE_2D, aniso.TEXTURE_MAX_ANISOTROPY_EXT, Math.min(8, max));
      }
      return t;
    }

    async #load() {
      const imgs = await Promise.all(this.opts.images.map((s) => this.#loadImage(s)));
      imgs.forEach((img) => {
        this.textures.push(this.#texFromSource(img));
        this.sizes.push([img.naturalWidth || img.width, img.naturalHeight || img.height]);
      });
    }

    #loadImage(src) {
      return new Promise((resolve) => {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.decoding = 'async';
        img.onload = () => resolve(img);
        img.onerror = () => resolve(makePlaceholder(src));
        img.src = src;
      });
    }

    /* ---------------- цикл отрисовки ---------------- */

    #resize() {
      const dpr = Math.min(window.devicePixelRatio || 1, 2);
      const w = Math.round(this.canvas.clientWidth * dpr);
      const h = Math.round(this.canvas.clientHeight * dpr);
      if (w === this.canvas.width && h === this.canvas.height) return;
      this.canvas.width = w;
      this.canvas.height = h;
      this.gl.viewport(0, 0, w, h);
      this.dirty = true;
    }

    #loop = () => {
      this.raf = requestAnimationFrame(this.#loop);
      if (!this.visible) return;
      this.#resize();

      if (this.opts.parallax) {
        const [tx, ty] = this.pointerTarget;
        const px = this.pointer[0] + (tx - this.pointer[0]) * 0.06;
        const py = this.pointer[1] + (ty - this.pointer[1]) * 0.06;
        if (Math.abs(px - this.pointer[0]) > 1e-4 || Math.abs(py - this.pointer[1]) > 1e-4) this.dirty = true;
        this.pointer = [px, py];
      }

      if (this.dirty || this.state !== 'idle') this.#draw();
    };

    #draw() {
      const gl = this.gl;
      if (!this.textures.length) return;
      const { prog, u } = this.active;
      const a = this.index;
      const b = this.next != null ? this.next : this.index;

      gl.useProgram(prog);
      gl.activeTexture(gl.TEXTURE0);
      gl.bindTexture(gl.TEXTURE_2D, this.textures[a]);
      gl.activeTexture(gl.TEXTURE1);
      gl.bindTexture(gl.TEXTURE_2D, this.textures[b]);

      gl.uniform2f(u.uRes, this.canvas.width, this.canvas.height);
      gl.uniform2fv(u.uFromSize, this.sizes[a]);
      gl.uniform2fv(u.uToSize, this.sizes[b]);
      gl.uniform2fv(u.uPointer, this.pointer);
      gl.uniform2fv(u.uDir, this.dir);
      gl.uniform1f(u.uProgress, this.p);
      gl.uniform1f(u.uTime, this.opts.scrollDriven ? (this.scrollTime || 0) : performance.now() * 0.001);
      gl.uniform1f(u.uCells, this.params.cells);
      gl.uniform1f(u.uFacet, this.params.facet);
      gl.uniform1f(u.uGlow, this.params.glow);
      gl.uniform1f(u.uChurn, this.params.churn);
      gl.uniform1f(u.uSwell, this.params.swell);

      gl.drawArrays(gl.TRIANGLES, 0, 3);
      this.dirty = false;
    }

    /* ---------------- переходы ---------------- */

    #animate(to) {
      this.state = 'anim';
      clearTimeout(this.autoTimer);
      const from = this.p;
      const dur = Math.max(220, this.params.duration * Math.abs(to - from));
      const t0 = performance.now();

      const step = () => {
        const k = clamp((performance.now() - t0) / dur, 0, 1);
        this.p = from + (to - from) * easeInOut(k);
        this.dirty = true;
        if (k < 1) { this.animRaf = requestAnimationFrame(step); return; }
        if (to === 1) { this.index = this.next; this.#emit(); }
        this.next = null;
        this.p = 0;
        this.state = 'idle';
        this.dirty = true;
        if (this.autoOn) this.#scheduleAuto();
      };
      step();
    }

    #scheduleAuto() {
      clearTimeout(this.autoTimer);
      if (!this.opts.autoplay) return;
      this.autoOn = true;
      this.autoTimer = setTimeout(() => this.nextSlide(), this.opts.autoplay);
    }

    #emit() {
      this.opts.onChange && this.opts.onChange(this.index, this.textures.length);
    }

    /* ---------------- ввод ---------------- */

    #bindEvents() {
      this.aborts = [];
      const on = (el, ev, fn, opt) => {
        el.addEventListener(ev, fn, opt);
        this.aborts.push(() => el.removeEventListener(ev, fn, opt));
      };

      if (!this.opts.scrollDriven) {
      let dragging = false, startX = 0, w = 1, pid = null;

      on(this.canvas, 'pointerdown', (e) => {
        if (this.state === 'anim') return;
        dragging = true; pid = e.pointerId;
        startX = e.clientX;
        w = this.canvas.clientWidth;
        this.state = 'drag';
        this.pause();
        this.canvas.setPointerCapture(pid);
      });

      on(this.canvas, 'pointermove', (e) => {
        if (this.opts.parallax) {
          const r = this.canvas.getBoundingClientRect();
          this.pointerTarget = [
            ((e.clientX - r.left) / r.width) * 2 - 1,
            ((e.clientY - r.top) / r.height) * -2 + 1,
          ];
        }
        if (!dragging) return;
        const dx = e.clientX - startX;
        const n = this.textures.length;
        const want = dx < 0 ? this.index + 1 : this.index - 1;
        const target = this.opts.loop ? ((want % n) + n) % n : clamp(want, 0, n - 1);
        if (target === this.index) { this.p = 0; return; }
        this.next = target;
        this.dir = dx < 0 ? [1, 0.15] : [-1, -0.15];
        this.p = clamp(Math.abs(dx) / (w * 0.65), 0, 1);
        this.dirty = true;
      });

      const end = () => {
        if (!dragging) return;
        dragging = false;
        if (pid != null && this.canvas.hasPointerCapture(pid)) this.canvas.releasePointerCapture(pid);
        if (this.next == null) { this.state = 'idle'; this.resume(); return; }
        this.#animate(this.p > 0.32 ? 1 : 0);
        this.autoOn = !!this.opts.autoplay;
      };
      on(this.canvas, 'pointerup', end);
      on(this.canvas, 'pointercancel', end);
      on(this.canvas, 'pointerleave', () => { this.pointerTarget = [0, 0]; });

      on(window, 'keydown', (e) => {
        if (e.key === 'ArrowRight') this.nextSlide();
        if (e.key === 'ArrowLeft') this.prevSlide();
      });

      let wheelLock = 0;
      on(this.canvas, 'wheel', (e) => {
        const d = Math.abs(e.deltaX) > Math.abs(e.deltaY) ? e.deltaX : e.deltaY;
        if (Math.abs(d) < 12 || performance.now() < wheelLock) return;
        wheelLock = performance.now() + this.params.duration + 120;
        d > 0 ? this.nextSlide() : this.prevSlide();
      }, { passive: true });

      }

      this.io = new IntersectionObserver(([entry]) => {
        this.visible = entry.isIntersecting;
        this.visible ? this.resume() : this.pause();
      }, { threshold: 0.01 });
      this.io.observe(this.canvas);

      on(document, 'visibilitychange', () => {
        document.hidden ? this.pause() : this.resume();
      });

      this.ro = new ResizeObserver(() => { this.dirty = true; });
      this.ro.observe(this.canvas);
    }

    /* ---------------- запасной вариант без WebGL2 ---------------- */

    #domFallback() {
      const box = document.createElement('div');
      box.className = 'cs-fallback';
      this.opts.images.forEach((src, i) => {
        const img = new Image();
        img.src = src;
        img.alt = '';
        if (i === 0) img.className = 'is-active';
        box.appendChild(img);
      });
      this.canvas.replaceWith(box);
      this.el = box;
      this.setEffect = () => this;
      this.goTo = (i) => {
        if (this.opts.scrollDriven) return;
        const n = box.children.length;
        this.index = ((i % n) + n) % n;
        [...box.children].forEach((c, k) => c.classList.toggle('is-active', k === this.index));
        this.#emit();
      };
      this.nextSlide = () => this.goTo(this.index + 1);
      this.prevSlide = () => this.goTo(this.index - 1);
      this.pause = this.resume = () => {};
      this.#emit();
      if (this.opts.autoplay) setInterval(() => this.nextSlide(), this.opts.autoplay);
    }
  }

  /** Процедурная заглушка в стиле макета — если файлов картинок нет. */
  function makePlaceholder(src) {
    const c = document.createElement('canvas');
    c.width = 900; c.height = 1150;
    const x = c.getContext('2d');
    const seed = [...String(src)].reduce((a, ch) => a + ch.charCodeAt(0), 0);
    const rnd = (n) => (Math.sin(seed * 12.9898 + n * 78.233) * 43758.5453) % 1;

    x.fillStyle = '#03080a';
    x.fillRect(0, 0, c.width, c.height);
    x.strokeStyle = 'rgba(80,255,230,0.75)';
    x.shadowColor = 'rgba(60,255,220,0.9)';
    x.shadowBlur = 18;
    x.lineWidth = 1.4;

    const cx = c.width / 2, base = c.height * 0.72;
    for (let i = 0; i < 14; i++) {
      const w = 90 + Math.abs(rnd(i)) * 240;
      const h = 40 + Math.abs(rnd(i + 40)) * 70;
      const y = base - i * 46;
      x.beginPath(); x.rect(cx - w, y - h, w * 2, h); x.stroke();
      x.beginPath();
      x.moveTo(cx - w, y); x.lineTo(cx + w, y - h);
      x.moveTo(cx + w, y); x.lineTo(cx - w, y - h);
      x.stroke();
    }
    const top = document.createElement('canvas');
    top.width = c.width; top.height = base;
    top.getContext('2d').drawImage(c, 0, 0);
    x.save();
    x.shadowBlur = 0;
    x.globalAlpha = 0.2;
    x.filter = 'blur(4px)';
    x.translate(0, base * 2);
    x.scale(1, -1);
    x.drawImage(top, 0, 0);
    x.restore();
    return c;
  }

  global.CrystalSlider = CrystalSlider;
})(window);
