/*global $, dotclear */
'use strict';

dotclear.ready(() => {
  Object.assign(dotclear, dotclear.getData('embed_media'));

  $('#media-insert-cancel').on('click', () => {
    window.close();
  });

  const sendClose = (object) => {
    const insert_form = $('#media-insert-form').get(0);
    if (insert_form === undefined) {
      return;
    }

    const tb = window.opener.the_toolbar;
    const { data } = tb.elements.embedmedia;

    data.alignment = $('input[name="alignment"]:checked', insert_form).val();
    data.url = insert_form.m_url.value;
    data.m_object = object;

    tb.elements.embedmedia.fncall[tb.mode].call(tb);
    window.close();
  };

  const embedMedia = (event) => {
    const url = $('#media-insert-form').get(0).m_url.value;
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
        sendClose(data.html);
      },
      {
        url,
      },
    );
    event?.preventDefault();
  };

  $('#media-insert-ok').on('click', (event) => embedMedia(event));

  dotclear.enterKeyInForm('media-insert-form', 'media-insert-ok', 'media-insert-cancel');
});
