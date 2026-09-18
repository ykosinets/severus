import { $$, onFrame } from '../../src/scripts/util.js';

const steps = $$('.steps__item');

if (steps.length) {
  onFrame(() => {
    const middle = innerHeight / 2;

    steps.forEach(step => {
      const box = step.getBoundingClientRect();
      step.classList.toggle('is-hit', box.top < middle && box.bottom > middle * 0.35);
    });
  });
}
