{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
{if $product.default_image}
  <meta itemprop="image" content="{$product.default_image.bySize.medium_default.url}">
{/if}
<div class="images-container js-images-container">
  {block name='product_cover'}
    <div class="product-images-cover">
      {if $product.default_image}
        <div class="product-images slick-block column-desktop-1 column-tablet-1 column-mobile-1">
            {foreach from=$product.images item=image}
              <div class="cover-item">
                <div class="easyzoom easyzoom--overlay">
                  <a href="{$image.bySize.large_default.url}">
                    <img class="" src="{$image.bySize.large_default.url}" alt="{$image.legend}" title="{$image.legend}" itemprop="image">
                  </a>
                </div>
              </div>
            {/foreach}
        </div>
        <div class="icon-zoom hidden-sm-down" data-toggle="modal" data-target="#product-modal">
          <i class="vecicon-expand-outline zoom-in"></i>
        </div>
      {else}
        <img
          class="img-fluid"
          src="{$urls.no_picture_image.bySize.medium_default.url}"
          loading="lazy"
          width="{$urls.no_picture_image.bySize.medium_default.width}"
          height="{$urls.no_picture_image.bySize.medium_default.height}"
        >
      {/if}
    </div>
  {/block}

  {block name='product_images'}
    <div class="product-images-thumb">
      <div class="product-thumbs js-qv-product-images slick-block column-desktop-5 column-tablet-4 column-mobile-3" data-item="{$vectheme.thumbnail_items}">
        {foreach from=$product.images item=image}
          <div class="thumb-item js-thumb-container">
            <img
              class="thumb js-thumb {if $image.id_image == $product.default_image.id_image} selected js-thumb-selected {/if}"
              data-image-medium-src="{$image.bySize.medium_default.url}"
              data-image-large-src="{$image.bySize.large_default.url}"
              src="{$image.bySize.small_default.url}"
              {if !empty($image.legend)}
                alt="{$image.legend}"
                title="{$image.legend}"
              {else}
                alt="{$product.name}"
              {/if}
              loading="lazy"
            >
          </div>
        {/foreach}
      </div>
    </div>
  {/block}
{hook h='displayAfterProductThumbs' product=$product}
</div>
