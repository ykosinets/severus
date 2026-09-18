import { $$, still } from '../../src/scripts/util.js';

/* Opens the hero video. The <video> starts at the thumbnail's exact box and is
   animated to its final one — width, height, position and corner radius all at
   once — so it reads as the poster growing rather than a panel appearing.
   Playback starts when the flight lands, and stops the moment it is dismissed. */

const OPEN_MS = 520;
const SHUT_MS = 380;
const EASE = 'cubic-bezier(.16, 1, .3, 1)';
// The resting radius from media-lightbox.pcss: halved where corners are round.
const END_RADIUS = CSS.supports('corner-shape', 'squircle') ? 32 : 16;
const SWIPE = 60;

const phone = matchMedia('(max-width: 900px)');

const boxOf = element => {
  const { left, top, width, height } = element.getBoundingClientRect();
  return { left, top, width, height };
};

/** Where the video ends up: 70vh tall on a desktop, full width on a phone. */
const target = ratio => {
  if (phone.matches) {
    const width = innerWidth;
    const height = Math.min(width / ratio, innerHeight);
    return { left: 0, top: (innerHeight - height) / 2, width, height };
  }

  const height = innerHeight * 0.7;
  const width = Math.min(height * ratio, innerWidth - 40);

  return {
    left: (innerWidth - width) / 2,
    top: (innerHeight - height) / 2,
    width,
    height,
  };
};

const frame = (box, radius) => ({
  left: `${box.left}px`,
  top: `${box.top}px`,
  width: `${box.width}px`,
  height: `${box.height}px`,
  borderRadius: `${radius}px`,
});

const apply = (element, box, radius) => {
  Object.assign(element.style, frame(box, radius));
};

$$('[data-video-open]').forEach(trigger => {
  const ratio = Number(trigger.dataset.width) / Number(trigger.dataset.height) || 16 / 9;
  const thumb = trigger.querySelector('img') || trigger;
  // The flight starts and ends on the thumbnail's own corners.
  const startRadius = () => parseFloat(getComputedStyle(thumb).borderTopLeftRadius) || 0;

  let dialog = null;
  let video = null;
  let poster = null;
  let opening = 0;
  let closing = false;

  const build = () => {
    dialog = document.createElement('dialog');
    dialog.className = 'lightbox';
    dialog.setAttribute('aria-label', trigger.getAttribute('aria-label') || 'Video');

    video = document.createElement('video');
    video.className = 'lightbox__video';
    video.src = trigger.dataset.video;
    video.poster = trigger.dataset.poster;
    video.controls = true;
    video.playsInline = true;
    video.preload = 'auto';       // no loop — the clip plays once

    poster = document.createElement('img');
    poster.className = 'lightbox__poster';
    poster.src = trigger.dataset.poster || thumb.currentSrc || thumb.src;
    poster.alt = '';
    poster.setAttribute('aria-hidden', 'true');

    video.addEventListener('playing', () => {
      if (dialog.open && !closing) dialog.classList.add('is-playing');
    });

    dialog.append(video, poster);
    document.body.append(dialog);

    // A click on the video or its controls must not count as a click past it.
    video.addEventListener('click', event => event.stopPropagation());
    dialog.addEventListener('click', () => close());
    dialog.addEventListener('cancel', event => {
      event.preventDefault();
      close();
    });

    let startY = 0;
    let touch = false;

    dialog.addEventListener('pointerdown', event => {
      touch = event.pointerType === 'touch';
      startY = event.clientY;
    });

    // On a phone a drag in either direction dismisses, the way a sheet does.
    dialog.addEventListener('pointerup', event => {
      if (!touch || Math.abs(event.clientY - startY) < SWIPE) return;
      close();
    });

    addEventListener('resize', () => {
      if (!dialog.open || closing) return;
      apply(video, target(ratio), END_RADIUS);
      apply(poster, target(ratio), END_RADIUS);
    });
  };

  const open = () => {
    if (!dialog) build();

    const from = boxOf(thumb);
    const to = target(ratio);

    closing = false;
    const currentOpening = ++opening;
    dialog.classList.remove('is-playing', 'show-controls');
    dialog.showModal();
    dialog.classList.add('is-open');

    /* The resting geometry is the destination, and the flight is a keyframe
       away from it. If the animation is throttled or never resolves, the video
       is still the right size — only the playback start waits. */
    apply(video, to, END_RADIUS);
    apply(poster, to, END_RADIUS);

    let started = false;
    const settle = () => {
      if (started || closing || !dialog.open || currentOpening !== opening) return;
      started = true;
      video.play().catch(() => {
        if (dialog.open && !closing && currentOpening === opening) {
          dialog.classList.add('show-controls');
        }
      });
    };

    if (still.matches) return settle();

    poster.animate([frame(from, startRadius()), frame(to, END_RADIUS)], { duration: OPEN_MS, easing: EASE });

    video
      .animate([frame(from, startRadius()), frame(to, END_RADIUS)], { duration: OPEN_MS, easing: EASE })
      .finished.then(settle, settle);

    setTimeout(settle, OPEN_MS + 120);
  };

  const close = () => {
    if (!dialog?.open || closing) return;

    closing = true;
    opening++;
    video.pause();
    dialog.classList.remove('is-open');

    let ended = false;
    const done = () => {
      if (ended) return;
      ended = true;
      dialog.close();
      closing = false;
    };

    if (still.matches) return done();

    const from = boxOf(video);
    const home = boxOf(thumb);
    apply(video, home, startRadius());
    apply(poster, home, startRadius());
    poster.animate([frame(from, END_RADIUS), frame(home, startRadius())], { duration: SHUT_MS, easing: EASE });

    video
      .animate([frame(from, END_RADIUS), frame(home, startRadius())], { duration: SHUT_MS, easing: EASE })
      .finished.then(done, done);

    setTimeout(done, SHUT_MS + 120);
  };

  trigger.addEventListener('click', open);
});
