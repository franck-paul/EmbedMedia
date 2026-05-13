/*global dotclear */
'use strict';

dotclear.ready(() => {
  const data = dotclear.getData('dc_editor_embedmedia');

  dotclear.ToolBar.prototype.elements.embedmedia = {
    group: 'media',
    type: 'button',
    title: data.title || 'Embed external Media',
    icon: data.icon,
    icon_dark: data.icon_dark,
    fn: {},
    fncall: {},
    open_url: data.open_url,
    data: {},
    popup() {
      globalThis.the_toolbar = this;
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

  dotclear.ToolBar.prototype.elements.embedmedia.fn.wiki = function () {
    this.elements.embedmedia.popup.call(this);
  };
  dotclear.ToolBar.prototype.elements.embedmedia.fn.xhtml = function () {
    this.elements.embedmedia.popup.call(this);
  };
  dotclear.ToolBar.prototype.elements.embedmedia.fn.markdown = function () {
    this.elements.embedmedia.popup.call(this);
  };

  dotclear.ToolBar.prototype.elements.embedmedia.fncall.wiki = function () {
    const html = this.elements.embedmedia.getHTML();

    this.encloseSelection('', '', () => `///html\n${html}\n///\n`);
  };
  dotclear.ToolBar.prototype.elements.embedmedia.fncall.xhtml = function () {
    const html = this.elements.embedmedia.getHTML();

    this.encloseSelection('', '', () => html);
  };
  dotclear.ToolBar.prototype.elements.embedmedia.fncall.markdown = function () {
    const html = this.elements.embedmedia.getHTML();

    this.encloseSelection('', '', () => html);
  };
});
