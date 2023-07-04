define([
        "jquery", "Magento_Ui/js/modal/modal",'mage/url'
    ], function($,modal,url){
    var options= {
        wrapperClass: 'devhub-modals-wrapper',
        modalClass: 'devhub-modal',
        overlayClass: 'devhub-modals-overlay',
        responsiveClass: 'devhub-modal-slide',
        type: 'popup',
        responsive: true,
        innerScroll: true,
        title: 'Cancel Order',
        buttons: [{
            text: $.mage.__('Send Request'),
            class: 'devhub-popup-button',
            click: function (data){
                var form_data = $("#devhub-cancellation-form").serialize();
                if ($('#devhub-cancellation-form').valid()) {
                    $.ajax({
                        showLoader: true,
                        url: url.build('developerhub/path/path'),
                        type: 'POST',
                        data: form_data
                    }).done(function (data) {
                        if (data.item_status)
                        {
                            var ItemId = data.item_id;
                            $('.order-cancellation[data-order-id="' + ItemId + '"]').remove();
                            $('.show[data-order-id="' + ItemId + '"]').append(`<span style="color: green">${'your request has been send'}</span>`);
                        }
                        else {
                            var rowId = data.row;
                            $('.order-cancellation[data-order-id="' + rowId + '"]').remove();
                        }
                        data = null;
                        $("#devhub-cancel-order-modal").modal('closeModal');
                        location.reload();
                    }).fail(function () {
                        $("#devhub-cancel-order-modal").modal('closeModal');
                    });
                }
            }
        },
            {
                text: $.mage.__('Cancel Request'),
                class: 'devhub-popup-button',
                click: function (data) {
                    $("#devhub-cancellation-form")[0].reset();
                    $('#devhub-cancel-order-modal').modal('closeModal');
                }
            }
        ]
    };
    $('tr').each(function (){
        modal(options, $("#devhub-cancel-order-modal"));
    });
    $(document).on('click', '.order-cancellation', function () {
        var order_id = $(this).attr('data-order-id');
        var item_sku = $(this).attr('data-order-sku');
        var order = $(this).attr('devhub-cancel-order-id');
        modal(options, $("#devhub-cancel-order-modal"));
        $("#devhub-cancel-order-modal").modal('openModal');
        $(".order-cancellation-id").attr('value', order_id);
        $(".order-cancellation-sku").attr('value', item_sku);
        $(".devhub-cancel-order-id").attr('value', order);
    });
});

