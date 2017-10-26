'use strict';

var currentMUShareModuleEditor = null;
var currentMUShareModuleInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getMUShareModulePopupAttributes()
{
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes';
}

/**
 * Open a popup window with the finder triggered by an editor button.
 */
function MUShareModuleFinderOpenPopup(editor, editorName)
{
    var popupUrl;

    // Save editor for access in selector window
    currentMUShareModuleEditor = editor;

    popupUrl = Routing.generate('musharemodule_external_finder', { objectType: 'location', editor: editorName });

    if (editorName == 'ckeditor') {
        editor.popup(popupUrl, /*width*/ '80%', /*height*/ '70%', getMUShareModulePopupAttributes());
    } else {
        window.open(popupUrl, '_blank', getMUShareModulePopupAttributes());
    }
}


var mUShareModule = {};

mUShareModule.finder = {};

mUShareModule.finder.onLoad = function (baseId, selectedId)
{
    if (jQuery('#mUShareModuleSelectorForm').length < 1) {
        return;
    }
    jQuery('select').not("[id$='pasteAs']").change(mUShareModule.finder.onParamChanged);
    
    jQuery('.btn-default').click(mUShareModule.finder.handleCancel);

    var selectedItems = jQuery('#musharemoduleItemContainer a');
    selectedItems.bind('click keypress', function (event) {
        event.preventDefault();
        mUShareModule.finder.selectItem(jQuery(this).data('itemid'));
    });
};

mUShareModule.finder.onParamChanged = function ()
{
    jQuery('#mUShareModuleSelectorForm').submit();
};

mUShareModule.finder.handleCancel = function (event)
{
    var editor;

    event.preventDefault();
    editor = jQuery("[id$='editor']").first().val();
    if ('ckeditor' === editor) {
        mUShareClosePopup();
    } else if ('quill' === editor) {
        mUShareClosePopup();
    } else if ('summernote' === editor) {
        mUShareClosePopup();
    } else if ('tinymce' === editor) {
        mUShareClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function mUShareGetPasteSnippet(mode, itemId)
{
    var quoteFinder;
    var itemPath;
    var itemUrl;
    var itemTitle;
    var itemDescription;
    var pasteMode;

    quoteFinder = new RegExp('"', 'g');
    itemPath = jQuery('#path' + itemId).val().replace(quoteFinder, '');
    itemUrl = jQuery('#url' + itemId).val().replace(quoteFinder, '');
    itemTitle = jQuery('#title' + itemId).val().replace(quoteFinder, '').trim();
    itemDescription = jQuery('#desc' + itemId).val().replace(quoteFinder, '').trim();
    pasteMode = jQuery("[id$='pasteAs']").first().val();

    // item ID
    if (pasteMode === '3') {
        return '' + itemId;
    }

    // relative link to detail page
    if (pasteMode === '1') {
        return mode === 'url' ? itemPath : '<a href="' + itemPath + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
    // absolute url to detail page
    if (pasteMode === '2') {
        return mode === 'url' ? itemUrl : '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }

    return '';
}


// User clicks on "select item" button
mUShareModule.finder.selectItem = function (itemId)
{
    var editor, html;

    html = mUShareGetPasteSnippet('html', itemId);
    editor = jQuery("[id$='editor']").first().val();
    if ('ckeditor' === editor) {
        if (null !== window.opener.currentMUShareModuleEditor) {
            window.opener.currentMUShareModuleEditor.insertHtml(html);
        }
    } else if ('quill' === editor) {
        if (null !== window.opener.currentMUShareModuleEditor) {
            window.opener.currentMUShareModuleEditor.clipboard.dangerouslyPasteHTML(window.opener.currentMUShareModuleEditor.getLength(), html);
        }
    } else if ('summernote' === editor) {
        if (null !== window.opener.currentMUShareModuleEditor) {
            html = jQuery(html).get(0);
            window.opener.currentMUShareModuleEditor.invoke('insertNode', html);
        }
    } else if ('tinymce' === editor) {
        window.opener.currentMUShareModuleEditor.insertContent(html);
    } else {
        alert('Insert into Editor: ' + editor);
    }
    mUShareClosePopup();
};

function mUShareClosePopup()
{
    window.opener.focus();
    window.close();
}

jQuery(document).ready(function() {
    mUShareModule.finder.onLoad();
});
