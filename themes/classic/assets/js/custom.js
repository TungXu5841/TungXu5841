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
	
	$('#menu-icon') .click(function(){ 
			$(this).toggleClass('open-menu'); 
			var hClass = $(this).hasClass('open-menu');
			if(hClass){
				$(window).resize(function(){
					if($(window).width() < 1024)   
					{
						$(this).parents('body').css( 'overflow','hidden');
					}
				});
				
				$(this).parents('body') .find( '#mobile_menu_wrapper' ) .addClass('box-menu');
			}
			else
			{
				$(this).parents('body').css( 'overflow','visible');
				$(this).parents('body') .find( '#mobile_menu_wrapper' ) .removeClass('box-menu');
				
			}
		});	  
		$('.menu-close') .click(function(){
			$(this).parents('body').css( 'overflow','visible');
			$(this).parents('body') .find( '#mobile_menu_wrapper' ) .removeClass('box-menu');
			$(this).parents('body').find( '#menu-icon' ).removeClass('open-menu');
		});	
})
