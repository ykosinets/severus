import { $$, still, whenSeen } from '../../src/scripts/util.js';

$$('.odo').forEach(odo => {
  const target = Number(odo.dataset.odo || 0);
  const places = String(target).length;
  if (!target) return;

  const reels = Array.from({ length: places }, () => {
    const slot = document.createElement('span');
    const reel = document.createElement('span');
    slot.className = 'odo__slot';
    reel.className = 'odo__reel';

    // 0-9 plus a repeated 0, so a column can roll past nine and wrap cleanly.
    for (let n = 0; n <= 10; n++) {
      const cell = document.createElement('span');
      cell.textContent = n % 10;
      reel.append(cell);
    }

    slot.append(reel);
    odo.append(slot);
    return { reel };
  });

  /* Classic odometer: a column only starts turning once the ones below it are
     about to roll over, so the number reads correctly the whole way up.
     Cells are 1em tall, so the offset is in em: it scales with the fluid font
     size and a resize never leaves the digits half-way between cells. */
  const paint = value => reels.forEach(({ reel }, index) => {
    const exact = value / 10 ** (places - 1 - index);
    const fraction = exact - Math.floor(exact);
    const carry = fraction > 0.9 ? (fraction - 0.9) * 10 : 0;
    const position = (Math.floor(exact) % 10) + carry;
    reel.style.transform = `translateY(${-position}em)`;
  });

  paint(0);

  const run = () => {
    if (still.matches) return paint(target);

    const DURATION = 2200;
    const started = performance.now();

    const tick = now => {
      const t = Math.min((now - started) / DURATION, 1);
      const eased = 1 - (1 - t) ** 4;   // fast away, long settle
      paint(target * eased);
      if (t < 1) requestAnimationFrame(tick);
    };

    requestAnimationFrame(tick);
  };

  whenSeen(odo, run, { threshold: 0.4 });
});
