<?php
/**
 * V-Elements - Live page builder
 *
 * @author    ThemeVec
 * @copyright 2020-2022 themevec.com
 */

namespace VEC;

defined('_PS_VERSION_') or die;

class WidgetWishlist extends WidgetBase
{ 

	public function getName() {
		return 'wishlist';
	}
	public function getTitle() {
		return __( 'Wishlist' );
	}

	public function getIcon() {
		return 'eicon-heart';
	}

	public function getCategories() {
		return ['theme-elements'];
	}

	public function getKeywords()
    {
        return ['header', 'wishlist'];
    }

	protected function _registerControls() {
		$this->startControlsSection(
			'content_section',
			[
				'label' => __( 'Content' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addControl(
				'button_layout',
				[
					'label' => __( 'Button Layout'),
					'type' => ControlsManager::SELECT,
					'default' => 'icon_text',
					'options' => [
						'icon' => __( 'Icon'),
						'icon_text' => __( 'Icon & Text'),
						'text' => __( 'Text'),
					],
					'prefix_class' => 'button-layout-',
				]
			);
			$this->addControl(
				'wishlist_icon',
				[
					'label' => __( 'Wishlist icon'),
					'type' => ControlsManager::SELECT,
					'default' => 'icon-rt-heart2',
					'options' => [
						'icon-rt-heart2' => __( 'Icon 1'),
						'icon-rt-heart-solid' => __( 'Icon 2'),
						'icon-rt-heart-outline' => __( 'Icon 3'),
						'icon-rt-ios-heart-outline' => __( 'Icon 4'),
						'icon-rt-Heart' => __( 'Icon 5')
					],
					'condition' => array(
	                    'button_layout!' => 'text',
	                ),
				]
				
			);
		$this->endControlsSection();
		// Start for style
		$this->startControlsSection(
			'style_section',
			[
				'label' => __( 'Style' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
			$this->addControl(
            	'icon_size',
	            [
	                'label' => __('Icon size'),
	                'type' => ControlsManager::SLIDER,
	                'default' => [
	                    'size' => 14,
	                ],
	                'selectors' => [
	                    '{{WRAPPER}} .wishlist-top i' => 'font-size: {{SIZE}}{{UNIT}}',
	                ],
	                'condition' => [
	                    'button_layout!' => 'text' 
	                ],
	            ]
	        );
			$this->addGroupControl(
				GroupControlTypography::getType(),
				[
					'name' 			=> 'text_typo',
					'selector' 		=> '{{WRAPPER}} .wishlist-top a',
				]
			);
	        $this->startControlsTabs('tabs_button_style');

	        $this->startControlsTab(
	            'tab_button_normal',
	            array(
	                'label' => __('Normal'),
	            )
	        );

	        $this->addControl(
	            'button_text_color',
	            array(
	                'label' => __('Text Color'),
	                'type' => ControlsManager::COLOR,
	                'default' => '',
	                'selectors' => array(
	                    '{{WRAPPER}} .wishlist-top a' => 'color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'background_color',
	            array(
	                'label' => __('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .wishlist-top a' => 'background-color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->endControlsTab();

	        $this->startControlsTab(
	            'tab_button_hover',
	            array(
	                'label' => __('Hover'),
	            )
	        );

	        $this->addControl(
	            'hover_color',
	            array(
	                'label' => __('Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .wishlist-top a:hover' => 'color: {{VALUE}};',
	                ),
	                'scheme' => array(
	                    'type' => SchemeColor::getType(),
	                    'value' => SchemeColor::COLOR_1,
	                ),
	            )
	        );

	        $this->addControl(
	            'button_background_hover_color',
	            array(
	                'label' => __('Background Color'),
	                'type' => ControlsManager::COLOR,
	                'selectors' => array(
	                    '{{WRAPPER}} .wishlist-top a:hover' => 'background-color: {{VALUE}};',
	                ),
	            )
	        );

	        $this->addControl(
	            'button_hover_border_color',
	            array(
	                'label' => __('Border Color'),
	                'type' => ControlsManager::COLOR,
	                'condition' => array(
	                    'border_border!' => '',
	                ),
	                'selectors' => array(
	                    '{{WRAPPER}} .wishlist-top a:hover' => 'border-color: {{VALUE}};',
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
	                'selector' => '{{WRAPPER}} .wishlist-top a',
	            )
	        );

	        $this->addControl(
	            'border_radius',
	            array(
	                'label' => __('Border Radius'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .wishlist-top a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	                'separator' => 'none'
	            )
	        );
			$this->addControl(
	            'padding',
	            array(
	                'label' => __('Padding'),
	                'type' => ControlsManager::DIMENSIONS,
	                'size_units' => array('px', 'em', '%'),
	                'selectors' => array(
	                    '{{WRAPPER}} .wishlist-top a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	                ),
	            )
	        );
	        $this->addGroupControl(
	            GroupControlBoxShadow::getType(),
	            array(
	                'name' => 'button_box_shadow',
	                'selector' => '{{WRAPPER}} .wishlist-top a',
	            )
	        );
	    $this->endControlsSection();
		$this->startControlsSection(
			'count_section',
			[
				'label' => __( 'Count' ),
				'tab' => ControlsManager::TAB_STYLE,
				'condition' => [
					'button_layout' => 'icon',
				],
			]
		);    
	        $this->addControl(
	            'heading_cart_count',
	            [
	                'label' => __('Count style'),
	                'type' => ControlsManager::HEADING,
					
	            ]
	        );
	        $this->addControl(
				'count_top',
				[
					'label' => __( 'Count Position Top'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .wishlist-top .wishlist-top-count' => 'top: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_right',
				[
					'label' => __( 'Count Position Right'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => -100,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .wishlist-top .wishlist-top-count' => 'right: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_size',
				[
					'label' => __( 'Count Size'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .wishlist-top .wishlist-top-count' => 'min-width: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_font_size',
				[
					'label' => __( 'Count Font Size'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 1,
							'max' => 200,
						]
					],
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .wishlist-top .wishlist-top-count' => 'font-size: {{SIZE}}{{UNIT}}'
					],
					'separator' => 'none'
				]
			);	
		
			$this->addControl(
				'count_text_color',
				[
					'label' => __( 'Count Text Color'),
					'type' => ControlsManager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .wishlist-top .wishlist-top-count' => 'fill: {{VALUE}}; color: {{VALUE}};',
					],
					'separator' => 'none'
				]
			);

			$this->addControl(
				'count_background_color',
				[
					'label' => __( 'Count Background Color'),
					'type' => ControlsManager::COLOR,
					'selectors' => [
						'{{WRAPPER}}.button-layout-icon .wishlist-top .wishlist-top-count' => 'background-color: {{VALUE}};',
					],
					'separator' => 'none'
				]
			);
		$this->endControlsSection();

	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	protected function render() {
		if (is_admin()){
			return print '<div class="ce-remote-render"></div>';
		}

		if( \Module::isEnabled('vecwishlist') ) {
			$settings = $this->getSettings();
			$icon = $settings['wishlist_icon'];

			echo $this->fetch( 'module:vecwishlist/views/templates/hook/wishlist-top.tpl', [ 'icon' => $icon ] );
		}
	} 

	protected function fetch( $templatePath, $params ) {
		$context = \Context::getContext();
		
		$smarty = $context->smarty;
		
        if ( is_object( $context->smarty ) ) {
            $smarty = $context->smarty->createData( $context->smarty );
        }
		
		$smarty->assign( $params );
		
        $template = $context->smarty->createTemplate( $templatePath, null, null, $smarty );

        return $template->fetch();
	}

}