import { $$ } from '../../src/scripts/util.js';

/* The shapes load (and three with them) the first time a callout comes near
   the viewport. */
const hosts = $$('[data-shapes]');

if (hosts.length) {
  let scene = null;

  const observer = new IntersectionObserver((entries, self) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      self.unobserve(entry.target);
      scene ||= import('./shapes.js');
      scene
        .then(({ mountShapes }) => mountShapes(entry.target))
        .catch(error => console.error('Callout shapes:', error));
    });
  }, { rootMargin: '25% 0px' });

  hosts.forEach(host => observer.observe(host));
}
