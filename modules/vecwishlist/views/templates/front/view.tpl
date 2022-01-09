{**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

{extends file='customer/page.tpl'}
	 
{block name='page_content_title'}
	<h4>
	  	{l s='Wishlist' mod='vecwishlist'}
	</h4>
{/block}

{block name='page_content_content'}
    {if isset($wlProducts) && $wlProducts}
	 
		{$imageType	= 'home_default'}

		{if isset($opThemect.general_product_image_type_small) && $opThemect.general_product_image_type_small}
			{$imageType = $opThemect.general_product_image_type_small}
		{/if}	
	 	<div id="my_wishlist">
	 		<div id="js-wishlist-table" class="wrapper-wishlist-table">
				<div class="wishlist-table-actions" style="display: none;">
					<a href="javascript:void(0)" class="js-wishlist-remove-all">
						<i class="las la-trash-alt"></i> {l s='Remove all products' mod='vecwishlist'}
					</a>
				</div>
				<table class="shop_table_responsive shop_table">
					<thead>
						<tr>
							{if !$readOnly}<th class="product-remove"></th>{/if}
							<th class="product-thumbnail"></th>
							<th class="product-name">{l s='Name' mod='vecwishlist'}</th>
							<th class="product-w-price">{l s='Price' mod='vecwishlist'}</th>
							<th class="product-stock">{l s='Stock' mod='vecwishlist'}</th>
							<th class="product-button"></th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$wlProducts item="product"}
							<tr class="js-wishlist-{$product.id_product}-{$product.id_product_attribute}">
								{if !$readOnly}
									<td class="product-remove">
										<a href="javascript:void(0)" class="js-wishlist-remove btn-action-wishlist-remove js-wishlist-remove-{$product.id_product|intval}-{$product.id_product_attribute|intval}"
											data-id-product="{$product.id_product|intval}"
											data-id-product-attribute="{$product.id_product_attribute|intval}">
											{l s='Remove' mod='vecwishlist'}
										</a>
									</td>
								{/if}
								<td class="product-thumbnail">
									<a class="product-image" href="{$product.url}" title="{$product.name}">
									  <div class="img-placeholder {$imageType}">
										{if $product.default_image}
											{$image = $product.default_image}
										{else}
											{$image = $urls.no_picture_image}
										{/if}
										<img
											class="img-loader lazy-load" 
											data-src="{$image.bySize.{$imageType}.url}"
											src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" 
											alt="{if !empty($image.legend)}{$image.legend}{else}{$product.name}{/if}"
											title="{if !empty($image.legend)}{$image.legend}{else}{$product.name}{/if}" 
											width="{$image.bySize.{$imageType}.width}"
											height="{$image.bySize.{$imageType}.height}"
										> 
									  </div>
									</a>  
								</td>
								<td class="product-name">
									<a class="product-title" href="{$product.url}">{$product.name}</a>
									<div class="text-muted">
									{foreach from=$product.attributes item="attribute"}
										<div><small class="label">{$attribute.group}: </small><small>{$attribute.name}</small></div>
									{/foreach}
									</div>
								</td>
								<td class="product-price price">
									{if $product.show_price}
										{hook h='displayProductPriceBlock' product=$product type="before_price"}
										{if $product.has_discount}
										  {hook h='displayProductPriceBlock' product=$product type="old_price"}
										  <span class="regular-price">{$product.regular_price}</span>&nbsp;&nbsp;
										{/if}
										<span class="price">{$product.price}</span>
										{hook h='displayProductPriceBlock' product=$product type='unit_price'}
										{hook h='displayProductPriceBlock' product=$product type='weight'}
									{/if}
								</td>	
								<td class="product-stock">
									{if $product.show_availability && $product.availability_message}
										{if $product.availability == 'available'}
											<span class="type-available">
										{elseif $product.availability == 'last_remaining_items'}
											<span class="type-last-remaining-items">
										{else}
											<span class="type-out-stock">
										{/if}
											{$product.availability_message}
											</span>
									{/if}
								</td>
								<td class="product-button">
									<div class="js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
										<form action="{$urls.pages.cart}" method="post">
											 {if !$configuration.is_catalog && $product.add_to_cart_url && ($product.quantity > 0 || $product.allow_oosp)}
												  {if !$product.id_product_attribute}
													<input type="hidden" name="token" value="{$static_token}">
													<input type="hidden" name="id_product" value="{$product.id}">
													<input type="number"
														   name="qty"
														   value="{$product.minimal_quantity}"
														   class="hidden"
														   min="{$product.minimal_quantity}"
													>
													<a 	href="javascript:void(0)" 
														class="btn-action btn button add-to-cart" data-button-action="add-to-cart" 
														title="{l s='Add to cart' d='Shop.Theme.Actions'}">
														{l s='Add to cart' d='Shop.Theme.Actions'}
													</a>
												  {else}
													<a 	href="javascript:void(0)" 
														class="btn-action btn button add-to-cart quick-view" data-link-action="quickview" 
														title="{l s='Select options' d='Shop.Theme.Actions'}">
														{l s='Select options' d='Shop.Theme.Actions'}
													</a>       
												  {/if}
											  {else}
												<a  href="{$product.url}" 
													class="btn-action btn button add-to-cart" title="{l s='Discover' mod='nrtcompare'}">
													{l s='Discover' mod='nrtcompare'}
												</a>
											  {/if}
										</form>
									</div>	
								</td>							
							</tr>
						{/foreach}
					</tbody>
				</table>
				{if !$readOnly}
					<h5>{l s='Share your wishlist' mod='vecwishlist'}</h5>
					<div class="input-group">
						<input class="form-control js-to-clipboard" readonly="readonly" type="url" value="{url entity='module' name='vecwishlist' relative_protocol=false controller='view' params=['token' => $token]}">
						<span class="input-group-btn">
							<button class="btn btn-secondary" type="button" id="wishlist-clipboard-btn" data-text-copied="{l s='Copied' mod='vecwishlist'}" data-text-copy="{l s='Copy' mod='vecwishlist'}">{l s='Copy' mod='vecwishlist'}</button>
						</span>
					</div>
					{hook h='displayWishListShareButtons'}
				{/if}
			</div>
	 	</div>
		<div id="js-wishlist-warning" style="display:none;" class="empty-products">
			<p class="empty-title empty-title-wishlist">
				{l s='Wishlist is empty.' mod='vecwishlist'}				
			</p>
			<div class="empty-text">
				{l s='No products added in the wishlist list. You must add some products to wishlist them.' mod='vecwishlist'}
			</div>
			<p class="return-to-home">
				<a href="{$urls.pages.index}" class="btn btn-primary">
					<i class="las la-reply"></i>
					{l s='Return to home' mod='vecwishlist'}
				</a>
			</p>
		</div>
    {else}
		<div class="empty-products">
			<p class="empty-title empty-title-wishlist">
				{l s='Wishlist list is empty.' mod='vecwishlist'}				
			</p>
			<div class="empty-text">
				{l s='No products added in the wishlist list. You must add some products to wishlist them.' mod='vecwishlist'}
			</div>
			<p class="return-to-home">
				<a href="{$urls.pages.index}" class="btn btn-primary">
					<i class="las la-reply"></i>
					{l s='Return to home' mod='vecwishlist'}
				</a>
			</p>
		</div>
    {/if}
{/block}


