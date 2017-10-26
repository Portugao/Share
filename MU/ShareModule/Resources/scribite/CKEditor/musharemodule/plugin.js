CKEDITOR.plugins.add('musharemodule', {
    requires: 'popup',
    init: function (editor) {
        editor.addCommand('insertMUShareModule', {
            exec: function (editor) {
                MUShareModuleFinderOpenPopup(editor, 'ckeditor');
            }
        });
        editor.ui.addButton('musharemodule', {
            label: 'Share',
            command: 'insertMUShareModule',
            icon: this.path.replace('scribite/CKEditor/musharemodule', 'images') + 'admin.png'
        });
    }
});
