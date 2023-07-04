require(
    ['jquery','mage/url'], function($,url) {
        console.log("JS Loaded Successfully!");
        $('#item-cancellation').on('click', function() {
            var id = $(this).attr('data-order-id');
            $(".order-cancellation-id").attr('value', id);
            var form_data = $("#devhub-cancellation-form").serializeArray();
            $.ajax({
                showLoader: true,
                url: 'http://magento24.test/admin/developerhub/index/cancel',
                type: 'POST',
                dataType: 'json',
                data: {form_key: window.FORM_KEY , form_data}
            }).done(function (data) {
                if (data.row){
                    location.reload();
                }
            });
        });
    });
