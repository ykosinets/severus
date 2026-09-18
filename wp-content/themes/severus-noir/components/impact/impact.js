import { $, $$, bp, clamp, onFrame, still } from '../../src/scripts/util.js';
import '../../src/scripts/vendor/crystal-slider.js';   // defines window.CrystalSlider

const impact = $('[data-impact]');
const canvas = $('[data-crystal]');
const mobile = matchMedia(`(max-width: ${bp.lap}px)`);

/* ── The crystal transition, scrubbed by scroll ───────────────────────────────── */
if (impact && canvas && window.CrystalSlider) {
  let images = [];
  try {
    images = JSON.parse(canvas.dataset.slides || '[]');
  } catch {
    images = [];
  }

  if (images.length) {
    const pin = $('.impact__pin', impact);
    const verts = $$('.vert', impact);
    const ticks = $$('.impact__ticks i', impact);
    const stageLink = $('[data-stage-link]', impact);


    const show = index => {
      verts.forEach((vert, i) => {
        vert.classList.toggle('is-active', i === index);
        vert.querySelector('[data-vert-go]')?.setAttribute('aria-expanded', String(i === index));
      });
      ticks.forEach((tick, i) => tick.classList.toggle('is-active', i === index));

      const source = verts[index]?.querySelector('a[href]');
      if (!stageLink || !source) return;
      stageLink.href = source.getAttribute('href');
      stageLink.setAttribute('aria-label', source.textContent.trim());
    };

    const slider = new window.CrystalSlider(canvas, {
      images,
      effect: 'flux',
      presets: { flux: { cells: 5.5, facet: 0.05, glow: 0.6, duration: 900, churn: 1.1, swell: 0.28 } },
      loop: false,
      parallax: false,
      autoplay: 0,
      scrollDriven: true,
    });

    impact.classList.add('is-scroll-driven');
    let shown = -1;
    // Desktop timeline, kept for the title clicks.
    const timing = { top: 0, travel: 1, duration: 1, cycle: 1, hold: 0 };
    const sync = () => {
      if (mobile.matches) {
        impact.style.removeProperty('height');
        pin.style.removeProperty('top');
        // Each article scrolls naturally over the pinned stage. Scrub toward
        // its image as its heading approaches the viewport's reading line.
        const readingLine = innerHeight * 0.3;
        let position = 0;
        for (let i = 0; i < verts.length - 1; i++) {
          const start = verts[i].getBoundingClientRect().top;
          const end = verts[i + 1].getBoundingClientRect().top;
          if (readingLine < start) break;
          position = i + clamp((readingLine - start) / Math.max(1, end - start), 0, 1);
        }
        position = Math.min(position, images.length - 1);
        const index = Math.round(position);
        slider.seek(still.matches ? index : position);
        if (shown !== index) {
          shown = index;
          show(index);
        }
        return;
      }
      // Tall mobile layouts scroll into view before pinning their lower edge.
      const top = Math.min(0, innerHeight - pin.offsetHeight);
      pin.style.top = `${top}px`;
      const hold = 0.65;
      const transition = 0.9;
      const cycle = hold + transition;
      const duration = images.length * hold + (images.length - 1) * transition;
      const travel = innerHeight * duration;
      impact.style.height = `${pin.offsetHeight + travel - top}px`;
      Object.assign(timing, { top, travel, duration, cycle, hold });
      const progress = clamp((top - impact.getBoundingClientRect().top) / travel, 0, 1);
      // Hold every complete frame before scrubbing into the next one.
      const elapsed = progress * duration;
      const frame = Math.min(Math.floor(elapsed / cycle), images.length - 1);
      const blend = clamp((elapsed - frame * cycle - hold) / transition, 0, 1);
      const position = Math.min(frame + blend, images.length - 1);
      const index = Math.round(position);
      slider.seek(still.matches ? index : position);
      if (shown !== index) {
        shown = index;
        show(index);
      }
    };
    /* A title doesn't toggle anything itself: it scrolls to the point where
       the slider holds its item, and the scroll opens it. */
    const go = index => {
      const smooth = still.matches ? 'auto' : 'smooth';

      if (mobile.matches) {
        // Land the title just under the site header and the pinned lead.
        const head = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--head')) || 0;
        const lead = $('.lead', impact)?.offsetHeight || 0;
        const title = verts[index].querySelector('.vert__title') || verts[index];
        const target = title.getBoundingClientRect().top + scrollY - head - lead - 16;
        scrollTo({ top: target, behavior: smooth });
        return;
      }

      const { top, travel, duration, cycle, hold } = timing;
      const progress = clamp((index * cycle + hold / 2) / duration, 0, 1);
      const sectionTop = impact.getBoundingClientRect().top + scrollY;
      scrollTo({ top: sectionTop - top + progress * travel, behavior: smooth });
    };

    impact.querySelectorAll('[data-vert-go]').forEach(button => {
      button.addEventListener('click', () => go(Number(button.dataset.vertGo)));
    });

    onFrame(sync);
    new ResizeObserver(sync).observe(pin);
    still.addEventListener('change', sync);
    mobile.addEventListener('change', sync);
  }
}
