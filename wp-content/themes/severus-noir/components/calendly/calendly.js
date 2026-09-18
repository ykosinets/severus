/* Calendly booking in a dialog. Any link to a calendly.com scheduling page
   opens that page inside the site instead of leaving it; Calendly's embed
   script loads on the first such click. Modified clicks (new tab/window) and
   visitors without JS still follow the link.

   Only Calendly's calendar is embedded (hide_event_type_details): the host,
   title and details sit in our own side panel, cloned from the template that
   components/calendly/calendly.php prints. The page the dialog was opened on
   is passed to Calendly as UTM data, unless the link carries its own.

   Colours: Calendly takes hex values in the URL, applied on a paid plan. They
   are read from the --booking-* custom properties in calendly.pcss. On a free
   plan the calendar is darkened with --booking-filter instead. */

const SCRIPT = 'https://assets.calendly.com/assets/external/widget.js';
const HOSTS = ['calendly.com', 'www.calendly.com'];

let dialog = null;
let frame = null;
let loader = null;
let lastFocus = null;

/** Any CSS colour (oklch included) as a 6-digit hex without the #. */
const hex = value => {
  const canvas = document.createElement('canvas');
  canvas.width = canvas.height = 1;
  const ctx = canvas.getContext('2d', { willReadFrequently: true });
  ctx.fillStyle = '#000';
  ctx.fillStyle = value;
  ctx.fillRect(0, 0, 1, 1);
  const [r, g, b] = ctx.getImageData(0, 0, 1, 1).data;
  return [r, g, b].map(channel => channel.toString(16).padStart(2, '0')).join('');
};

const colours = () => {
  const style = getComputedStyle(dialog);
  const read = name => style.getPropertyValue(name).trim();
  return {
    background_color: hex(read('--booking-bg')),
    text_color: hex(read('--booking-text')),
    primary_color: hex(read('--booking-primary')),
  };
};

const loadScript = () => {
  loader ||= new Promise((resolve, reject) => {
    if (window.Calendly) return resolve(window.Calendly);
    const script = document.createElement('script');
    script.src = SCRIPT;
    script.async = true;
    script.onload = () => (window.Calendly ? resolve(window.Calendly) : reject(new Error('Calendly did not load')));
    script.onerror = () => reject(new Error('Calendly did not load'));
    document.head.append(script);
  });
  return loader;
};

const close = () => {
  if (!dialog?.open) return;
  dialog.classList.remove('is-open');
  dialog.close();
  frame.replaceChildren();
  document.documentElement.classList.remove('has-booking');
  lastFocus?.focus?.();
};

const build = () => {
  dialog = document.createElement('dialog');
  dialog.className = 'booking';
  dialog.setAttribute('aria-label', 'Book a call');
  dialog.innerHTML = `
    <div class="booking__shell">
      <div class="booking__panel edge">
        <button class="booking__close" type="button" aria-label="Close">
          <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </button>
        <div class="booking__calendar">
          <div class="booking__frame"></div>
          <p class="booking__state" hidden></p>
        </div>
      </div>
    </div>`;

  const aside = document.getElementById('severus-booking');
  if (aside) dialog.querySelector('.booking__panel').prepend(aside.content.cloneNode(true));
  const title = dialog.querySelector('.booking__title');
  if (title) {
    title.id = 'booking-title';
    dialog.removeAttribute('aria-label');
    dialog.setAttribute('aria-labelledby', title.id);
  }

  document.body.append(dialog);

  frame = dialog.querySelector('.booking__frame');
  dialog.querySelector('.booking__close').addEventListener('click', close);
  // A click on the backdrop lands on the dialog itself.
  dialog.addEventListener('click', event => {
    if (event.target === dialog || event.target.classList.contains('booking__shell')) close();
  });
  dialog.addEventListener('cancel', event => {
    event.preventDefault();
    close();
  });
};

const state = (text, link) => {
  const note = dialog.querySelector('.booking__state');
  note.hidden = !text;
  note.innerHTML = '';
  if (!text) return;
  note.append(text);
  if (link) {
    const a = document.createElement('a');
    a.href = link;
    a.target = '_blank';
    a.rel = 'noopener';
    a.textContent = 'Open the booking page';
    note.append(' ', a);
  }
};

const open = url => {
  if (!dialog) build();
  lastFocus = document.activeElement;

  frame.replaceChildren();
  dialog.classList.add('is-loading');
  state('');
  dialog.showModal();
  document.documentElement.classList.add('has-booking');
  requestAnimationFrame(() => dialog.classList.add('is-open'));

  const target = new URL(url);
  Object.entries({ ...colours(), hide_gdpr_banner: '1', hide_event_type_details: '1' })
    .forEach(([key, value]) => target.searchParams.set(key, value));

  // Where the booking came from, unless the link already says.
  const tagged = [...target.searchParams.keys()].some(key => key.startsWith('utm_'));
  const utm = tagged ? {} : {
    utmSource: location.hostname,
    utmMedium: 'website',
    utmContent: location.pathname,
  };

  loadScript()
    .then(Calendly => {
      Calendly.initInlineWidget({ url: target.toString(), parentElement: frame, utm });
    })
    .catch(() => {
      dialog.classList.remove('is-loading');
      state('The booking calendar could not be loaded.', url);
    });
};

/* Calendly reports what happens inside the frame. */
const fromCalendly = origin => {
  try {
    return HOSTS.includes(new URL(origin).hostname);
  } catch {
    return false;
  }
};

window.addEventListener('message', event => {
  if (!fromCalendly(event.origin)) return;
  const name = event.data?.event;
  if (typeof name !== 'string' || !name.startsWith('calendly.')) return;

  if (name === 'calendly.event_type_viewed') dialog?.classList.remove('is-loading');
  if (name === 'calendly.event_scheduled') {
    document.dispatchEvent(new CustomEvent('severus:booked', { detail: event.data.payload }));
    window.dataLayer?.push({ event: 'calendly_booked' });
  }
});

document.addEventListener('click', event => {
  if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

  const link = event.target.closest('a[href]');
  if (!link) return;

  let url;
  try {
    url = new URL(link.href);
  } catch {
    return;
  }

  // A scheduling page has at least a user segment: calendly.com/<user>[/<event>].
  if (!HOSTS.includes(url.hostname) || url.pathname.split('/').filter(Boolean).length < 1) return;
  if (!('showModal' in HTMLDialogElement.prototype)) return;

  event.preventDefault();
  open(url.toString());
});
