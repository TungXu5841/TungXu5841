{**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 *}

{extends file="helpers/list/list_content.tpl"}

{block name="td_content"}
	{if 'preview' === $key}
		<style>{str_replace('{{BASE}}', $smarty.const.__PS_BASE_URI__, $tr.$key)}</style>
		<span style="font-family: '{$tr.$key->family|escape:'html':'UTF-8'}'; font-size: 16px;">
			V-Elements PageBuilder is making the web beautiful!
		</span>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
