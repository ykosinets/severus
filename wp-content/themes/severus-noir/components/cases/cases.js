import { startLoading } from '../../src/scripts/loading-bar.js';

const pageFromLink = link => {
  const match = new URL(link.href, window.location.origin).pathname.match(/\/page\/(\d+)\/?$/);
  return match ? Number(match[1]) : 1;
};

document.querySelectorAll('[data-cases]').forEach(section => {
  const endpoint = section.dataset.casesEndpoint;
  const results = section.querySelector('[data-cases-results]');
  const pagination = section.querySelector('[data-cases-pagination]');
  let category = section.querySelector('[data-cases-filter][aria-current="page"]')?.dataset.caseCategory || 'all';
  const initialState = { category, page: pageFromLink({ href: window.location.href }) };
  let request = 0;

  const setActiveFilter = selectedCategory => {
    section.querySelectorAll('[data-cases-filter]').forEach(link => {
      const active = link.dataset.caseCategory === selectedCategory;
      link.classList.toggle('btn--solid', active);
      link.classList.toggle('btn--quiet', !active);
      if (active) link.setAttribute('aria-current', 'page');
      else link.removeAttribute('aria-current');
    });
  };

  const load = async (page, fallbackUrl, updateUrl = true) => {
    const currentRequest = ++request;
    section.setAttribute('aria-busy', 'true');
    const stopLoading = startLoading();

    try {
      const form = new URLSearchParams({ action: 'severus_filter_cases', category, page: String(page) });
      const response = await fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
        body: form,
      });
      const payload = await response.json();

      if (!response.ok || !payload.success) throw new Error('Case archive request failed');
      if (currentRequest !== request) return;

      results.innerHTML = payload.data.results;
      pagination.innerHTML = payload.data.pagination;
      setActiveFilter(category);
      document.dispatchEvent(new CustomEvent('severus:content-updated', { detail: { root: results } }));
      if (updateUrl) window.history.pushState({ category, page }, '', payload.data.url);
    } catch (error) {
      if (currentRequest === request) window.location.assign(fallbackUrl);
    } finally {
      stopLoading();
      if (currentRequest === request) {
        section.removeAttribute('aria-busy');
      }
    }
  };

  section.addEventListener('click', event => {
    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    const filter = event.target.closest('[data-cases-filter]');
    const pager = event.target.closest('[data-case-page], [data-cases-pagination] a.page-numbers');

    if (filter) {
      event.preventDefault();
      category = filter.dataset.caseCategory;
      load(1, filter.href);
      return;
    }

    if (pager) {
      const page = Number(pager.dataset.casePage || pageFromLink(pager));
      event.preventDefault();
      load(page, pager.href || window.location.href);
    }
  });

  window.history.replaceState(initialState, '', window.location.href);
  window.addEventListener('popstate', event => {
    const state = event.state || initialState;
    category = state.category || 'all';
    load(Number(state.page) || 1, window.location.href, false);
  });
});
