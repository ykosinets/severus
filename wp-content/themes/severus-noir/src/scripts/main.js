/* Global behaviour. Component scripts are appended after this by the build. */
import './snake-arrow.js';
import { $$, still } from './util.js';

/* Where scroll-driven CSS animations aren't supported, reveal on intersection:
   as soon as the top edge is in, however tall the element (a share of its
   height would hold a long block of text back). */
if (!CSS.supports('animation-timeline: view()') && !still.matches) {
  const targets = $$('.reveal');

  targets.forEach(element => {
    element.style.opacity = '0';
    element.style.transform = 'translateY(2.2rem)';
    element.style.transition = 'opacity .8s cubic-bezier(.16,1,.3,1), transform .8s cubic-bezier(.16,1,.3,1)';
  });

  const observer = new IntersectionObserver((entries, self) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      entry.target.style.opacity = '';
      entry.target.style.transform = '';
      self.unobserve(entry.target);
    });
  }, { rootMargin: '0px 0px -8% 0px', threshold: 0 });

  targets.forEach(element => observer.observe(element));
}
