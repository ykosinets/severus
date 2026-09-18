import { $ } from '../../src/scripts/util.js';

const turn = $('[data-turn]');

if (turn) {
  const clip = $('.turn__video', turn);

  /* Off screen the mask and the loop are pure overhead — drop both. */
  new IntersectionObserver(([entry]) => {
    turn.classList.toggle('is-idle', !entry.isIntersecting);
    if (!clip) return;

    if (entry.isIntersecting) clip.play().catch(() => { /* nothing to do */ });
    else clip.pause();
  }, { rootMargin: '10% 0px' }).observe(turn);
}
