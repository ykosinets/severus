import { $$, whenSeen } from '../../src/scripts/util.js';

const cards = $$('.card, .sub');

if (cards.length) {
  /* Dropping the class and forcing a reflow restarts the stroke animation, so
     the glyph redraws on hover as well as the first time it is seen. */
  const draw = card => {
    card.classList.remove('is-drawn');
    void card.offsetWidth;
    card.classList.add('is-drawn');
  };

  cards.forEach(card => {
    whenSeen(card, draw, { rootMargin: '0px 0px -12% 0px', threshold: 0.15 });
    card.addEventListener('pointerenter', event => {
      if (event.pointerType !== 'touch') draw(card);
    });
  });
}
