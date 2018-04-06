define([
    'mage/adminhtml/grid'
], function () {
    'use strict';

    return function (config) {
        var selectedAttachments = config.selectedAttachments,
            pageAttachments = $H(selectedAttachments),
            gridJsObject = window[config.gridJsObjectName],
            tabIndex = 1000;

        $('sy_attachments_hidden_input').value = Object.toJSON(pageAttachments);

        function registerAttachment(grid, element, checked) {
            if (checked) {
                pageAttachments.set(element.value, element.value);
            } else {
                pageAttachments.unset(element.value);
            }
            $('sy_attachments_hidden_input').value = Object.toJSON(pageAttachments);
            grid.reloadParams = {
                'selected_attachments[]': pageAttachments.keys()
            };
        }

        function attachmentRowClick(grid, event) {
            var trElement = Event.findElement(event, 'tr'),
                isInput = Event.element(event).tagName === 'INPUT',
                checked = false,
                checkbox = null;

            if (trElement) {
                checkbox = Element.getElementsBySelector(trElement, 'input');

                if (checkbox[0]) {
                    checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                    gridJsObject.setCheckboxChecked(checkbox[0], checked);
                }
            }
        }

        function positionChange(event) {
            var element = Event.element(event);

            if(element && element.checked) {
                pageAttachments.set(element.value, element.value);
            }
            else{
                pageAttachments.unset(element.value, element.value);
            }
            $('sy_attachments_hidden_input').value = Object.toJSON(pageAttachments);
        }

        function attachmentRowInit(grid, row) {
            var checkbox = $(row).getElementsByClassName('checkbox')[0];

            if (checkbox) {
                Event.observe(checkbox, 'change', positionChange);
            }
        }

        gridJsObject.rowClickCallback = attachmentRowClick;
        gridJsObject.initRowCallback = attachmentRowInit;
        gridJsObject.checkboxCheckCallback = registerAttachment;

        if (gridJsObject.rows) {
            gridJsObject.rows.each(function (row) {
                attachmentRowInit(gridJsObject, row);
            });
        }
    };
});
