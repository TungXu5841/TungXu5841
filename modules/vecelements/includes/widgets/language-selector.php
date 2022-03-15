<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

/**
 * Language Selector widget
 *
 * @since 2.5.0
 */
class WidgetLanguageSelector extends WidgetBase
{
    use NavTrait;

    /**
     * Get widget name.
     *
     * @since 2.5.0
     * @access public
     *
     * @return string Widget name.
     */
    public function getName()
    {
        return 'language-selector';
    }

    /**
     * Get widget title.
     *
     * @since 2.5.0
     * @access public
     *
     * @return string Widget title.
     */
    public function getTitle()
    {
        return __('Language Selector');
    }

    /**
     * Get widget icon.
     *
     * @since 2.5.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function getIcon()
    {
        return 'eicon-global-settings';
    }

    /**
     * Get widget categories.
     *
     * @since 2.5.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function getCategories()
    {
        return ['theme-elements'];
    }

    /**
     * Get widget keywords.
     *
     * @since 2.5.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function getKeywords()
    {
        return ['language', 'selector', 'chooser'];
    }

    /**
     * Register language selector widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 2.5.0
     * @access protected
     */
    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_selector',
            [
                'label' => $this->getTitle(),
            ]
        );

        $this->addControl(
            'skin',
            [
                'label' => __('Skin'),
                'type' => ControlsManager::SELECT,
                'default' => 'dropdown',
                'options' => [
                    'classic' => __('Classic'),
                    'dropdown' => __('Dropdown'),
                ],
                'separator' => 'after',
            ]
        );

        $this->addControl(
            'content',
            [
                'label' => __('Content'),
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'default' => ['name'],
                'options' => [
                    'flag' => __('Country Flag'),
                    'code' => __('ISO Code'),
                    'name' => __('Language'),
                ],
                'multiple' => true,
            ]
        );

        $this->addControl(
            'align_items',
            [
                'label' => __('Align'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'prefix_class' => 'elementor-nav--align-',
            ]
        );

        $this->addControl(
            'show_submenu_on',
            [
                'label' => __('Show Submenu'),
                'type' => ControlsManager::SELECT,
                'label_block' => false,
                'default' => 'hover',
                'options' => [
                    'hover' => __('On Hover'),
                    'click' => __('On Click'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->addControl(
            'align_submenu',
            [
                'label' => __('Submenu Align'),
                'type' => ControlsManager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __('Right'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'frontend_available' => true,
            ]
        );

        $this->endControlsSection();

        // Start style selector

        $this->startControlsSection(
            'section_style_nav',
            [
                'label' => $this->getTitle(),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => isset($args['condition']) ? $args['condition'] : [],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'menu_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .elementor-nav--main',
            ]
        );

        $this->startControlsTabs('tabs_menu_item_style');

        $this->startControlsTab(
            'tab_menu_item_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'color_menu_item',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e)' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->addControl(
            'bgcolor_menu_item',
            [
                'label' => __('Background color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.elementor-item-active:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.highlighted:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):hover, ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_menu_item_hover',
            [
                'label' => __('Hover & Active'),
            ]
        );

        $this->addControl(
            'color_menu_item_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.elementor-item-active:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.highlighted:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):hover, ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):focus' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->addControl(
            'background_menu_item_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.elementor-item-active:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.highlighted:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):hover, ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->addControl(
            'menu_item_border_color_hover',
            array(
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'condition' => array(
                    'border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.elementor-item-active:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item.highlighted:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):hover, ' .
                    '{{WRAPPER}} .elementor-nav--main a.elementor-item:not(#e):focus' => 'border-color: {{VALUE}}',
                ),
            )
        );

        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'border',
                'label' => __('Border'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .blockcart > a',
            )
        );
        $this->endControlsSection();

        // Start style dropdown

        $this->startControlsSection(
            'section_style_dropdown',
            [
                'label' => __('Dropdown'),
                'tab' => ControlsManager::TAB_STYLE,
                'condition' => [
                    'skin' => 'dropdown',
                ]
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'dropdown_typography',
                'scheme' => SchemeTypography::TYPOGRAPHY_4,
                'exclude' => ['line_height'],
                'selector' => '{{WRAPPER}} .elementor-nav--dropdown',
            ]
        );

        $this->startControlsTabs('tabs_dropdown_item_style');

        $this->startControlsTab(
            'tab_dropdown_item_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'color_dropdown_item',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a:not(#e), {{WRAPPER}} .elementor-menu-toggle' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'background_color_dropdown_item',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_dropdown_item_hover',
            [
                'label' => __('Hover & Active'),
            ]
        );

        $this->addControl(
            'color_dropdown_item_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.highlighted:not(#e), ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a:not(#e):hover, ' .
                    '{{WRAPPER}} .elementor-menu-toggle:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active:not(#e)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'background_color_dropdown_item_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a:hover, ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active, ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.highlighted' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active' => 'background-color: {{VALUE}}',
                ],
                'separator' => 'none',
            ]
        );
        $this->addControl(
            'dropdown_item_border_color_hover',
            array(
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'condition' => array(
                    'border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-nav--dropdown a:hover, ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active, ' .
                    '{{WRAPPER}} .elementor-nav--dropdown a.highlighted' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .elementor-nav--dropdown a.elementor-item-active' => 'border-color: {{VALUE}}',
                ),
            )
        );
        $this->endControlsTab();

        $this->endControlsTabs();

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'dropdown_border',
                'selector' => '{{WRAPPER}} .elementor-nav--dropdown',
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'dropdown_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-nav--dropdown li:first-child a' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-nav--dropdown li:last-child a' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'dropdown_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .elementor-nav--main .elementor-nav--dropdown, {{WRAPPER}} .elementor-nav__container.elementor-nav--dropdown',
            ]
        );

        $this->addResponsiveControl(
            'padding_horizontal_dropdown_item',
            [
                'label' => __('Horizontal Padding'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
                ],
                'separator' => 'before',

            ]
        );

        $this->addResponsiveControl(
            'padding_vertical_dropdown_item',
            [
                'label' => __('Vertical Padding'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown a' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'heading_dropdown_divider',
            [
                'label' => __('Divider'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->addGroupControl(
            GroupControlBorder::getType(),
            [
                'name' => 'dropdown_divider',
                'selector' => '{{WRAPPER}} .elementor-nav--dropdown li:not(:last-child)',
                'exclude' => ['width'],
            ]
        );

        $this->addControl(
            'dropdown_divider_width',
            [
                'label' => __('Border Width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--dropdown li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'dropdown_divider_border!' => '',
                ],
            ]
        );

        $this->addResponsiveControl(
            'dropdown_top_distance',
            [
                'label' => __('Distance'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-nav--main > .elementor-nav > li > .elementor-nav--dropdown, ' .
                    '{{WRAPPER}} .elementor-nav__container.elementor-nav--dropdown' => 'margin-top: {{SIZE}}{{UNIT}} !important',
                ],
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();
    }

    /**
     * Render language selector widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 2.5.0
     * @access protected
     * @codingStandardsIgnoreStart Generic.Files.LineLength
     */
    protected function render()
    {
        $settings = $this->getActiveSettings();
        $languages = \Language::getLanguages(true, $this->context->shop->id);

        if (count($languages) <= 1 || !$settings['content']) {
            return;
        }
        $this->lang_flag = in_array('flag', $settings['content']);
        $this->lang_code = in_array('code', $settings['content']);
        $this->lang_name = in_array('name', $settings['content']);

        $id_lang = $this->context->language->id;
        $menu = [
            '0' => [
                'id_lang' => $id_lang,
                'name' => $this->context->language->name,
                'iso_code' => $this->context->language->iso_code,
                'url' => 'javascript:;',
                'current' => false,
                'children' => [],
            ]
        ];
        foreach ($languages as &$lang) {
            $lang['current'] = $id_lang == $lang['id_lang'];
            $lang['url'] = $this->context->link->getLanguageLink($lang['id_lang']);

            $menu[0]['children'][] = $lang;
        }
        if ('classic' === $settings['skin']) {
            $menu = &$menu[0]['children'];
        }
        $ul_class = 'elementor-nav';

        // General Menu.
        ob_start();
        $this->selectorList($menu, 0, $ul_class);
        $menu_html = ob_get_clean();

        $this->addRenderAttribute('main-menu', 'class', [
            'elementor-langs',
            'elementor-nav--main',
            'elementor-nav__container',
        ]);
        ?>
        <nav <?= $this->getRenderAttributeString('main-menu') ?>><?= $menu_html ?></nav>
        <?php
    }

    protected function selectorList(array &$nodes, $depth = 0, $ul_class = '')
    {
        ?>
        <ul <?= $depth ? 'class="sub-menu elementor-nav--dropdown"' : 'id="selector-' . $this->getId() . '" class="' . $ul_class . '"' ?>>
        <?php foreach ($nodes as &$node) : ?>
            <li class="<?= sprintf(self::$li_class, 'lang', "lang-{$node['id_lang']}", $node['current'] ? ' current-menu-item' : '', !empty($node['children']) ? ' menu-item-has-children' : '') ?>">
                <a class="<?= ($depth ? 'elementor-sub-item' : 'elementor-item') . ($node['current'] ? ' elementor-item-active' : '') ?>" href="<?= esc_attr($node['url']) ?>">
                <?php if ($this->lang_flag) : ?>
                    <img class="elementor-langs__flag" src="<?= esc_attr(Helper::getMediaLink("img/l/{$node['id_lang']}.jpg")) ?>" alt="<?= $node['iso_code'] ?>" width="16" height="11">
                <?php endif ?>
                <?php if ($this->lang_code) : ?>
                    <span class="elementor-langs__code"><?= $node['iso_code'] ?></span>
                <?php endif ?>
                <?php if ($this->lang_name) : ?>
                    <span class="elementor-langs__name"><?= explode(' (', $node['name'])[0] ?></span>
                <?php endif ?>
                <span class="sub-arrow"><i class=""></i></span>
                </a>
                <?php empty($node['children']) or $this->selectorList($node['children'], $depth + 1) ?>
            </li>
        <?php endforeach ?>
        </ul>
        <?php
    }

    public function __construct($data = [], $args = [])
    {
        $this->context = \Context::getContext();

        parent::__construct($data, $args);
    }
}
