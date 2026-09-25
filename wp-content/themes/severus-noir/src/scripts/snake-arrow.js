/* The drum arrow (see severus_arrow()). While the pointer is on an element
   with data-snake-arrow (or it has keyboard focus) its drums spin steadily,
   one turn every SPIN seconds, easing in; when it leaves, the drum eases on
   to the next face and stops there, so an arrow always faces front at rest.
   Each face fades with its angle — fully there at the front, gone at the
   sides — so an arrow fades out as it rolls away and in as it comes round.
   With reduced motion the arrow stays put. */
const SPIN = 4;               // seconds per full turn
const SPEED = 360 / SPIN;     // degrees per second
const EASE_IN = 0.35;         // seconds to reach full speed
const SETTLE = 6;             // how briskly it settles on a face

const still = matchMedia('(prefers-reduced-motion: reduce)');

export const initializeSnakeArrows = (root = document) => {
  root.querySelectorAll('[data-snake-arrow]').forEach(trigger => {
    if (trigger.dataset.snakeArrowReady) return;
    trigger.dataset.snakeArrowReady = 'true';

    const drums = trigger.querySelectorAll('.snake-arrow__drum');
    if (!drums.length) return;

    const faces = [...drums].map(drum => [...drum.querySelectorAll('.snake-arrow__face')]);

    let angle = 0;
    let speed = 0;
    let active = false;
    let frame = 0;
    let last = 0;

    const paint = () => {
      drums.forEach((drum, index) => {
        drum.style.setProperty('--turn', `${angle.toFixed(2)}deg`);
        faces[index].forEach((face, position) => {
          const facing = Math.cos(((angle - position * 90) * Math.PI) / 180);
          face.style.opacity = Math.max(0, facing).toFixed(3);
        });
      });
    };

    const tick = now => {
      const step = Math.min((now - last) / 1000, 0.05);
      last = now;

      if (active) {
        speed = Math.min(SPEED, speed + (SPEED / EASE_IN) * step);
        angle += speed * step;
      } else {
        const stop = Math.ceil(angle / 90 - 1e-6) * 90;
        const gap = stop - angle;
        // Carry the spin on, slowing into the next face.
        const move = Math.min(gap, Math.max(speed * step, gap * SETTLE * step));
        speed = Math.max(0, speed - (SPEED / EASE_IN) * step);
        angle += move;

        if (stop - angle < 0.05) {
          angle = stop % 360;
          speed = 0;
          frame = 0;
          paint();
          return;
        }
      }

      paint();
      frame = requestAnimationFrame(tick);
    };

    const start = () => {
      if (still.matches) return;
      active = true;
      if (frame) return;
      last = performance.now();
      frame = requestAnimationFrame(tick);
    };

    const stop = () => {
      active = false;
    };

    trigger.addEventListener('pointerenter', event => {
      if (event.pointerType !== 'touch') start();
    });
    trigger.addEventListener('pointerleave', stop);

    trigger.addEventListener('focus', () => {
      if (trigger.matches(':focus-visible')) start();
    });
    trigger.addEventListener('blur', stop);
  });
};

initializeSnakeArrows();

document.addEventListener('severus:content-updated', event => {
  initializeSnakeArrows(event.detail?.root || document);
});
