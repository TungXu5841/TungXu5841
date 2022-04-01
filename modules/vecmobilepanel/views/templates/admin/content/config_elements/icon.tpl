{*
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="form-group">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3 first-block">
        <div class="text-right">
            <label class="control-label">
                {l s='Icon' d='Modules.Vecmobilepanel.Admin'}
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 first-block">
        <div class="psr_picto_showing input-group col-lg-8">
            {if isset($block)}
                {if $block['custom_icon']}
                    <img class="psr-picto picto_by_module svg" src="{$block['custom_icon']}"/>
                {else}
                    <i class="{$block['icon']}" style="font-size: 50px;"></i>
                {/if}
            {/if}
            <div>
                <i class="material-icons landscape">landscape</i>
                <img class="image-preview-lang img-thumbnail hide" src="" alt="" width="24px" height="24px"/>
            </div>
            <div class="icon_chosed_here">
                <i class=""></i>
            </div>
            <select class="vecicon-selection" style="width: 200px;">
                {foreach from=$vecicons key=key item=value}
                    <option value="{$key}">{$value}</option>
                {/foreach}
            </select>
        </div>
        <div class="input-group upload_file_button">
            <label class="file_label" for="file{if isset($block)}{$block['id_mobilepanel']}{/if}" data-label="{l s='or upload file' d='Modules.Vecmobilepanel.Admin'}">{l s='or upload file' d='Modules.Vecmobilepanel.Admin'}</label>
            <label class="input-group-btn">
                <span>
                    <i class="icon-file"></i><input id="file{if isset($block)}{$block['id_mobilepanel']}{/if}" class="slide_image" data-preview="image-preview-lang" type="file" name="image-lang">
                </span>
            </label>
        </div>
        <div class="help-block">
            {l s='Choose SVG for better customization. Other allowed formats are: .gif, .jpg, .png' d='Modules.Vecmobilepanel.Admin'}
        </div>
    </div>
    <div class="clearfix"></div>
</div>
