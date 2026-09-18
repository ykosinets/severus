const guide = document.querySelector('.sg');
if (guide) {
  const showValues = () => {
    const tokens = getComputedStyle(document.documentElement);
    guide.querySelectorAll('[data-sg-value]').forEach(el => {
      el.textContent = tokens.getPropertyValue(el.dataset.sgValue).trim();
    });
  };
  showValues();
  window.addEventListener('resize', showValues, { passive: true });
  guide.querySelectorAll('[data-sg-copy]').forEach(button => {
    button.addEventListener('click', async () => {
      const value = button.dataset.sgCopy;
      const status = guide.querySelector('[data-sg-status]');
      try {
        await navigator.clipboard.writeText(value);
        status.textContent = `Copied ${value}`;
      } catch {
        status.textContent = `Copy this variable: ${value}`;
      }
    });
  });
  guide.querySelector('[data-sg-demo]').addEventListener('click', () => {
    guide.querySelector('[data-sg-demo-status]').textContent = 'Preview complete. No message was sent.';
  });
  const links = [...guide.querySelectorAll('.sg__nav a')];
  const observer = new IntersectionObserver(entries => {
    const active = entries.find(entry => entry.isIntersecting);
    if (!active) return;
    links.forEach(link => {
      if (link.hash === `#${active.target.id}`) link.setAttribute('aria-current', 'location');
      else link.removeAttribute('aria-current');
    });
  }, { rootMargin: '-15% 0px -60% 0px' });
  guide.querySelectorAll('.sg__section').forEach(section => observer.observe(section));
}
