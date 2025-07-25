/*global dotclear */
'use strict';

dotclear.ready(() => {
  Object.assign(dotclear, dotclear.getData('embed_media', false, false));

  const form = document.querySelector('#media-insert-form');
  if (!form) return;

  const sendClose = (object, url) => {
    const tb = window.opener.the_toolbar;
    const { data } = tb.elements.embedmedia;

    data.alignment = form.querySelector('input[name="media-insert-alignment"]:checked')?.value;
    data.url = url;
    data.m_object = object;
    data.caption = form.querySelector('input[name="media-insert-caption"]')?.value;

    tb.elements.embedmedia.fncall[tb.mode].call(tb);
    window.close();
  };

  const embedMedia = (event) => {
    // Stop event propagation as we catch submit from differente source (form, input, …)
    event?.preventDefault();
    event?.stopPropagation();

    const url = form.querySelector('#media-insert-url').value;
    if (!url) {
      window.alert(dotclear.embed_media.url_empty);
      return;
    }

    const waiting = document.querySelector('.ac_waiting');
    if (waiting) {
      waiting.style.width = '4em';
    }

    const maxwidth = Number.parseInt(form.querySelector('#media-insert-maxwidth')?.value);
    const maxheight = Number.parseInt(form.querySelector('#media-insert-maxheight')?.value);
    // Call REST method to get embedded media HTML source code if possible
    dotclear.jsonServicesGet(
      'embedMedia',
      (data) => {
        if (data.ret === false) {
          if (waiting) waiting.classList.remove('ac_loading');
          event?.preventDefault();
          event?.stopPropagation();
          if (data?.error) {
            window.alert(`${dotclear.embed_media.request_error} ${data.error}`);
          } else {
            window.alert(`${dotclear.embed_media.unknown_error}`);
          }
          return;
        }
        // Use data.html
        sendClose(data.html, url);
      },
      {
        url,
        maxwidth,
        maxheight,
      },
      (_error) => {
        if (waiting) waiting.classList.remove('ac_loading');
      },
    );
    if (waiting) waiting.classList.add('ac_loading');
  };

  document.querySelector('#media-insert-cancel')?.addEventListener('click', (event) => {
    event?.preventDefault();
    window.close();
  });

  document.querySelector('#media-insert-ok')?.addEventListener('click', (event) => {
    embedMedia(event);
  });

  form.addEventListener('submit', (event) => {
    embedMedia(event);
  });

  dotclear.enterKeyInForm('#media-insert-form', '#media-insert-ok', '#media-insert-cancel');
});
