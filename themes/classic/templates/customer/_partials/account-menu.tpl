<div class="links group-link-infomation">
  <a id="identity-link"{if $page.page_name == 'identity'} class="active"{/if} href="{$urls.pages.identity}">
    <span class="link-item">
      <i class="vecicon-user"></i>
      <span>{l s='Information' d='Shop.Theme.Customeraccount'}</span>
    </span>
  </a>

  {if $customer.addresses|count}
    <a id="addresses-link"{if $page.page_name == 'addresses'} class="active"{/if} href="{$urls.pages.addresses}">
      <span class="link-item">
        <i class="vecicon-location-pin"></i>
        <span>{l s='Addresses' d='Shop.Theme.Customeraccount'}</span>
      </span>
    </a>
  {else}
    <a id="address-link"{if $page.page_name == 'address'} class="active"{/if} href="{$urls.pages.address}">
      <span class="link-item">
        <i class="vecicon-location-pin"></i>
        <span>{l s='Add first address' d='Shop.Theme.Customeraccount'}</span>
      </span>
    </a>
  {/if}

  {if !$configuration.is_catalog}
    <a id="history-link"{if $page.page_name == 'history'} class="active"{/if} href="{$urls.pages.history}">
      <span class="link-item">
        <i class="vecicon-time-outline"></i>
        <span>{l s='Order history and details' d='Shop.Theme.Customeraccount'}</span>
      </span>
    </a>
  {/if}

  {if !$configuration.is_catalog}
    <a id="order-slips-link"{if $page.page_name == 'order-slip'} class="active"{/if} href="{$urls.pages.order_slip}">
      <span class="link-item">
        <i class="vecicon-exchange-1"></i>
        <span>{l s='Credit slips' d='Shop.Theme.Customeraccount'}</span>
      </span>
    </a>
  {/if}

  {if $configuration.voucher_enabled && !$configuration.is_catalog}
    <a id="discounts-link"{if $page.page_name == 'discount'} class="active"{/if} href="{$urls.pages.discount}">
      <span class="link-item">
        <i class="vecicon-gift-solid"></i>
        <span>{l s='Vouchers' d='Shop.Theme.Customeraccount'}</span>
      </span>
    </a>
  {/if}

  {if $configuration.return_enabled && !$configuration.is_catalog}
    <a id="returns-link"{if $page.page_name == 'order_follow'} class="active"{/if} href="{$urls.pages.order_follow}">
      <span class="link-item">
        <i class="material-icons">&#xE860;</i>
        <span>{l s='Merchandise returns' d='Shop.Theme.Customeraccount'}</span>
      </span>
    </a>
  {/if}

  {block name='display_customer_account'}
    {hook h='displayCustomerAccount'}
  {/block}
  <a href="{$link->getPageLink('index', true, null, 'mylogout')|escape:'html'}" rel="nofollow" class="btn-logout">
    <span class="link-item">
      <i class="zmdi zmdi-power"></i>
      {l s='Sign out' d='Shop.Theme.Actions'}
    </span>
  </a>
</div>