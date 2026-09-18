import { $, $$ } from '../../src/scripts/util.js';

$$('[data-contact-form]').forEach(form => {
  const note = $('[data-form-note]', form);
  const label = $('[data-submit-label]', form);
  const area = $('[data-counter]', form);
  const out = $('[data-counter-out]', form);
  const idle = label ? label.textContent : '';

  area?.addEventListener('input', () => {
    if (out) out.textContent = area.value.length;
  });

  const say = (message, bad = false) => {
    if (!note) return;
    note.textContent = message;
    note.classList.toggle('is-bad', bad);
    note.hidden = !message;
  };

  const mark = (field, bad) => field.classList.toggle('is-bad', bad);

  /* Checked here for the inline marks; the server checks again and is the one
     that decides. */
  const problems = data => {
    const missing = [];

    for (const name of ['name', 'company', 'email', 'details']) {
      const field = form.elements[name];
      const empty = !String(data.get(name) || '').trim();
      mark(field, empty);
      if (empty) missing.push(name);
    }

    const email = String(data.get('email') || '');
    if (!missing.includes('email') && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)) {
      mark(form.elements.email, true);
      missing.push('email');
    }

    return missing;
  };

  form.addEventListener('submit', async event => {
    event.preventDefault();

    const data = new FormData(form);

    if (problems(data).length) {
      say(form.dataset.errorRequired || 'Please fill in every field with a valid email.', true);
      return;
    }

    form.classList.add('is-sending');
    if (label) label.textContent = form.dataset.labelSending || 'Sending…';
    say('');

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        headers: { Accept: 'application/json' },
        body: data,
      });

      const payload = await response.json().catch(() => ({}));

      if (!response.ok) {
        throw new Error(payload.message || 'Request failed');
      }

      form.reset();
      if (out) out.textContent = '0';
      $$('.is-bad', form).forEach(field => field.classList.remove('is-bad'));
      say(payload.message || 'Thank you — we will come back to you shortly.');
    } catch (error) {
      say(error.message || 'Something went wrong. Please email us instead.', true);
    } finally {
      form.classList.remove('is-sending');
      if (label) label.textContent = idle;
    }
  });
});
