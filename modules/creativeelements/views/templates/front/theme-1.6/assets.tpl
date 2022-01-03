{**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   One domain support license
 *}
{if isset($cssFiles)}
	{foreach from=$cssFiles key=css_uri item=media}
		<link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}" />
	{/foreach}
{/if}
{if isset($jsFiles)}
	{foreach from=$jsFiles item=js_uri}
		<script type="text/javascript" src="{$js_uri|escape:'html':'UTF-8'}"></script>
	{/foreach}
{/if}
{if isset($jsDefers)}
	{foreach from=$jsDefers item=js_uri}
		<script type="text/javascript" src="{$js_uri|escape:'html':'UTF-8'}" defer></script>
	{/foreach}
{/if}