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

class WidgetProductTab extends WidgetProductBase
{
	public function getName() {
		return 'product-tab';
	}
	public function getTitle() {
		return __('Tab Products');
	}
	public function getIcon() { 
		return 'eicon-product-tabs';
	}
	public function getKeywords()
    {
        return ['product', 'tab'];
    }
 
	protected function _registerControls() {
		$this->startControlsSection(
			'tab_products_section',
			[
				'label' => __( 'Content' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();
		$repeater->addControl(
            'tab_title',
            [
                'label' => __('Title & Description'),
                'type' => ControlsManager::TEXT,
                'default' => __('Accordion Title'),
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
            ]
        );
        $repeater->addControl(
            'listing',
            [
                'label' => __('Listing'),
                'type' => ControlsManager::SELECT,
                'default' => 'category',
                'options' => $this->getListingOptions(),
                'separator' => 'before',
            ]
        );

        $repeater->addControl(
            'products',
            [
                'type' => ControlsManager::REPEATER,
                'item_actions' => [
                    'add' => !is_admin() ? true : [
                        'product' => $this->getAjaxProductsListUrl(),
                        'placeholder' => __('Add Product'),
                    ],
                    'duplicate' => false,
                ],
                'fields' => [
                    [
                        'name' => 'id',
                        'type' => ControlsManager::HIDDEN,
                        'default' => '',
                    ],
                ],
                'title_field' =>
                    '<# var prodImg = elementor.getProductImage( id ), prodName = elementor.getProductName( id ); #>' .
                    '<# if ( prodImg ) { #><img src="{{ prodImg }}" class="ce-repeater-thumb"><# } #>' .
                    '<# if ( prodName ) { #><span title="{{ prodName }}">{{{ prodName }}}</span><# } #>',
                'condition' => [
                    'listing' => 'products',
                ],
            ]
        );

        $repeater->addControl(
            'category_id',
            [
                'label' => __('Category'),
                'label_block' => true,
                'type' => ControlsManager::SELECT2,
                'options' => $this->getCategoryOptions(),
                'default' => 2,
                'condition' => [
                    'listing' => 'category',
                ],
            ]
        );

        $repeater->addControl(
            'order_by',
            [
                'label' => __('Order By'),
                'type' => ControlsManager::SELECT,
                'default' => 'position',
                'options' => [
                    'name' => __('Name'),
                    'price' => __('Price'),
                    'position' => __('Popularity'),
                    'quantity' => __('Sales Volume'),
                    'date_add' => __('Arrival'),
                    'date_upd' => __('Update'),
                ],
                'condition' => [
                    'listing!' => 'products',
                ],
            ]
        );

        $repeater->addControl(
            'order_dir',
            [
                'label' => __('Order Direction'),
                'type' => ControlsManager::SELECT,
                'default' => 'asc',
                'options' => [
                    'asc' => __('Ascending'),
                    'desc' => __('Descending'),
                ],
                'condition' => [
                    'listing!' => 'products',
                ],
            ]
        );

        $repeater->addControl(
            'randomize',
            [
                'label' => __('Randomize'),
                'type' => ControlsManager::SWITCHER,
                'label_on' => __('Yes'),
                'label_off' => __('No'),
                'condition' => [
                    'listing' => ['category', 'products'],
                ],
            ]
        );

        $repeater->addControl(
            'limit',
            [
                'label' => __('Product Limit'),
                'type' => ControlsManager::NUMBER,
                'min' => 1,
                'default' => 8,
                'condition' => [
                    'listing!' => 'products',
                ],
            ]
        );
        $this->addControl(
            'tabs',
            [
                'label' => __('Tab Items'),
                'type' => ControlsManager::REPEATER,
                'fields' => $repeater->getControls(),
                'default' => [
                	[
                        'tab_title' => __('Tab #1'),
                        'listing' => 'new-products',
                        'order_by' => 'position',
                        'order_dir' => 'asc',
                        'limit' => 8,
                    ],
                    [
                        'tab_title' => __('Tab #2'),
                        'listing' => 'new-products',
                        'order_by' => 'position',
                        'order_dir' => 8,
                    ],
                ],
                'title_field' => '{{{ tab_title }}}',
            ]
        );
		
		
		$this->endControlsSection();
		$this->startControlsSection(
			'layout_section',
			[
				'label' => __( 'Layout' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			
			$this->addControl(
				'enable_slider',
				[
					'label' 		=> __('Enable Slider'),
					'type' 			=> ControlsManager::SWITCHER,
					'return_value' 	=> 'yes',
					'default' 		=> 'yes', 
				]
			);
			$this->addResponsiveControl(
				'columns',
				[
					'label' => __( 'Columns' ),
					'type' => ControlsManager::SLIDER,
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
					'size_units' => ['item'],
					'range' => [
						'item' => [
							'min' => 1,
							'max' => 6,
							'step' => 1,
						],
					],
					'desktop_default' => [
						'size' => 4,
						'unit' => 'item',
					],
					'tablet_default' => [
						'size' => 3,
						'unit' => 'item',
					],
					'mobile_default' => [
						'size' => 2,
						'unit' => 'item',
					],
					'condition' 	=> [
						'enable_slider!' => 'yes',
					],
				]
			);
			$product_display = array(
				'default' => 'Default',
				'grid1' => 'Grid 1',
				'grid2' => 'Grid 2',
				'grid3' => 'Grid 3',
				'grid4' => 'Grid 4',
				'list'  => 'List',
			);
			$this->addControl(
				'product_display',
				[
					'label' => __( 'Product display' ),
					'type' => ControlsManager::SELECT,
					'options' => $product_display,
					'default' => 'default',
					'description' => __('Default: use themesettings configuration')
				]
			);
		$this->endControlsSection();
		//Slider Setting
		

		$this->startControlsSection(
			'section_tp_style',
			[
				'label' 		=> __('Title Style'),
				'tab' 			=> ControlsManager::TAB_STYLE,
			]
		);
			$this->addControl(
				'title_type',
				[
					'label' => __( 'Title type' ),
					'type' => ControlsManager::SELECT,
					'default' => 'normal',
					'options' => [
						'normal'  => __( 'Normal' ),
						'absolute' => __( 'Absolute' ),
					],
				]
			);
			$this->addResponsiveControl(
				'title_absolute',
				[
					'label' => __( 'Title absolute' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => -20,
					],
					'selectors' => [
						'{{WRAPPER}} .tab-titles' => 'top: {{SIZE}}{{UNIT}};',
					],
					'condition'    	=> [
						'title_type' => 'absolute',
					],
				]
			);
			$this->addControl(
				'title_align',
				[
					'label' => __( 'Title alignment' ),
					'type' => ControlsManager::CHOOSE,
					'options' => [
						'left' => [
							'title' => __( 'Left' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => __( 'Center' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => __( 'Right' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'left',
					'selectors' => [
						'{{WRAPPER}} .tab-titles' => 'text-align: {{VALUE}};',
					],
				]
			);
			$this->addResponsiveControl(
				'title_size',
				[
					'label' => __( 'Title size' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .tab-titles li a' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'title_padding',
				[
					'label' => __( 'Padding' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tab-titles li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);
			$this->addResponsiveControl(
				'title_space',
				[
					'label' => __( 'Title space' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 20,
					],
					'selectors' => [
						'{{WRAPPER}} .tab-titles li a' => 'margin-right: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->addResponsiveControl(
				'title_space_bottom',
				[
					'label' => __( 'Title space bottom' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 25,
					],
					'selectors' => [
						'{{WRAPPER}} .tab-titles' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->startControlsTabs('tabs_title_style');
				$this->startControlsTab(
					'title_normal',
					[
						'label' => __( 'Normal' ),
					]
				);
					$this->addControl(
						'title_color',
						[
							'label' => __( 'Text Color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tab-titles li a' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'bg_color',
						[
							'label' => __( 'Background color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tab-titles li a' => 'background-color: {{VALUE}};',
							],
						]
					);
				$this->endControlsTab();
				$this->startControlsTab(
					'title_active',
					[
						'label' => __( 'Active' ),
					]
				);
					$this->addControl(
						'title_active_color',
						[
							'label' => __( 'Color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tab-titles li.active a, {{WRAPPER}} .tab-titles li:hover a' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'bg_active_color',
						[
							'label' => __( 'Background color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tab-titles li.active a, {{WRAPPER}} .tab-titles li:hover a' => 'background-color: {{VALUE}};',
							],
						]
					);
					$this->addControl(
						'border_active_color',
						[
							'label' => __( 'Border color' ),
							'type' => ControlsManager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .tab-titles li.active a, {{WRAPPER}} .tab-titles li:hover a' => 'border-color: {{VALUE}};',
							],
						]
					);
				$this->endControlsTab();
			$this->endControlsTabs();

			$this->addGroupControl(
	            GroupControlBorder::getType(),
	            [
	                'name' => 'border',
	                'selector' => '{{WRAPPER}} .tab-titles li a',
	            ]
	        );
			$this->addControl(
				'border_radius',
				[
					'label' => __( 'Border Radius' ),
					'type' => ControlsManager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .tab-titles li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->addGroupControl(
				GroupControlBoxShadow::getType(),
				[
					'name' => 'button_box_shadow',
					'selector' => '{{WRAPPER}} .tab-titles li',
				]
			);
			
		$this->endControlsSection();
	}
	protected function render() {
		if (is_admin()){
			return print '<div class="ce-remote-render"></div>';
		}
		if (empty($this->context->currency->id)) {
            return;
        }
		$settings = $this->getSettingsForDisplay();

		// if($settings['enable_slider']){
		// 	$this->context->smarty->assign(
		// 		array(
		// 			'slick_options' => json_encode($slick_options) ,
		// 			'slick_responsive' => json_encode($slick_responsive),
		// 		)
		// 	);
		// }else{
		// 	$context->smarty->assign(
		// 		array(
		// 			'columns_desktop' => ($settings['columns']['size'] == 5) ? '2-4' : (12/$settings['columns']['size']),
		// 			'columns_tablet' => ($settings['columns_tablet']['size'] == 5) ? '2-4' : (12/$settings['columns_tablet']['size']),
		// 			'columns_mobile' => ($settings['columns_mobile']['size'] == 5) ? '2-4' : (12/$settings['columns_mobile']['size']),
		// 		)
		// 	);
		// }

		// $tab_titles = array();
		// $tab_contents = array();

		// foreach ( $settings['tab_list'] as $index => $tab ){
		// 	$tab_titles[] = array(
		// 		'id' => $tab['_id'],
		// 		'title' => $tab['tab_title']
		// 	);

		// 	if ($tab['randomize'] && ('category' === $tab['listing'] || 'products' === $tab['listing'])) {
	 //            $tab['order_by'] = 'rand';
	 //        }

	 //        $products = $this->getProducts(
	 //            $tab['listing'],
	 //            $tab['order_by'],
	 //            $tab['order_dir'],
	 //            $tab['limit'],
	 //            $tab['category_id'],
	 //            $tab['products']
	 //        );
		// 	$tab_contents[] = array(
		// 		'id' => $tab['_id'],
		// 		'products' => $products,
		// 	);
		// }

		// $this->context->smarty->assign(
		// 	array(
		// 		'tab_titles'         => $tab_titles,
		// 		'tab_contents'       => $tab_contents,
		// 		'elementprefix'       => 'single-product',
		// 		'theme_template_path' => $display,
		// 		'carousel_active' => $settings['enable_slider'],
		// 		'name_length' => $name_length ,
		// 	)
		// );
		
		// $template_file_name = _PS_MODULE_DIR_ . 'poselements/views/templates/front/producttabs.tpl';
		
		// $out_put .= $context->smarty->fetch( $template_file_name );

		// echo $out_put;
	}

}