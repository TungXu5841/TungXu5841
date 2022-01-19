<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace VEC;

defined('_PS_VERSION_') or die;

class WidgetSiteTitle extends WidgetHeading
{
    public function getName()
    {
        return 'theme-site-title';
    }

    public function getTitle()
    {
        return __('Site Title');
    }

    public function getIcon()
    {
        return 'eicon-site-title';
    }

    public function getCategories()
    {
        return ['theme-elements', 'maintenance-theme-elements'];
    }

    public function getKeywords()
    {
        return ['site', 'title', 'name'];
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        $this->updateControl(
            'title',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => \Configuration::get('PS_SHOP_NAME'),
            ]
        );

        $this->updateControl(
            'link',
            [
                'type' => ControlsManager::HIDDEN,
                'default' => [
                    'url' => __PS_BASE_URI__,
                    'is_external' => false,
                ],
            ]
        );
    }

    protected function getHtmlWrapperClass()
    {
        return parent::getHtmlWrapperClass() . ' elementor-widget-' . parent::getName();
    }

    protected function addInlineEditingAttributes($key, $toolbar = 'basic')
    {
        // Prevent inline editing
    }

    protected function _contentTemplate()
    {
        ?>
        <# view.addInlineEditingAttributes = function() {/* Prevent inline editing */} #>
        <?php
        parent::_contentTemplate();
    }
}
