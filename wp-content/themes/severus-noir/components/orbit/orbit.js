import { $$ } from '../../src/scripts/util.js';

/* Every orbit on the page. The scene (and three with it) loads the first time
   one comes near the viewport; a hidden host — display: none on a breakpoint —
   never intersects and never mounts. */
const hosts = $$('[data-orbit]');

if (hosts.length) {
  let scene = null;

  const mount = host => {
    scene ||= import('./scene.js');

    const page = host.dataset.orbit === 'page';

    scene
      .then(({ mountOrbit }) => mountOrbit({
        container: host,
        trigger: page ? document.documentElement : host.closest('section') || host,
        modelUrl: host.dataset.model,
        scrollStart: page ? 'top top' : 'top bottom',
        scrollEnd: 'bottom bottom',
      }))
      .catch(error => {
        host.classList.add('is-fallback');
        console.error('Orbit:', error);
      });
  };

  const observer = new IntersectionObserver((entries, self) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      self.unobserve(entry.target);
      mount(entry.target);
    });
  }, { rootMargin: '25% 0px' });

  hosts.forEach(host => observer.observe(host));
}
