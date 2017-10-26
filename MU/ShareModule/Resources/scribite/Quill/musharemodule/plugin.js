var musharemodule = function(quill, options) {
    setTimeout(function() {
        var button;

        button = jQuery('button[value=musharemodule]');

        button
            .css('background', 'url(' + Zikula.Config.baseURL + Zikula.Config.baseURI + '/web/modules/mushare/images/admin.png) no-repeat center center transparent')
            .css('background-size', '16px 16px')
            .attr('title', 'Share')
        ;

        button.click(function() {
            MUShareModuleFinderOpenPopup(quill, 'quill');
        });
    }, 1000);
};
