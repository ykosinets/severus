import { $, $$, clamp, onFrame, still } from '../../src/scripts/util.js';

/* The stack reads as depth rather than as four cards of one size: each slab
   shrinks by how many slabs have driven over it, not just by the next one, so
   the pinned tops fan out behind the card being read. The last slab has no
   successor and stays full size; the pile leaves as soon as it lands. */

const FALLOFF = 0.94;   // per slab of depth
const FADE = 1.35;      // content is gone before the cover fully lands

const stack = $('[data-stack]');

if (stack) {
  const slabs = $$('.slab', stack);
  const lead = $('[data-stack-lead]');
  const section = stack.closest('.familiar');
  const last = slabs[slabs.length - 1];

  const setVar = (name, value, element = section) => {
    if (element.style.getPropertyValue(name) !== value) element.style.setProperty(name, value);
  };

  /* Adjacent margins collapse to the larger positive one, or to their sum
     when one is negative, so the slab below cancels the hold with a negative
     margin when the hold outgrows the gap and simply restates the gap when it
     doesn't. */
  const setHolds = holds => {
    const gap = parseFloat(getComputedStyle(stack).rowGap) || 0;

    slabs.forEach((slab, index) => {
      setVar('--hold', `${holds[index]}px`, slab);

      if (index) {
        const hold = holds[index - 1];
        setVar('--above', `${hold > gap ? gap - hold : gap}px`, slab);
      }
    });
  };

  /* Slabs pin below the header, so they need its height; the slabs and the
     header need to know where the pile lets go (see .stack and
     .familiar__lead). Pinned tops are read from computed style, which is
     independent of the scale below. */
  const pin = () => {
    if (getComputedStyle(last).position !== 'sticky') {
      setVar('--lead-h', '0px');
      setVar('--tail', '0px');
      setHolds(slabs.map(() => 0));
      return;
    }

    const leadPinned = lead && getComputedStyle(lead).position === 'sticky';

    setVar('--lead-h', leadPinned ? `${lead.offsetHeight}px` : '0px');

    /* Where each slab's bottom sits while pinned, and the lowest of them —
       the line the whole pile lets go at. */
    const bottoms = slabs.map(slab => parseFloat(getComputedStyle(slab).top) + slab.offsetHeight);
    const floor = Math.max(...bottoms);

    setHolds(bottoms.map(bottom => Math.round(floor - bottom)));

    if (!leadPinned) {
      setVar('--tail', '0px');
      return;
    }

    /* The pile lets go when the stack's end reaches that line. The header's margin
       box has to reach the same end at the same moment, and its margin
       already carries the flow gap on top of the current tail. */
    const leadStyle = getComputedStyle(lead);
    const gap = parseFloat(leadStyle.marginBottom) - (parseFloat(section.style.getPropertyValue('--tail')) || 0);
    const leadBottom = parseFloat(leadStyle.top) + lead.offsetHeight;
    const tail = floor - leadBottom - gap;

    setVar('--tail', `${Math.max(0, Math.round(tail))}px`);
  };

  const clear = () => slabs.forEach(slab => {
    slab.style.removeProperty('--slab-scale');
    slab.style.removeProperty('--slab-fade');
  });

  /* Pinning is layout, not motion, so it runs with reduced motion too. */
  onFrame(pin);

  if (still.matches) {
    clear();
  } else {
    onFrame(() => {
      if (getComputedStyle(slabs[0]).position !== 'sticky') return clear();

      /* How far the thing above each slab has covered it, 0 to 1.
         offsetHeight is the untransformed height, so scaling can't feed back. */
      const steps = slabs.map((slab, index) => {
        const next = slabs[index + 1];

        /* Nothing arrives over the last slab, so it never recedes. */
        if (!next) {
          return 0;
        }

        const top = slab.getBoundingClientRect().top;

        return clamp(1 - (next.getBoundingClientRect().top - top) / slab.offsetHeight, 0, 1);
      });

      /* Accumulated from the bottom up, so a slab keeps receding as the ones
         above it keep arriving. */
      let depth = 0;

      for (let index = slabs.length - 1; index >= 0; index--) {
        depth += steps[index];
        slabs[index].style.setProperty('--slab-scale', (FALLOFF ** depth).toFixed(4));

        /* The content fades because something is arriving over it; the last
           slab keeps its copy. */
        if (index === slabs.length - 1) continue;

        slabs[index].style.setProperty('--slab-fade', (1 - Math.min(1, steps[index] * FADE)).toFixed(4));
      }
    });
  }
}
