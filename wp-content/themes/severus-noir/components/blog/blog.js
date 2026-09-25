import { startLoading } from '../../src/scripts/loading-bar.js';

document.querySelectorAll('[data-blog]').forEach(section => {
  const content = section.querySelector('[data-blog-content]');
  let controller;
  let request = 0;

  // The filter stays put (and keeps focus); only its current button moves.
  const setActiveFilter = slug => {
    section.querySelectorAll('[data-blog-filter]').forEach(link => {
      const active = link.dataset.blogFilter === slug;
      link.classList.toggle('btn--solid', active);
      link.classList.toggle('btn--quiet', !active);
      if (active) link.setAttribute('aria-current', 'page');
      else link.removeAttribute('aria-current');
    });
  };

  /* A page's worth of posts replaces the current one. Paging moves focus and
     scroll to the list; a filter click leaves both on the filter. */
  const load = async (url, updateHistory = true, { reveal = updateHistory } = {}) => {
    const current = ++request;
    controller?.abort();
    controller = new AbortController();
    const activeController = controller;
    const stopLoading = startLoading();
    section.setAttribute('aria-busy', 'true');
    const timeout = setTimeout(() => activeController.abort(), 20000);

    try {
      const response = await fetch(url, { signal: controller.signal });
      if (!response.ok) throw new Error('Blog request failed');
      const page = new DOMParser().parseFromString(await response.text(), 'text/html');
      const next = page.querySelector('[data-blog] [data-blog-content]');
      if (!next) throw new Error('Blog content missing');
      if (current !== request) return;

      content.replaceChildren(...next.childNodes);
      setActiveFilter(page.querySelector('[data-blog-filter][aria-current="page"]')?.dataset.blogFilter || 'all');
      document.title = page.title;
      if (updateHistory) window.history.pushState({ blog: true }, '', response.url);
      document.dispatchEvent(new CustomEvent('severus:content-updated', { detail: { root: content } }));
      if (reveal) {
        content.focus({ preventScroll: true });
        section.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth' });
      }
    } catch (error) {
      if (current === request) window.location.assign(url);
    } finally {
      clearTimeout(timeout);
      stopLoading();
      if (current === request) section.removeAttribute('aria-busy');
    }
  };

  section.addEventListener('click', event => {
    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    const filter = event.target.closest('[data-blog-filter]');
    if (filter && section.contains(filter)) {
      event.preventDefault();
      setActiveFilter(filter.dataset.blogFilter);
      load(filter.href, true, { reveal: false });
      return;
    }

    const link = event.target.closest('.pager a.page-numbers');
    if (!link || !section.contains(link)) return;
    event.preventDefault();
    load(link.href);
  });

  window.addEventListener('popstate', () => load(window.location.href, false));
});
