/*global dotclear, CKEDITOR */
'use strict';

dotclear.ck_embedmedia = dotclear.getData('ck_editor_embedmedia');

CKEDITOR.plugins.add('embedmedia', {
  requires: 'dialog',

  init(editor) {
    editor.addCommand('embedMediaCommand', new CKEDITOR.dialogCommand('embedMediaDialog'));

    CKEDITOR.dialog.add('embedMediaDialog', `${this.path}dialogs/popup.js`);

    editor.ui.addButton('EmbedMedia', {
      label: dotclear.ck_embedmedia.title,
      command: 'embedMediaCommand',
      icon: `${this.path}icons/icon.svg`,
    });
  },
});
