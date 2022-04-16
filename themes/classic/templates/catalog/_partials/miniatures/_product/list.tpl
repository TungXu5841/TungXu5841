
{block name='product_miniature_item'}
	
	<article class="thumbnail-container style_product_list product-miniature js-product-miniature item_in" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
		<div class="img_block">
		  {block name='product_thumbnail'}
			<a href="{$product.url}" class="thumbnail product-thumbnail rotator-animation-{$vectheme.rotator}">
			  <img class="first-image lazyload"
			  	src="{$product.cover.bySize.cart_default.url}" width="{$product.cover.bySize.cart_default.width}" height="{$product.cover.bySize.cart_default.height}" 
				alt = "{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
				data-full-size-image-url = "{$product.cover.large.url}"
			  >
			  {if $vectheme.rotator}
				{foreach from=$product.images item=image}
					{if !$image.cover}
						<img
							src="{$image.bySize.cart_default.url}"
							data-src="{$image.bySize.cart_default.url}"
							width="{$image.bySize.cart_default.width}"
							height="{$image.bySize.cart_default.height}"
							alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if} 2"
							class="lazy-product-image product-thumbnail-rotator"  loading="lazy"
						>
						{break}
					{/if}
				{/foreach}
		   {/if}
			</a>
		  {/block}
		</div>
		<div class="product_desc"> 
			{if isset($product.id_manufacturer)}
			 <div class="manufacturer"><a href="{url entity='manufacturer' id=$product.id_manufacturer }">{Manufacturer::getnamebyid($product.id_manufacturer)}</a></div>
			{/if}
			{block name='product_reviews'}
				<div class="hook-reviews">
				{hook h='displayProductListReviews' product=$product}
				</div>
			{/block}
			{block name='product_name'}
			  <h3 itemprop="name"><a href="{$product.url}" class="product_name" title="{$product.name}">{$product.name}</a></h3> 
			{/block}
			
			{block name='product_price_and_shipping'}
			  {if $product.show_price}
				<div class="product-price-and-shipping">
				  {if $product.has_discount}
					{hook h='displayProductPriceBlock' product=$product type="old_price"}

					<span class="sr-only">{l s='Regular price' d='Shop.Theme.Catalog'}</span>
					<span class="regular-price">{$product.regular_price}</span>
				  {/if}

				  {hook h='displayProductPriceBlock' product=$product type="before_price"}

				  <span class="sr-only">{l s='Price' d='Shop.Theme.Catalog'}</span>
				  <span itemprop="price" class="price {if $product.has_discount}price-sale{/if}">{$product.price}</span>
				  {hook h='displayProductPriceBlock' product=$product type='unit_price'}

				  {hook h='displayProductPriceBlock' product=$product type='weight'}
				</div>
			  {/if}
			{/block} 
			
		</div>
	</article>
{/block}