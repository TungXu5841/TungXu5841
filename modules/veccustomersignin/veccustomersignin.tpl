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
<div class="vec-customersignin">
  {if $logged}
  	<div class="currency-selector localiz_block dropdown js-dropdown">
		<button data-target="#" data-toggle="dropdown" class="btn-unstyle hidden-md-down">
			{if $icon}<i class="{$icon}"></i>{/if}
		  	<span class="expand-more">{l s='My account' d='Shop.Theme.Customeraccount'}</span>
		</button>
		<ul class="dropdown-menu">
			<li>
				<a href="{$urls.pages.my_account}" rel="nofollow" class="dropdown-item">{$customerName}</a>
			</li>
			<li>
				<a href="{$urls.pages.my_account}" rel="nofollow" class="dropdown-item">{l s='My account' d='Shop.Theme.Customeraccount'}</a>
			</li>
			<li>
				<a href="{$urls.pages.history}" rel="nofollow" class="dropdown-item">{l s='Order history' d='Shop.Theme.Customeraccount'}</a>
			</li>
			<li>
				<a href="{$urls.pages.discount}" rel="nofollow" class="dropdown-item">{l s='My voucher' d='Shop.Theme.Customeraccount'}</a>
			</li>
			<li>
				<a class="logout dropdown-item" href="{$urls.actions.logout}" rel="nofollow">
					{l s='Sign out' d='Shop.Theme.Actions'}
				</a>
			</li>
		</ul>
	</div>
  {else}
  	{if $icon}<i class="{$icon}"></i>{/if}
    <a class="login" href="{$urls.pages.my_account}" rel="nofollow" title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}">{l s='Sign in' d='Shop.Theme.Actions'}</a>
  {/if}
</div>
