<?php

namespace VEC;

defined('_PS_VERSION_') or die;

use Context;
use Category;
use Configuration;
use Customer;
use DB;
use Shop;
use Validate;

class WidgetAjaxSearch extends WidgetBase { 

    public function getName() {
        return 'ajax-search';
    }
    public function getTitle() {
        return __( 'Ajax search' );
    }

    public function getIcon() {
        return 'eicon-search';
    }

    public function getCategories() {
        return [ 'theme-elements' ];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'search_content',
            [
                'label' => __('Search'),
            ]
        );

        $this->addControl(
            'search_type',
            [
                'label' => __('Search display'),
                'type' => ControlsManager::SELECT,
                'default' => 'classic',
                'options' => [
                    'classic' => __('Form - classic'),
                    'minimal' => __('Form - minimal'),
                    'dropdown' => __('Icon - dropdown'),
                    'topbar' => __('Icon - topbar'),
                ],
                'prefix_class' => '',
                'render_type' => 'template',
                'frontend_available' => true,
            ]
        );
        
        $this->addControl(
            'button_type',
            [
                'label' => __('Type'),
                'type' => ControlsManager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => __('Icon'),
                    'text' => __('Text'),
                ],
				'condition' => [
                    'search_type' => 'classic',
                ],
                'prefix_class' => 'elementor-search--button-type-',
                'render_type' => 'template',      
            ]
        );

        $this->addControl(
            'button_text',
            [
                'label' => __('Text'),
                'type' => ControlsManager::TEXT,
                'default' => __('Search'),
                'condition' => [
                    'button_type' => 'text',
                ],
            ]
        );
        $icon_options = [
            'fa fa-search' => 'search-fa',
            'ceicon-search-light' => 'search-light',
            'ceicon-search-medium' => 'search-medium',
            'ceicon-search-glint' => 'search-glint',
            'ceicon-search-minimal' => 'search-minimal',
            'ceicon-magnifier' => 'magnifier',
            'ceicon-loupe' => 'loupe',
            'fa fa-arrow-right' => 'arrow-fa',
        ];
		$this->addControl(
			'icon',
			[
				'label' => __( 'Search icon'),
				'type' => ControlsManager::ICON,
				'default' => 'fa fa-search',
				'options' => $icon_options,
                'include' => array_keys($icon_options),
				'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'search_type',
                            'operator' => '!==',
                            'value' => 'classic',
                        ],
                        [
                            'name' => 'button_type',
                            'value' => 'icon',
                        ],
                    ],
                ],
			]
		);
     
        $this->addControl(
            'search_dropdown_position',
            [
                'label' => __( 'Dropdown Position'),
                'type' => ControlsManager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Left'),
                    'right' => __( 'Right'),
                ],
                'prefix_class' => 'search-dropdown-',
                'condition' => [
                    'search_type' => 'dropdown',
                ],
            ]
        );     
        $this->addControl(
            'heading_input_content',
            [
                'label' => __('Input'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'search_type' => 'classic',
                ],
            ]
        );

        $this->addResponsiveControl(
            'width_size',
            [
                'label' => __('Input width'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1000, 
                    ],
                ],
                'default' => [
                    'size' => 200,
                    'unit' => 'px', 
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input' => 'min-width: {{SIZE}}{{UNIT}}',
                ],
                
            ]
        );
        $this->addResponsiveControl(
            'height_size',
            [
                'label' => __('Input height'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 30,
                        'max' => 200, 
                    ],
                ],
                'default' => [
                    'size' => 30, 
                    'unit' => 'px', 
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input' => 'min-height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .pos-search__submit' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->addControl(
            'placeholder',
            [
                'label' => __('Placeholder'),
                'type' => ControlsManager::TEXT,
                'default' => __('Search') . '...',
            ]
        );
		$this->addControl(
            'placeholder_color',
            [
                'label' => __('Placeholder Color'),
                'type' => ControlsManager::COLOR, 
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input::placeholder' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->endControlsSection();


        // Start for style
        $this->startControlsSection(
            'section_general',
            [
                'label' => __('General'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );
            $this->addControl(
            'search_width',
                [
                    'label' => __('Width'),
                    'type' => ControlsManager::SELECT,
                    'default' => 'inline',
                    'options' => [ 
                        'fullwidth' => __('Full width 100%'),
                        'inline' => __('Inline (auto)')
                    ],
                    'prefix_class' => 'pewidth-',
                    'render_type' => 'template',
                    'frontend_available' => true
                ]
            );
        $this->endControlsSection();
        $this->startControlsSection(
            'section_toggle_style',
            [
                'label' => __('Toggle'),
                'tab' => ControlsManager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'search_type', 
                            'operator' => '!==',
                            'value' => 'classic',
                        ],
                        [
                            'name' => 'search_type',
                            'operator' => '!==',
                            'value' => 'minimal',
                        ],
                    ],
                ],
            ]
        );
        $this->startControlsTabs('tabs_toggle_colors');
        $this->startControlsTab(
            'tab_toggle_normal',
            [
                'label' => __('Normal'),
            ]
        );
        $this->addControl(
            'toggle_color',
            [
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__toggle i' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                ],
            ]
        );
        $this->endControlsTab();

        $this->startControlsTab(
            'tab_toggle_hover',
            [
                'label' => __('Hover'),
            ]
        );
        $this->addControl(
            'toggle_color_hover',
            [
                'label' => __('Hover color'), 
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__toggle i:hover' => 'color: {{VALUE}}; border-color: {{VALUE}}',
                ],
            ]
        );
        $this->endControlsTab();
        $this->endControlsTabs();
        $this->addControl(
            'toggle_icon_size',
            [
                'label' => __('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__toggle i' => 'font-size: {{SIZE}}{{UNIT}}',  
                ],
                'default' => [
                    'size' => 24, 
                    'unit' => 'px',
                ],
                'separator' => 'before',
            ]
        );

        $this->endControlsSection();
    
        $this->startControlsSection(
            'section_input_style',
            [
                'label' => __('Input'),
                'tab' => ControlsManager::TAB_STYLE,
            ]
        );

        $this->addControl(
            'icon_size_minimal',
            [
                'label' => __('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .icon-minimal' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'search_type' => 'minimal',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} input[type="search"].pos-search__input',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            ]
        );

        $this->startControlsTabs('tabs_input_colors');

        $this->startControlsTab(
            'tab_input_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'input_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'input_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'input_border_color',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'input_box_shadow',
                'selector' => '{{WRAPPER}} .pos-search__input',
                'fields_options' => [
                    'box_shadow_type' => [
                        'separator' => 'default',
                    ],
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_input_focus',
            [
                'label' => __('Focus'),
            ]
        );

        $this->addControl(
            'input_text_color_focus',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input:focus' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'input_background_color_focus',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'input_border_color_focus',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input:focus' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'input_box_shadow_focus',
                'selector' => '{{WRAPPER}} .pos-search__input:focus',
                'fields_options' => [
                    'box_shadow_type' => [
                        'separator' => 'default',
                    ],
                ],       
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();
        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'border_input',
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .pos-search__input',
            )
        );
  
        $this->addResponsiveControl(
            'border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => [
                    '{{WRAPPER}} .pos-search__input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->endControlsSection();

        $this->startControlsSection(
            'section_button_style',
            [
                'label' => __('Button'),
                'tab' => ControlsManager::TAB_STYLE, 
				'condition' => [
					'search_type!' => 'minimal',
                ],	
            ]
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .pos-search__submit',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
                'condition' => [
                    'button_type' => 'text',
                ],
            ]
        );

        $this->startControlsTabs('tabs_button_colors');

        $this->startControlsTab(
            'tab_button_normal',
            [
                'label' => __('Normal'),
            ]
        );

        $this->addControl(
            'button_text_color',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'button_background_color',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => [
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->startControlsTab(
            'tab_button_hover',
            [
                'label' => __('Hover'),
            ]
        );

        $this->addControl(
            'button_text_color_hover',
            [
                'label' => __('Text Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->addControl(
            'button_background_color_hover',
            [
                'label' => __('Background Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();
        
        $this->addControl(
            'button_border_color_focus',
            [
                'label' => __('Border Color'),
                'type' => ControlsManager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->addGroupControl(
            GroupControlBoxShadow::getType(),
            [
                'name' => 'button_box_shadow_focus',
                'selector' => '{{WRAPPER}} .pos-search__submit',
                'fields_options' => [
                    'box_shadow_type' => [
                        'separator' => 'default',
                    ],
                ],
            ]
        );

        $this->endControlsTab();

        $this->endControlsTabs();
        $this->addGroupControl(
            GroupControlBorder::getType(),
            array(
                'name' => 'border_button',
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => '{{WRAPPER}} .pos-search__submit',
            )
        );
       
        $this->addResponsiveControl(
            'button_border_radius',
            [
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->addControl(
            'icon_size',
            [
                'label' => __('Icon Size'),
                'type' => ControlsManager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 18,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'button_type' => 'icon',
                ],
                'separator' => 'before',
            ]
        );

        $this->addResponsiveControl(
            'button_width',
            [
                'label' => __('Width'),
                'type' => ControlsManager::SLIDER, 
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .pos-search__submit' => 'min-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->endControlsSection();
    }

    protected function render()
    {
		if (is_admin()){
            return print '<div class="ce-remote-render"></div>';
        }
        if(\Module::isEnabled('vecsearchbar'))
        {
            $settings = $this->getSettings();

            $module = \Module::getInstanceByName('vecsearchbar');
            $params = array(
                'placeholder'       => $settings['placeholder'],
                'search_type'       => $settings['search_type'],
                'icon'              => $settings['icon'],
                'button_type'       => $settings['button_type'],
                'button_text'       => $settings['button_text'],

            );

            echo $module->renderWidget( 'displaySearch', $params);
        }else{
            echo 'You have to install and activate Vec-search bar module to use this widget.';
        }

    }

    protected function _contentTemplate(){}
}
