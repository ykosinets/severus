import { $ } from '../../src/scripts/util.js';

const head = $('.site-head');
const themeToggle = $('.theme-toggle');

if (themeToggle) {
  const storageKey = 'severus-theme';
  const legacyStorageKey = 'severus-home-theme';
  const lightClass = 'is-light-theme';
  const setTheme = (isLight) => {
    document.body.classList.toggle(lightClass, isLight);
    themeToggle.setAttribute('aria-pressed', String(isLight));
    themeToggle.querySelector('.screen-reader-text').textContent = isLight
      ? 'Enable dark mode'
      : 'Enable light mode';
  };

  setTheme((window.localStorage.getItem(storageKey) || window.localStorage.getItem(legacyStorageKey)) === 'light');
  themeToggle.addEventListener('click', () => {
    const isLight = !document.body.classList.contains(lightClass);
    window.localStorage.setItem(storageKey, isLight ? 'light' : 'dark');
    setTheme(isLight);
  });
}

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
