{if isset($id_product)}
<button class="btn-action btn-wishlist js-wishlist-add" data-id-product="{$id_product|intval}" data-id-product-attribute="{$id_product_attribute|intval}" title="{l s='Add to Wishlist' mod='vecwishlist'}">
	{l s='Add to Wishlist' mod='vecwishlist'}
</button>
{/if}