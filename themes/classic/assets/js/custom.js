$(document).ready(function(){
    $('input.form-control').each(function(){
        $(this)
            .focus(function(e){
                $(this).parents('.form-group').find('.form-control-label').addClass('has-value');
            })
            .focusout(function(e){
                if(!$(this).val()){
                    $(this).parents('.form-group').find('.form-control-label').removeClass('has-value');
                }
                
            });
    })
    
    prestashop.on('updatedAddressForm', function (event) {
        $('input.form-control').each(function(){
            $(this)
                .focus(function(e){
                    $(this).parents('.form-group').find('.form-control-label').addClass('has-value');
                })
                .focusout(function(e){
                    if(!$(this).val()){
                        $(this).parents('.form-group').find('.form-control-label').removeClass('has-value');
                    }
                    
                });
        })
    });
})