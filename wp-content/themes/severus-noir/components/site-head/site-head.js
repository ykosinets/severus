import { $ } from '../../src/scripts/util.js';

const head = $('.site-head');

if (head) {
  /* A one-pixel sentinel at the top of the document is cheaper than watching
     scroll position, and it never fights the sticky element itself. */
  const sentinel = document.createElement('div');
  sentinel.style.cssText = 'position:absolute;top:0;height:1px;width:1px';
  document.body.prepend(sentinel);

  new IntersectionObserver(
    ([entry]) => head.classList.toggle('is-stuck', !entry.isIntersecting),
    { threshold: 0 }
  ).observe(sentinel);

  /* Published for the drawer, which fills the viewport minus this header. */
  const measure = () =>
    document.documentElement.style.setProperty('--head', `${head.offsetHeight}px`);

  new ResizeObserver(measure).observe(head);
  measure();
}
