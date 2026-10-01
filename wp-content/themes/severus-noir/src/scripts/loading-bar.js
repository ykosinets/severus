const pending = new Set();
let timer;
let reset;
let progress = 0;

/** Temporarily use the existing reading bar for pending requests. */
export const startLoading = () => {
  const bar = document.querySelector('.readbar');
  const token = {};
  pending.add(token);
  clearTimeout(reset);
  if (pending.size === 1) {
    progress = 0.12;
    bar?.classList.add('is-loading');
    bar?.style.setProperty('--request-progress', progress);
    clearInterval(timer);
    timer = setInterval(() => {
      progress += (0.9 - progress) * 0.12;
      bar?.style.setProperty('--request-progress', progress);
    }, 250);
  }

  return () => {
    if (!pending.delete(token) || pending.size) return;
    clearInterval(timer);
    bar?.style.setProperty('--request-progress', '1');
    reset = setTimeout(() => {
      bar?.classList.remove('is-loading');
      bar?.style.removeProperty('--request-progress');
    }, 250);
  };
};
