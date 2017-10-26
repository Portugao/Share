/**
 * Initializes the plugin, this will be executed after the plugin has been created.
 * This call is done before the editor instance has finished it's initialization so use the onInit event
 * of the editor instance to intercept that event.
 *
 * @param {tinymce.Editor} ed Editor instance that the plugin is initialised in
 * @param {string} url Absolute URL to where the plugin is located
 */
tinymce.PluginManager.add('musharemodule', function(editor, url) {
    var icon;

    icon = Zikula.Config.baseURL + Zikula.Config.baseURI + '/web/modules/mushare/images/admin.png';

    editor.addButton('musharemodule', {
        //text: 'Share',
        image: icon,
        onclick: function() {
            MUShareModuleFinderOpenPopup(editor, 'tinymce');
        }
    });
    editor.addMenuItem('musharemodule', {
        text: 'Share',
        context: 'tools',
        image: icon,
        onclick: function() {
            MUShareModuleFinderOpenPopup(editor, 'tinymce');
        }
    });

    return {
        getMetadata: function() {
            return {
                title: 'Share',
                url: 'https://homepages-mit-zikula.de'
            };
        }
    };
});
