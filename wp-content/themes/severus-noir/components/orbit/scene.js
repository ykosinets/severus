/* Transparent, on-demand 3D viewer for the orbit mark.
   Ported from the previous theme; three and gsap now come from node_modules,
   and esbuild keeps this file (and them) in a separate chunk. */
import * as THREE from 'three';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/** Mounts the orbit. Returns a cleanup function. */
export async function mountOrbit({ container, trigger, modelUrl, scrollStart = 'top bottom', scrollEnd = 'bottom bottom' }) {
  const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true, powerPreference: 'low-power' });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setClearColor(0x000000, 0);
  renderer.toneMapping = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.15;
  renderer.domElement.setAttribute('aria-hidden', 'true');
  container.append(renderer.domElement);

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(35, 1, 0.1, 20);
  camera.position.set(0, 0, 3.65);

  // One close light with finite reach; no ambient fill or environment
  // reflections, so the far side fades into darkness as it turns.
  const key = new THREE.SpotLight(0xf0fff8, 60, 4.2, Math.PI / 4, 0.85, 2);
  key.position.set(1.8, 2.2, 1.8);
  scene.add(key);
  scene.add(key.target);

  let gltf;
  try {
    gltf = await new GLTFLoader().loadAsync(modelUrl);
  } catch (error) {
    renderer.dispose();
    renderer.domElement.remove();
    throw error;
  }

  const orbit = gltf.scene;
  const pivot = new THREE.Group();
  pivot.add(orbit);
  scene.add(pivot);
  pivot.rotation.set(0.16, 0.28, -0.12);

  orbit.traverse(object => {
    if (!object.isMesh) return;
    object.castShadow = false;
    object.receiveShadow = false;
  });

  const render = () => renderer.render(scene, camera);

  const resize = () => {
    const width = container.clientWidth;
    const height = container.clientHeight;
    if (!width || !height) return;
    camera.aspect = width / height;
    camera.position.z = 3.65 / Math.min(camera.aspect, 1);
    camera.updateProjectionMatrix();
    renderer.setSize(width, height);
    render();
  };

  const observer = new ResizeObserver(resize);
  observer.observe(container);
  resize();

  const media = gsap.matchMedia();

  media.add('(prefers-reduced-motion: no-preference)', () => {
    gsap.to(pivot.rotation, {
      y: 0.28 + Math.PI * 2,
      x: 0.56,
      z: 0.16,
      ease: 'none',
      onUpdate: render,
      scrollTrigger: { trigger, start: scrollStart, end: scrollEnd, scrub: 0.65, invalidateOnRefresh: true },
    });
  });

  media.add('(prefers-reduced-motion: reduce)', () => {
    pivot.rotation.set(0.16, 0.28, -0.12);
    render();
  });

  ScrollTrigger.refresh();
  container.dataset.ready = 'true';

  return () => {
    observer.disconnect();
    media.revert();
    orbit.traverse(object => {
      object.geometry?.dispose();
      if (!object.material) return;
      for (const material of Array.isArray(object.material) ? object.material : [object.material]) material.dispose();
    });
    renderer.dispose();
    renderer.domElement.remove();
    delete container.dataset.ready;
  };
}
