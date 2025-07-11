/*global dotclear, CKEDITOR */
'use strict';

CKEDITOR.dialog.add('embedMediaDialog', (editor) => ({
  title: dotclear.ck_embedmedia.title,
  minWidth: 400,
  minHeight: 150,
  contents: [
    {
      id: 'tab-embed',
      label: '',
      elements: [
        {
          id: 'url',
          type: 'text',
          label: dotclear.ck_embedmedia.url,
          validate: CKEDITOR.dialog.validate.notEmpty(dotclear.ck_embedmedia.url_empty),
        },
        {
          type: 'radio',
          id: 'alignment',
          label: dotclear.ck_embedmedia.align,
          items: [
            [dotclear.ck_embedmedia.align_none, 'none'],
            [dotclear.ck_embedmedia.align_left, 'left'],
            [dotclear.ck_embedmedia.align_right, 'right'],
            [dotclear.ck_embedmedia.align_center, 'center'],
          ],
          default: dotclear.ck_embedmedia.align_default,
        },
        {
          type: 'vbox',
          padding: 0,
          children: [
            {
              id: 'maxwidth',
              type: 'text',
              label: dotclear.ck_embedmedia.maxwidth,
              controlStyle: 'width:5em',
              default: dotclear.ck_embedmedia.maxwidth_default,
              validate: CKEDITOR.dialog.validate.number(dotclear.ck_embedmedia.invalid_number),
            },
            {
              id: 'maxheight',
              type: 'text',
              label: dotclear.ck_embedmedia.maxheight,
              controlStyle: 'width:5em',
              default: dotclear.ck_embedmedia.maxheight_default,
              validate: CKEDITOR.dialog.validate.number(dotclear.ck_embedmedia.invalid_number),
            },
          ],
        },
      ],
    },
  ],
  onOk() {
    const url = this.getValueOf('tab-embed', 'url');
    const alignment = this.getValueOf('tab-embed', 'alignment');
    const maxwidth = Math.abs(Number.parseInt(this.getValueOf('tab-embed', 'maxwidth')));
    const maxheight = Math.abs(Number.parseInt(this.getValueOf('tab-embed', 'maxheight')));

    // Call REST method to get embedded media HTML source code if possible
    dotclear.jsonServicesGet(
      'embedMedia',
      (data) => {
        if (data.ret === false) {
          // An error has occured, may be display something?
          return;
        }
        // Use data.html
        const div = editor.document.createElement('div');
        let classes = 'external_media';
        if (alignment === 'left') {
          classes += ` ${dotclear.ck_embedmedia.class.left}`;
        } else if (alignment === 'right') {
          classes += ` ${dotclear.ck_embedmedia.class.right}`;
        } else if (alignment === 'center') {
          classes += ` ${dotclear.ck_embedmedia.class.center}`;
        }
        div.setAttribute('class', classes);
        div.appendHtml(data.html);
        editor.insertElement(div);
      },
      {
        url,
        maxwidth,
        maxheight,
      },
    );
  },
}));
