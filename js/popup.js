/*global dotclear */
'use strict';

dotclear.ready(() => {
  Object.assign(dotclear, dotclear.getData('embed_media'));

  const form = document.querySelector('#media-insert-form');
  if (!form) return;

  const sendClose = (object, url) => {
    const tb = window.opener.the_toolbar;
    const { data } = tb.elements.embedmedia;

    data.alignment = form.querySelector('input[name="media-insert-alignment"]:checked')?.value;
    data.url = url;
    data.m_object = object;

    tb.elements.embedmedia.fncall[tb.mode].call(tb);
    window.close();
  };

  const embedMedia = (event) => {
    const url = form.querySelector('#media-insert-url').value;
    const maxwidth = Number.parseInt(form.querySelector('#media-insert-maxwidth')?.value);
    const maxheight = Number.parseInt(form.querySelector('#media-insert-maxheight')?.value);
    // Call REST method to get embedded media HTML source code if possible
    dotclear.jsonServicesGet(
      'embedMedia',
      (data) => {
        if (data.ret === false) {
          if (data?.error) {
            window.alert(`${dotclear.embed_media.request_error + data.error}`);
          }
          window.close();
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
    );
  };

  document.querySelector('#media-insert-cancel')?.addEventListener('click', (event) => {
    event?.preventDefault();
    window.close();
  });

  document.querySelector('#media-insert-ok')?.addEventListener('click', (event) => {
    embedMedia(event);
  });

  dotclear.enterKeyInForm('#media-insert-form', '#media-insert-ok', '#media-insert-cancel');
});
