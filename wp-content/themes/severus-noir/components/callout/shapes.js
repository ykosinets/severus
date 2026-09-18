/* Three faceted icosahedra for the callout panel: flat-shaded deep teal, lit
   hard from the upper left so each face reads on its own. One renderer for
   all three. They turn slowly and drift with the scroll — the bigger the
   solid, the further it drifts, so the big ones read as nearer. The loop runs only
   while the panel is on screen; with reduced motion a still frame is drawn. */
import { onFrame } from '../../src/scripts/util.js';
import {
  AmbientLight,
  Color,
  DirectionalLight,
  Group,
  HemisphereLight,
  IcosahedronGeometry,
  Mesh,
  MeshStandardMaterial,
  PerspectiveCamera,
  Scene,
  WebGLRenderer,
} from 'three';

const still = matchMedia('(prefers-reduced-motion: reduce)');

/* Where each solid sits, as a fraction of the visible half-width/height, and
   its size as a fraction of the smaller half. Narrow panels use the second
   layout so the shapes keep to the corners. */
const LAYOUT = {
  wide: [
    { x: -0.8, y: 0.36, r: 0.15 },
    { x: 0.78, y: -0.32, r: 0.28 },
    { x: 0.74, y: 0.6, r: 0.07 },
  ],
  narrow: [
    { x: -0.82, y: 0.86, r: 0.1 },
    { x: 0.8, y: -0.84, r: 0.17 },
    { x: -0.76, y: -0.76, r: 0.05 },
  ],
};

/* Scroll drift: a solid of size r moves DRIFT × r half-heights as the panel
   crosses the viewport, so the bigger ones travel further, as if nearer. */
const DRIFT = 1.8;

/* All three are the same icosahedron, set at different angles and turning at
   different rates so they don't read as copies. */
const SOLIDS = [
  { spin: [0.13, 0.19, 0.05], phase: 0 },
  { spin: [-0.11, 0.15, 0.08], phase: 2.1 },
  { spin: [0.21, -0.17, 0.12], phase: 4.2 },
];

export function mountShapes(container) {
  const renderer = new WebGLRenderer({ alpha: true, antialias: true, powerPreference: 'low-power' });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setClearColor(0x000000, 0);
  container.append(renderer.domElement);

  const scene = new Scene();
  const camera = new PerspectiveCamera(35, 1, 0.1, 40);
  camera.position.set(0, 0, 9);

  scene.add(new AmbientLight(0x08201b, 1));
  scene.add(new HemisphereLight(0x6fc4ae, 0x010a08, 0.6));

  const key = new DirectionalLight(0xd9f7ee, 2);
  key.position.set(-3, 4, 5);
  scene.add(key);

  const rim = new DirectionalLight(0x3ec9a5, 0.9);
  rim.position.set(4, -2, -3);
  scene.add(rim);

  const material = new MeshStandardMaterial({
    color: new Color(0x2b6f61),
    emissive: new Color(0x031512),
    roughness: 0.42,
    metalness: 0.18,
    flatShading: true,
  });

  const geometry = new IcosahedronGeometry(1, 0);

  const solids = SOLIDS.map(solid => {
    const mesh = new Mesh(geometry, material);
    const group = new Group();
    group.add(mesh);
    scene.add(group);
    mesh.rotation.set(solid.phase, solid.phase * 0.7, 0);
    return { ...solid, mesh, group };
  });

  let halfWidth = 1;
  let halfHeight = 1;

  const place = () => {
    const width = container.clientWidth;
    const height = container.clientHeight;
    if (!width || !height) return;

    camera.aspect = width / height;
    camera.updateProjectionMatrix();
    renderer.setSize(width, height, false);

    halfHeight = Math.tan((camera.fov * Math.PI) / 360) * camera.position.z;
    halfWidth = halfHeight * camera.aspect;

    const layout = camera.aspect < 1.3 ? LAYOUT.narrow : LAYOUT.wide;
    const unit = Math.min(halfWidth, halfHeight);

    solids.forEach((solid, index) => {
      const spot = layout[index];
      solid.home = { x: spot.x * halfWidth, y: spot.y * halfHeight };
      solid.drift = DRIFT * spot.r * halfHeight;
      solid.group.position.set(solid.home.x, solid.home.y, 0);
      solid.group.scale.setScalar(spot.r * unit);
    });
  };

  /* Where the panel is: -1 as it enters at the bottom, 1 as it leaves at
     the top. Read on scroll, used by the next frame. */
  let progress = 0;

  onFrame(() => {
    const box = container.getBoundingClientRect();
    const travel = (innerHeight + box.height) / 2;
    progress = Math.max(-1, Math.min(1, (innerHeight / 2 - (box.top + box.height / 2)) / travel));
  });

  const draw = time => {
    const t = time / 1000;
    const shift = still.matches ? 0 : progress;
    solids.forEach(solid => {
      const [sx, sy, sz] = solid.spin;
      solid.mesh.rotation.x = solid.phase + t * sx;
      solid.mesh.rotation.y = solid.phase * 0.7 + t * sy;
      solid.mesh.rotation.z = t * sz;
      solid.group.position.y = solid.home.y + shift * solid.drift + Math.sin(t * 0.6 + solid.phase) * 0.05;
    });
    renderer.render(scene, camera);
  };

  let frame = 0;
  let visible = false;

  const loop = time => {
    draw(time);
    frame = requestAnimationFrame(loop);
  };

  const sync = () => {
    cancelAnimationFrame(frame);
    frame = 0;
    if (still.matches) {
      draw(0);
      return;
    }
    if (visible) frame = requestAnimationFrame(loop);
  };

  const resize = new ResizeObserver(() => {
    place();
    draw(still.matches ? 0 : performance.now());
  });
  resize.observe(container);
  place();
  draw(0);

  const watch = new IntersectionObserver(([entry]) => {
    visible = entry.isIntersecting;
    sync();
  });
  watch.observe(container);
  still.addEventListener('change', sync);

  container.dataset.ready = 'true';
}
