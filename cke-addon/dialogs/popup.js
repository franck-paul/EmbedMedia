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
        let style = '';
        let classes = 'external_media';
        if (dotclear.ck_embedmedia.style.class) {
          if (alignment === 'left') {
            classes += ` ${dotclear.ck_embedmedia.style.left}`;
          } else if (alignment === 'right') {
            classes += ` ${dotclear.ck_embedmedia.style.right}`;
          } else if (alignment === 'center') {
            classes += ` ${dotclear.ck_embedmedia.style.center}`;
          }
        } else if (alignment === 'left') {
          style = 'float: left; margin: 0 1em 1em 0;';
        } else if (alignment === 'right') {
          style = 'float: right; margin: 0 0 1em 1em;';
        } else if (alignment === 'center') {
          style = 'margin: 1em auto; text-align: center;';
        }
        div.setAttribute('class', classes);
        if (style !== '') {
          div.setAttribute('style', style);
        }

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
