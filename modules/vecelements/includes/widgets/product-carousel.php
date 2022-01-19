<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

class WidgetProductCarousel extends WidgetProductBase
{
    use CarouselTrait;

    public function getName()
    {
        return 'product-carousel';
    }

    public function getTitle()
    {
        return __('Product Carousel');
    }

    public function getIcon()
    {
        return 'eicon-posts-carousel';
    }

    public function getKeywords()
    {
        return ['product', 'carousel', 'slider'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_product_carousel',
            [
                'label' => __('Product Carousel'),
            ]
        );

        $this->registerListingControls();

        $this->endControlsSection();

        $this->startControlsSection(
            'section_product_layout',
            [
                'label' => __('Product layout'),
            ]
        );
            $this->addControl(
                'product_display',
                [
                    'label' => __('Product display'),
                    'type' => ControlsManager::SELECT,
                    'options' => [
                        '0' => 'Default',
                        '1' => 'Grid view 1',
                        '2' => 'Grid view 2',
                        '3' => 'Grid view 3',
                        '4' => 'Grid view 4',
                        '5' => 'Grid view 5',
                        '6' => 'Grid view 6',
                        '7' => 'List view',
                    ],
                    'default' => '0',
                    'separator' => 'before',
                    'description' => __('Default: use setting from theme options module.'),
                ]
            );
            $this->addControl(
                'enable_slider',
                [
                    'type' => ControlsManager::HIDDEN,
                    'default' => 'yes',
                ]
            );

        $this->endControlsSection();

        $this->registerCarouselSection([
            'default_slides_desktop' => 4,
            'default_slides_tablet' => 3,
            'default_slides_mobile' => 2,
        ]);

        $this->registerNavigationStyleSection();

        //$this->registerCarouselArrowsConfig();
    }

    protected function render()
    {
        if (is_admin()) {
            return print '<div class="ce-remote-render"></div>';
        }
        if (empty($this->context->currency->id)) {
            return;
        }

        $settings = $this->getSettingsForDisplay();

        if ($settings['randomize'] && ('category' === $settings['listing'] || 'products' === $settings['listing'])) {
            $settings['order_by'] = 'rand';
        }

        $products = $this->getProducts(
            $settings['listing'],
            $settings['order_by'],
            $settings['order_dir'],
            $settings['limit'],
            $settings['category_id'],
            $settings['products']
        );

        if (empty($products)) {
            return;
        }

        $slides = [];

        // Theme Skin PS 1.7+
        $tpl = "catalog/_partials/miniatures/product.tpl";

        if (!file_exists(_PS_THEME_DIR_ . "templates/$tpl") &&
            !file_exists(_PS_ALL_THEMES_DIR_ . "{$this->parentTheme}/templates/$tpl")
        ) {
            return;
        }
        // Store product temporary if exists
        isset($this->context->smarty->tpl_vars['product']) && $tmp = $this->context->smarty->tpl_vars['product'];

        foreach ($products as &$product) {
            $this->context->smarty->assign('product', $product);
            $slides[] = '<div class="slick-slide-inner">' . $this->context->smarty->fetch($tpl) . '</div>';
        }
        // Restore product if exists
        isset($tmp) && $this->context->smarty->tpl_vars['product'] = $tmp;
        

        $this->renderCarousel($settings, $slides);
    }
}
