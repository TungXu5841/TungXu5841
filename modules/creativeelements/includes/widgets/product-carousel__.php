<?php
/**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   One domain support license
 */

namespace CE;

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

        $this->addControl(
            'skin',
            [
                'label' => __('Skin'),
                'type' => ControlsManager::SELECT,
                'options' => $this->getSkinOptions(),
                'default' => 'product',
            ]
        );

        $this->registerListingControls();

        $this->endControlsSection();

        $this->registerMiniatureSections();

        $this->registerCarouselSection([
            'default_slides_count' => 4,
        ]);

        $this->startControlsSection(
            'section_style_product',
            [
                'label' => __('Product Box'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'product_spacing_custom',
            [
                'label' => __('Columns Gap'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-list' => 'margin-left: -{{SIZE}}{{UNIT}}; -webkit-clip-path: inset(0 0 0 {{SIZE}}{{UNIT}}); clip-path: inset(0 0 0 {{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .slick-slide .slick-slide-inner' => 'margin-left: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addResponsiveControl(
            'product_spacing_row',
            [
                'label' => __('Rows Gap'),
                'separator' => '',
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .slick-slide .slick-slide-inner' => 'margin-top: calc({{SIZE}}{{UNIT}} / 2); margin-bottom: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $product_selector = '{{WRAPPER}}:not(.wrapfix) .slick-slide-inner > *, {{WRAPPER}}.wrapfix .slick-slide-inner > * > *';
        $product_selector_hover = '{{WRAPPER}}:not(.wrapfix) .slick-slide-inner > :hover, {{WRAPPER}}.wrapfix .slick-slide-inner > * > :hover';

        $this->addResponsiveControl(
            'product_padding',
            [
                'label' => __('Padding'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    $product_selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->addControl(
            'product_border_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'separator' => '',
                'selectors' => [
                    $product_selector => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
                ],
            ]
        );

        $this->addControl(
            'product_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'separator' => '',
                'selectors' => [
                    $product_selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->startControlsTabs('product_style_tabs');

        $this->startControlsTab(
            'product_style_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'product_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    $product_selector => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'product_bg_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-product-miniature' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'skin' => 'custom',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'product_box_shadow',
                'separator' => '',
                'selector' => $product_selector,
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'product_style_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'product_border_color_hover',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    $product_selector_hover => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->addControl(
            'product_bg_color_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'separator' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-product-miniature:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'skin' => 'custom',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'product_box_shadow_hover',
                'separator' => '',
                'selector' => $product_selector_hover,
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->registerMiniatureStyleSections();

        $this->registerNavigationStyleSection();
    }

    protected function render()
    {
        if (is_admin() && $this->getSettings('skin') !== 'custom') {
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

        if ('custom' === $settings['skin']) {
            // Custom Skin
            foreach ($products as &$product) {
                $slides[] = '<div class="slick-slide-inner">' . $this->fetchMiniature($settings, $product) . '</div>';
            }
        } else {
            // Theme Skin PS 1.7+
            $tpl = "catalog/_partials/miniatures/{$settings['skin']}.tpl";

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
        }

        $this->renderCarousel($settings, $slides);
    }
}
