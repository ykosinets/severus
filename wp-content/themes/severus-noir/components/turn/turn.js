import { $ } from '../../src/scripts/util.js';

const turn = $('[data-turn]');

if (turn) {
  const clip = $('.turn__video', turn);

  /* Do not fetch the 0.5 MB loop during the critical rendering path. It only
     receives a source just before the transition enters the viewport. */
  const load = () => {
    if (!clip || clip.src || !clip.dataset.src) return;
    clip.src = clip.dataset.src;
    clip.load();
  };

  /* Off screen the mask and the loop are pure overhead — drop both. */
  new IntersectionObserver(([entry]) => {
    turn.classList.toggle('is-idle', !entry.isIntersecting);
    if (!clip) return;

    if (entry.isIntersecting) {
      load();
      clip.play().catch(() => { /* nothing to do */ });
    }
    else clip.pause();
  }, { rootMargin: '25% 0px' }).observe(turn);
}
