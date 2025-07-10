/*global jsToolBar, dotclear */
'use strict';

dotclear.ready(() => {
  const data = dotclear.getData('dc_editor_embedmedia');

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
        'alwaysRaised=yes,dependent=yes,toolbar=yes,height=500,width=760,menubar=no,resizable=yes,scrollbars=yes,status=no',
      );
    },
    gethtml() {
      const d = this.data;

      if (d.m_object === '') {
        return false;
      }

      const classes = ['external-media'];
      let style = '';
      if (data.style.class) {
        switch (d.alignment) {
          case 'left':
            classes.push(data.style.left);
            break;
          case 'right':
            classes.push(data.style.right);
            break;
          case 'center':
            classes.push(data.style.center);
            break;
        }
      } else {
        switch (d.alignment) {
          case 'left':
            style = ` style="${data.style.left}"`;
            break;
          case 'right':
            style = ` style="${data.style.right}"`;
            break;
          case 'center':
            style = ` style="${data.style.center}"`;
            break;
        }
      }

      return `<div class="${classes.join(' ')}"${style}>\n${d.m_object}\n</div>`;
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
    const html = this.elements.embedmedia.gethtml();

    this.encloseSelection('', '', () => `///html\n${html}\n///\n`);
  };
  jsToolBar.prototype.elements.embedmedia.fncall.xhtml = function () {
    const html = this.elements.embedmedia.gethtml();

    this.encloseSelection('', '', () => html);
  };
  jsToolBar.prototype.elements.embedmedia.fncall.markdown = function () {
    const html = this.elements.embedmedia.gethtml();

    this.encloseSelection('', '', () => html);
  };
});
