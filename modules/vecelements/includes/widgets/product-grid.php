<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

class WidgetProductGrid extends WidgetProductBase
{
    public function getName()
    {
        return 'product-grid';
    }

    public function getTitle()
    {
        return __('Product Grid');
    }

    public function getIcon()
    {
        return 'eicon-posts-grid';
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_grid_settings',
            [
                'label' => __('Product Grid'),
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

        $this->registerListingControls('num_of_prods');

        $this->addResponsiveControl(
            'columns',
            [
                'label' => __('Columns'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'selectors' => [
                    '{{WRAPPER}} .elementor-product-grid' => '-ms-grid-columns: repeat({{VALUE}}, minmax(0, 1fr)); grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
                ],
                'default' => 4,
                'tablet_default' => 3,
                'mobile_default' => 1,
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();

        $this->registerMiniatureSections();

        $this->startControlsSection(
            'section_style_product',
            [
                'label' => __('Product Box'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addResponsiveControl(
            'product_column_gap',
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
                    '{{WRAPPER}} .elementor-product-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->addResponsiveControl(
            'product_row_gap',
            [
                'label' => __('Rows Gap'),
                'type' => ControlsManager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-product-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $product_selector = '{{WRAPPER}}:not(.wrapfix) .elementor-product-grid > *, {{WRAPPER}}.wrapfix .elementor-product-grid > * > *';
        $product_selector_hover = '{{WRAPPER}}:not(.wrapfix) .elementor-product-grid > :hover, {{WRAPPER}}.wrapfix .elementor-product-grid > * > :hover';

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
                'selector' => $product_selector_hover,
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->endControlsSection();

        $this->registerMiniatureStyleSections();
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
            $settings['num_of_prods'],
            $settings['category_id'],
            $settings['products']
        );

        if (empty($products)) {
            return;
        }

        if ('custom' === $settings['skin']) {
            // Custom Skin
            echo '<div class="elementor-product-grid">';
            foreach ($products as &$product) {
                echo $this->fetchMiniature($settings, $product);
            }
            echo '</div>';
       
        } else {
            // Theme Skin PS 1.7+
            $tpl = "catalog/_partials/miniatures/{$settings['skin']}.tpl";

            if (empty($products) || !(file_exists(_PS_THEME_DIR_ . "templates/$tpl") ||
                file_exists(_PS_ALL_THEMES_DIR_ . "{$this->parentTheme}/templates/$tpl"))
            ) {
                return;
            }
            // Store product temporary if exists
            isset($this->context->smarty->tpl_vars['product']) && $tmp = $this->context->smarty->tpl_vars['product'];

            echo '<div class="elementor-product-grid">';
            foreach ($products as &$product) {
                $this->context->smarty->assign('product', $product);
                echo $this->context->smarty->fetch($tpl);
            }
            echo '</div>';

            // Restore product if exists
            isset($tmp) && $this->context->smarty->tpl_vars['product'] = $tmp;
        }
    }
}
