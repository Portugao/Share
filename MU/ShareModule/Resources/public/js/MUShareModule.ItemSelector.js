'use strict';

var mUShareModule = {};

mUShareModule.itemSelector = {};
mUShareModule.itemSelector.items = {};
mUShareModule.itemSelector.baseId = 0;
mUShareModule.itemSelector.selectedId = 0;

mUShareModule.itemSelector.onLoad = function (baseId, selectedId)
{
    mUShareModule.itemSelector.baseId = baseId;
    mUShareModule.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    jQuery('#mUShareModuleObjectType').change(mUShareModule.itemSelector.onParamChanged);

    jQuery('#' + baseId + '_catidMain').change(mUShareModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + '_catidsMain').change(mUShareModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'Id').change(mUShareModule.itemSelector.onItemChanged);
    jQuery('#' + baseId + 'Sort').change(mUShareModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'SortDir').change(mUShareModule.itemSelector.onParamChanged);
    jQuery('#mUShareModuleSearchGo').click(mUShareModule.itemSelector.onParamChanged);
    jQuery('#mUShareModuleSearchGo').keypress(mUShareModule.itemSelector.onParamChanged);

    mUShareModule.itemSelector.getItemList();
};

mUShareModule.itemSelector.onParamChanged = function ()
{
    jQuery('#ajaxIndicator').removeClass('hidden');

    mUShareModule.itemSelector.getItemList();
};

mUShareModule.itemSelector.getItemList = function ()
{
    var baseId;
    var params;

    baseId = mUShareModule.itemSelector.baseId;
    params = {
        ot: baseId,
        sort: jQuery('#' + baseId + 'Sort').val(),
        sortdir: jQuery('#' + baseId + 'SortDir').val(),
        q: jQuery('#' + baseId + 'SearchTerm').val()
    }
    if (jQuery('#' + baseId + '_catidMain').length > 0) {
        params[catidMain] = jQuery('#' + baseId + '_catidMain').val();
    } else if (jQuery('#' + baseId + '_catidsMain').length > 0) {
        params[catidsMain] = jQuery('#' + baseId + '_catidsMain').val();
    }

    jQuery.getJSON(Routing.generate('musharemodule_ajax_getitemlistfinder'), params, function( data ) {
        var baseId;

        baseId = mUShareModule.itemSelector.baseId;
        mUShareModule.itemSelector.items[baseId] = data;
        jQuery('#ajaxIndicator').addClass('hidden');
        mUShareModule.itemSelector.updateItemDropdownEntries();
        mUShareModule.itemSelector.updatePreview();
    });
};

mUShareModule.itemSelector.updateItemDropdownEntries = function ()
{
    var baseId, itemSelector, items, i, item;

    baseId = mUShareModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id');
    itemSelector.length = 0;

    items = mUShareModule.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.get(0).options[i] = new Option(item.title, item.id, false);
    }

    if (mUShareModule.itemSelector.selectedId > 0) {
        jQuery('#' + baseId + 'Id').val(mUShareModule.itemSelector.selectedId);
    }
};

mUShareModule.itemSelector.updatePreview = function ()
{
    var baseId, items, selectedElement, i;

    baseId = mUShareModule.itemSelector.baseId;
    items = mUShareModule.itemSelector.items[baseId];

    jQuery('#' + baseId + 'PreviewContainer').addClass('hidden');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (mUShareModule.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id == mUShareModule.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (null !== selectedElement) {
        jQuery('#' + baseId + 'PreviewContainer')
            .html(window.atob(selectedElement.previewInfo))
            .removeClass('hidden');
    }
};

mUShareModule.itemSelector.onItemChanged = function ()
{
    var baseId, itemSelector, preview;

    baseId = mUShareModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id').get(0);
    preview = window.atob(mUShareModule.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    jQuery('#' + baseId + 'PreviewContainer').html(preview);
    mUShareModule.itemSelector.selectedId = jQuery('#' + baseId + 'Id').val();
};

jQuery(document).ready(function() {
    var infoElem;

    infoElem = jQuery('#itemSelectorInfo');
    if (infoElem.length == 0) {
        return;
    }

    mUShareModule.itemSelector.onLoad(infoElem.data('base-id'), infoElem.data('selected-id'));
});
