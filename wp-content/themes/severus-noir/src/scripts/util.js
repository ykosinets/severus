/* Small shared helpers. Everything component scripts have in common lives here
   so no component reimplements query/scroll plumbing. */

export const $ = (selector, root = document) => root.querySelector(selector);
export const $$ = (selector, root = document) => [...root.querySelectorAll(selector)];
export const clamp = (n, min, max) => Math.min(Math.max(n, min), max);

export const still = matchMedia('(prefers-reduced-motion: reduce)');

/* Breakpoints, kept in step with tokens.pcss. */
export const bp = {
  lap: 1024,
  pad: 900,
  phone: 640,
};

/* One rAF per scroll frame for every measuring component on the page. */
const jobs = new Set();
let queued = false;

const flush = () => {
  queued = false;
  jobs.forEach(job => job());
};

addEventListener('scroll', () => {
  if (queued) return;
  queued = true;
  requestAnimationFrame(flush);
}, { passive: true });

addEventListener('resize', flush);

/** Register a scroll/resize measurement. Runs once immediately. */
export const onFrame = job => {
  jobs.add(job);
  job();
  return () => jobs.delete(job);
};

/** Run a callback the first time an element comes into view. */
export const whenSeen = (element, done, options = {}) => {
  const observer = new IntersectionObserver((entries, self) => {
    if (!entries[0].isIntersecting) return;
    self.disconnect();
    done(element);
  }, { threshold: 0.2, ...options });

  observer.observe(element);
  return observer;
};
