{if isset($posts) AND !empty($posts)}
<div class="elementor-image-carousel {$classes}">
	{foreach from=$posts item=post}
    	{include file="$style" class="" postcategory=$post}
    {/foreach}
</div>
{else}
  <p>{l s='There is no post.'}</p>
{/if}