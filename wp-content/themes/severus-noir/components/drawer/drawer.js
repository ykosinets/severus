import { $, $$ } from '../../src/scripts/util.js';

const burger = $('.burger');
const drawer = $('#site-drawer');

if (burger && drawer) {
  /* Core outputs branches as `<a>` + `<ul class="sub-menu">`; turn the parent
     link into a toggle so the second level can expand in place. */
  $$('.drawer__list > li.menu-item-has-children', drawer).forEach(item => {
    const link = item.querySelector(':scope > a');
    const list = item.querySelector(':scope > .sub-menu');
    if (!link || !list) return;

    const toggle = document.createElement('button');
    toggle.type = 'button';
    toggle.className = 'drawer__toggle';
    toggle.setAttribute('aria-expanded', 'false');
    toggle.innerHTML = `${link.textContent.trim()}<span class="drawer__sign" aria-hidden="true"></span>`;

    // Keep the original destination reachable as the first child item.
    const passthrough = document.createElement('li');
    passthrough.innerHTML = `<a href="${link.getAttribute('href')}">${link.textContent.trim()}</a>`;
    list.prepend(passthrough);

    // The 0fr fold needs exactly one child to collapse, so the list gets one.
    const fold = document.createElement('div');
    fold.className = 'drawer__fold';
    list.replaceWith(fold);
    fold.append(list);

    link.replaceWith(toggle);
  });

  const toggles = $$('.drawer__toggle', drawer);

  // Stagger order for the rise-in (drawer.pcss).
  [...$$('.drawer__list > li', drawer), $('.drawer__note', drawer)]
    .filter(Boolean)
    .forEach((item, index) => item.style.setProperty('--i', index));

  let hideTimer = 0;

  const setOpen = open => {
    if (open === (burger.getAttribute('aria-expanded') === 'true')) return;

    burger.setAttribute('aria-expanded', String(open));
    burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    document.documentElement.style.overflow = open ? 'hidden' : '';
    clearTimeout(hideTimer);

    if (open) {
      drawer.hidden = false;
      drawer.scrollTop = 0;
      // Let the closed state paint first, so the curtain has something to animate from.
      requestAnimationFrame(() => requestAnimationFrame(() => drawer.classList.add('is-open')));
      return;
    }

    drawer.classList.remove('is-open');
    // Hide once rolled up; the timer covers a skipped transition.
    const hide = () => {
      if (drawer.classList.contains('is-open')) return;
      drawer.hidden = true;
      toggles.forEach(toggle => toggle.setAttribute('aria-expanded', 'false'));
    };
    hideTimer = setTimeout(hide, 600);
  };

  burger.addEventListener('click', () => setOpen(burger.getAttribute('aria-expanded') !== 'true'));
  drawer.addEventListener('click', event => {
    if (event.target.closest('a')) setOpen(false);
  });
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape') setOpen(false);
  });

  // One branch open at a time — the expand reads more clearly that way.
  toggles.forEach(toggle => toggle.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') === 'true';
    toggles.forEach(other => other.setAttribute('aria-expanded', 'false'));
    toggle.setAttribute('aria-expanded', String(!open));
  }));
}
