$(document).ready(function() {
	
	wishlistRefreshStatus();

	$('body').on('show.bs.modal','.quickview',(function() {
		wishlistRefreshStatus();
	}));
	
    prestashop.on('updatedProductList', function (e) {
        wishlistRefreshStatus();
    });
	
    prestashop.on('updatedProduct', function (e) {
        wishlistRefreshStatus();
    });
	
    prestashop.on('updatedProductAjax', function (e) {
        wishlistRefreshStatus();
    });
	
	$('body').on('click', '.js-wishlist-add', function (event) {
		var self = this;
		prestashop.emit('clickWishListAdd', {
			dataset: self.dataset,
			self: self
		});
		event.preventDefault();
	});

	$('body').on('click', '.js-wishlist-remove', function (event) {
		var self = this;
		prestashop.emit('clickWishListRemove', {
			dataset: self.dataset
		});
		event.preventDefault();
	});

	$('body').on('click', '.js-wishlist-remove-all', function (event) {
		var self = this;
		prestashop.emit('clickWishListRemoveAll', {
			dataset: self.dataset
		});
		event.preventDefault();
	});

	prestashop.on('clickWishListAdd', function (el) {
		
		if($('.js-wishlist-btn-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).hasClass("loading")){
			return;
		}
		
		var data = {
			'process': 'add',
			'ajax': 1,
			'idProduct': el.dataset.idProduct,
			'idProductAttribute': el.dataset.idProductAttribute
		};
		
		$('.js-wishlist-btn-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).addClass('loading');
		
		$.post(wishListVar.actions, data, null, 'json').then(function (resp) {
			if(!resp.is_logged){
				if($('.vec-quicklogin-modal').length > 0){
					$('.vec-quicklogin-modal .modal-header').find('h3').hide();
					$('.wishlist-title').show();
					$('.vec-quicklogin-modal').modal('show');
				}
			}else{
				wishListVar.ids = resp.productsIds;
				$('.js-wishlist-btn-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).removeClass('loading');
				wishlistRefreshStatus();	
			}
			
		}).fail(function (resp) {
			prestashop.emit('handleError', { eventType: 'clickWishListAdd', resp: resp });
		});
	});

	prestashop.on('clickWishListRemove', function (el) {

		if($('.js-wishlist-remove-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).hasClass("loading")){
			return;
		}
		
		var data = {
			'process': 'remove',
			'ajax': 1,
			'idProduct': el.dataset.idProduct,
			'idProductAttribute': el.dataset.idProductAttribute
		};
		
		$('.js-wishlist-remove-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).addClass('loading');
		
		$.post(wishListVar.actions, data, null, 'json').then(function (resp) {		
			
			if(!resp.is_logged){
				wishlistShowLogin(el.dataset.idProduct, el.dataset.idProductAttribute);
			}else{
				$('.js-wishlist-' + el.dataset.idProduct + '-' + el.dataset.idProductAttribute).remove();
				wishListVar.ids = resp.productsIds;
				wishlistRefreshStatus();
				if (wishListVar.ids.length == 0) {
					$('#js-wishlist-table').remove();
					$('#js-wishlist-warning').show();
				}
			}
								
		}).fail(function (resp) {
			prestashop.emit('handleError', { eventType: 'clickWishListRemove', resp: resp });
		});
	});

	prestashop.on('clickWishListRemoveAll', function (el) {
		
		if($('.js-wishlist-remove-all').hasClass('loading')){
			return;
		}
		
		var data = {
			'process': 'removeAll',
			'ajax': 1
		};
		
		$('.js-wishlist-remove-all').addClass('loading');
		
		$.post(wishListVar.actions, data, null, 'json').then(function (resp) {
			
			wishListVar.ids = resp.productsIds;
			wishlistRefreshStatus();
			
			$('#js-wishlist-table').remove();
			$('#js-wishlist-warning').show();
		}).fail(function (resp) {
			prestashop.emit('handleError', { eventType: 'clickWishListRemoveAll', resp: resp });
		});
	});
	
	function wishlistShowLogin($idProduct, $idProductAttribute)
	{
		var data = {
			'process': 'login',
			'ajax': 1,
			'current_url': prestashop.urls.current_url
		};
		
		$.post(wishListVar.login, data, null, 'json').then(function (resp) {
			$('#wishlist_login').html(resp.html);
			$('#wishlist_login').find('input[name=email]').focus();
			setTimeout(function(){
				$('.modal:not(#modal_wishlist)').modal('hide');
				$('#modal_wishlist').modal('show');
				if(!(typeof $idProduct === 'undefined' || typeof $idProductAttribute === 'undefined')){
					$('.js-wishlist-btn-' + $idProduct + '-' + $idProductAttribute).removeClass('loading');
				}
			}, 300);
		}).fail(function (resp) {
			prestashop.emit('handleError', { eventType: 'wishlistShowLogin', resp: resp });
		});
		
	}
		
	function wishlistRefreshStatus()
	{
		$('.js-wishlist').each(function(){
			
			var $el = $(this);
			var $idProduct = $el.data('id-product');
			var $idProductAttribute = $el.data('id-product-attribute');		
			
			if (wishListVar.ids.includes($idProduct + '-' + $idProductAttribute)){
				$el.removeClass('js-wishlist-add').addClass('added');
				$el.attr('href', wishListVar.url);
				$el.find('.text').text(wishListVar.alert.view);
				if (typeof $(this).attr('data-original-title') !== typeof undefined && $(this).attr('data-original-title') !== false) {
					$el.attr('data-original-title', wishListVar.alert.view);
				}else{
					$el.attr('title', wishListVar.alert.view);
				}
			}else{
				$el.addClass('js-wishlist-add').removeClass('added');
				$el.find('.text').text(wishListVar.alert.add);
				if (typeof $(this).attr('data-original-title') !== typeof undefined && $(this).attr('data-original-title') !== false) {
					$el.attr('data-original-title', wishListVar.alert.add);
				}else{
					$el.attr('title', wishListVar.alert.add);
				}
			}
			
			$el.addClass('js-wishlist-btn-'+$idProduct + '-' + $idProductAttribute);
			
		});
		
		$('.js-wishlist-nb').text(wishListVar.ids.length);
						
	}
	
	$('#wishlist-clipboard-btn').on('click', function () {

		var $this = $(this);

		$this.closest('.input-group').find('input.js-to-clipboard').select();

		if (document.execCommand('copy')) {
			$this.text($this.data('textCopied'));
			setTimeout(function () {
				$this.text($this.data('textCopy'));
			}, 1500);
		}
		
	});
	
});