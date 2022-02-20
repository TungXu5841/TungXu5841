var vecCompare = {
    addCompare: function(obj, id, product_name, product_image){
        $.ajax({
            type: 'POST',
            url: veccompare.baseDir + 'module/veccompare/actions',
            dataType: 'json',
            data: {
                action : 'add',
                id: id,
                ajax : true
            },
            success: function(data)
            {  
                veccompare.nbProducts++;
                $('#veccompare-nb, #qmcompare-count').text(veccompare.nbProducts);
                var html = '';
                    html += '<div class="modal fade" id="compareModal">';
                    html += '<div class="modal-dialog"><div class="modal-content">';
                        html += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="material-icons">close</i></button>';
                        html += '<div class="modal-body">';
                            html += '<img src="' + product_image + '" alt="' + product_name + '" />';
                            html += '<h4>' + product_name + '</h4>';
                            html += veccompare.success_text;
                            html += '<a class="btn-primary" href="' + veccompare.compare_url + '">' + veccompare.compare_text + '</a>';
                        html += '</div>';
                    html += '</div></div></div>';
                $("body").append(html);
                $('.quickview').modal('hide');
                $('#compareModal').modal('show');
                $('#compareModal').on('hidden.bs.modal', function () {
                    $('#compareModal').remove();
                });
                obj.addClass('cmp_added');
            },
            error: function (jqXHR, status, err) {
                 obj.addClass('cmp_added');
            }
        })
    },
    removeCompare: function(id){
        vecCompare.blockUI('#veccompare-table');
        $.ajax({
            type: 'POST',
            url: veccompare.baseDir + 'module/veccompare/actions',
            dataType: 'json',
            data: {
                action : 'remove',
                id: id,
                ajax : true
            },
            success: function(data)

            {
                $('.js-veccompare-product-' + id).remove();
                veccompare.nbProducts--;
                $('#veccompare-nb, #qmcompare-count').text(veccompare.nbProducts);

                if (veccompare.nbProducts == 0) {
                    $('#veccompare-table').remove();
                    $('#veccompare-warning').removeClass('hidden-xs-up');
                }
                vecCompare.unblockUI('#veccompare-table');
            }
        })
    },
    removeAllCompare: function(){
        vecCompare.blockUI('#content');
        $.ajax({
            type: 'POST',
            url: veccompare.baseDir + 'module/veccompare/actions',
            dataType: 'json',
            data: {
                action : 'removeAll',
                ajax : true
            },
            success: function(data)

            {
                $('#veccompare-nb, #qmcompare-count').text(0);
                $('#veccompare-table').remove();
                $('#veccompare-warning').removeClass('hidden-xs-up');
                vecCompare.unblockUI('#content');
            }
        })
    },

    checkCompare : function (){
        var target = $('.veccompare-add');
        var compareList = veccompare.idProducts;
        target.each(function(){
            var $id = $(this).data('id-product');
            var flag = false;
            $.each( compareList, function( key, value ) {
              if($id == value) {
                flag = true;
              };
            });
            if(flag) {
                $(this).addClass('cmp_added');
            }
        })
    },
    blockUI: function(selector){
        $(selector).addClass('ar-blocked');
        $(selector).find('.ar-loading').remove();
        $(selector).append('<div class="ar-loading"><div class="ar-loading-inner"></div></div>');
    },
    unblockUI: function(selector){
        $(selector).find('.ar-loading').remove();
        $(selector).removeClass('ar-blocked');
    },
};

$(document).ready(function () { 
    vecCompare.checkCompare();
    
    $('#veccompare-nb, #qmcompare-count').text(veccompare.nbProducts);
    $('body').on('click', '.js-veccompare-remove-all', function (event) {
        vecCompare.removeAllCompare();
        event.preventDefault();
    });
    $(".veccompare-add").click(function(e) {
        e.preventDefault();
        var id = $(this).data('id-product'),
            product_name = $(this).data('product-name'),
            product_image = $(this).data('product-image');
        vecCompare.addCompare($(this), id, product_name, product_image);
    }); 
    
});

