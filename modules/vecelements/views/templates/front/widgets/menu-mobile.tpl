<div id="menu-icon"><i class="vecicon-bars-solid"></i></div> 
<div class="menu-mobile-content" id="mobile_menu_wrapper">
	{hook h='displayMegamenuMobileTop'} 
	<div class="menu-close"> 
		{l s='close' d='Shop.Theme.Global'} <i class="material-icons float-xs-right">arrow_back</i>
	</div>
	{if $vmenu}
	<ul class="nav nav-mobile-menu" role="tablist"> 
		<li class="nav-item">
			<a class="nav-link active"  data-toggle="tab" href="#tab-mobile-megamenu" role="tab" aria-controls="mobile-megamenu" aria-selected="true">Menu</a>
			
		</li>
		<li class="nav-item">
			<a class="nav-link"  data-toggle="tab" href="#tab-mobile-vegamenu" role="tab" aria-controls="mobile-vegamenu" aria-selected="true">Categories</a>
		</li>
	</ul>
	{/if}
	{if $vmenu}
	<div class="tab-content">
		  <div class="tab-pane fade active in" id="tab-mobile-megamenu" role="tabpanel" aria-labelledby="megamenu-tab">
	{/if}
		<div id="_mobile_megamenu"></div>
	{if $vmenu}
		</div>
		<div class="tab-pane fade" id="tab-mobile-vegamenu" role="tabpanel" aria-labelledby="vegamenu-tab">
		<div id="_mobile_vegamenu"></div>
		</div>
	</div>
	{/if}
	{hook h='displayMegamenuMobileBottom'}
</div>