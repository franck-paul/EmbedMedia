/*global jsToolBar, dotclear */
'use strict';

dotclear.ready(() => {
  const data = dotclear.getData('dc_editor_embedmedia');

  jsToolBar.prototype.elements.embedmediaSpaceBefore = {
    type: 'space',
    format: {
      wysiwyg: true,
      wiki: true,
      xhtml: true,
      markdown: true,
    },
  };
  jsToolBar.prototype.elements.embedmedia = {
    type: 'button',
    title: data.title || 'Embed external Media',
    icon: data.icon,
    icon_dark: data.icon_dark,
    fn: {},
    fncall: {},
    open_url: data.open_url,
    data: {},
    popup() {
      window.the_toolbar = this;
      this.elements.embedmedia.data = {};

      window.open(
        this.elements.embedmedia.open_url,
        'dc_popup',
        'alwaysRaised=yes,dependent=yes,toolbar=yes,height=540,width=760,menubar=no,resizable=yes,scrollbars=yes,status=no',
      );
    },
    getHTML() {
      if (this.data.m_object === '') {
        return '';
      }

      const classes = ['external-media'];
      switch (this.data.alignment) {
        case 'left':
          classes.push(data.class.left);
          break;
        case 'right':
          classes.push(data.class.right);
          break;
        case 'center':
          classes.push(data.class.center);
          break;
      }

      if (this.data.caption?.trim()) {
        return `<figure class="${classes.join(' ')}">\n${this.data.m_object}\n<figcaption>${this.data.caption.trim()}</figcaption>\n</figure>`;
      }

      return `<div class="${classes.join(' ')}">\n${this.data.m_object}\n</div>`;
    },
  };
  jsToolBar.prototype.elements.embedmediaSpaceAfter = {
    type: 'space',
    format: {
      wysiwyg: true,
      wiki: true,
      xhtml: true,
      markdown: true,
    },
  };

  jsToolBar.prototype.elements.embedmedia.fn.wiki = function () {
    this.elements.embedmedia.popup.call(this);
  };
  jsToolBar.prototype.elements.embedmedia.fn.xhtml = function () {
    this.elements.embedmedia.popup.call(this);
  };
  jsToolBar.prototype.elements.embedmedia.fn.markdown = function () {
    this.elements.embedmedia.popup.call(this);
  };

  jsToolBar.prototype.elements.embedmedia.fncall.wiki = function () {
    const html = this.elements.embedmedia.getHTML();

    this.encloseSelection('', '', () => `///html\n${html}\n///\n`);
  };
  jsToolBar.prototype.elements.embedmedia.fncall.xhtml = function () {
    const html = this.elements.embedmedia.getHTML();

    this.encloseSelection('', '', () => html);
  };
  jsToolBar.prototype.elements.embedmedia.fncall.markdown = function () {
    const html = this.elements.embedmedia.getHTML();

    this.encloseSelection('', '', () => html);
  };
});
