<?php  

namespace CE;

defined('_PS_VERSION_') or die;

use Context;
use Tools;

class WidgetSlideshow extends WidgetBase { 
	public function getName() 
	{
		return 'slideshow';
	}

	public function getTitle() 
	{
		return 'Slideshow';
	}
	
	public function getIcon() 
	{
		return 'eicon-slider-album';
	}
	
	protected function _registerControls() {
		$animations = array(
			'' => $this->l('Default' ), 
			'bounceIn' => 'bounceIn',
			'bounceInDown' => 'bounceInDown',
			'bounceInLeft' => 'bounceInLeft',
			'bounceInRight' => 'bounceInRight',
			'bounceInUp' => 'bounceInUp',
			'fadeIn' => 'fadeIn',
			'fadeInDown' => 'fadeInDown',
			'fadeInLeft' => 'fadeInLeft',
			'fadeInRight' => 'fadeInRight',
			'fadeInUp' => 'fadeInUp',
			'zoomIn' => 'zoomIn',
			'zoomInDown' => 'zoomInDown',
			'zoomInLeft' => 'zoomInLeft',
			'zoomInRight' => 'zoomInRight',
			'zoomInUp' => 'zoomInUp',
			'rotateIn' => 'rotateIn',
			'rotateInDownLeft' => 'rotateInDownLeft',
			'rotateInDownRight' => 'rotateInDownRight',
			'rotateInUpLeft' => 'rotateInUpLeft',
			'rotateInUpRight' => 'rotateInUpRight',
			'pulse' => 'pulse',
			'flipInX' => 'flipInX',
			'jackInTheBox' => 'jackInTheBox',
			'rollIn' => 'rollIn',
		);
		//Tab Content
		$this->startControlsSection(
			'content_section',
			[
				'label' => $this->l( 'Content'),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$this->addResponsiveControl( 
				'height_slideshow',
				[
					'label' => $this->l( 'Height slideshow' ),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 2000,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 500,
					],
					'selectors' => [
						'{{WRAPPER}} .pos-slideshow .slider-item' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$repeater = new Repeater();
			$repeater->startControlsTabs( 'slideshow_content' );
			$repeater->startControlsTab( 'content',
				[
					'label' => __( 'Content'),
				]
			);
				$repeater->addControl(
					'slideshow_image', 
					[
						'label' => $this->l('Add Image'),
	                    'type' => ControlsManager::MEDIA,
	                    'seo' => 'true',
	                    'default' => [
	                        'url' => Utils::getPlaceholderImageSrc(),
	                    ],
					]
				);
				$repeater->addResponsiveControl(
					'x', 
					[
	                    'label' => __('X Position', 'Background Control'),
	                    'type' => ControlsManager::SLIDER, 
	                    'range' => [
	                        '%' => [
	                            'min' => 0,
	                            'max' => 100,
	                        ],
	                    ],
	                    'selectors' => [
	                        '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .desc-banner' => 'left: {{SIZE}}{{UNIT}};',
	                    ],
					]
				);
				$repeater->addResponsiveControl(
					'y', 
					[
	                    'label' => __('Y Position', 'Background Control'),
	                    'type' => ControlsManager::SLIDER,                        
	                    'range' => [
	                        '%' => [
	                            'min' => 0,
	                            'max' => 100,
	                        ],
	                    ],
	                    'selectors' => [ 
	                        '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .desc-banner' => 'top: {{SIZE}}{{UNIT}}',
	                    ],
					]
				);
				$repeater->addControl(
					'title1', 
					[
	                    'label' => __('Title 1'),
	                    'type' => ControlsManager::TEXT,	                    
	                    'label_block' => true,
					]
				);
				$repeater->addControl(
					'title2', 
					[
	                    'label' => __('Title 2'),
	                    'type' => ControlsManager::TEXT,
	                    'label_block' => true,
					]
				);
				$repeater->addControl(
					'title3', 
					[
	                    'label' => __('Title 3'),
	                    'type' => ControlsManager::TEXT,	                    
	                    'label_block' => true,
					]
				);
				$repeater->addControl(
					'subtitle', 
					[
	                    'label' => __('Subtitle'),
	                    'type' => ControlsManager::TEXT,                    
	                    'label_block' => true,
					]
				);
				$repeater->addControl(
					'button', 
					[
	                    'label' => __('Button text'),
	                    'type' => ControlsManager::TEXT,	                    
	                    'label_block' => true,
					]
				);
				$repeater->addControl(
					'link', 
					[
	                    'label' => $this->l('Link'),
	                    'type' => ControlsManager::URL,
	                    'placeholder' => $this->l('https://your-link.com'),
					]
				);
			$repeater->endControlsTab();
			$repeater->startControlsTab( 'style',
				[
					'label' => __( 'Style'),
				]
			);
				$repeater->addResponsiveControl(
					'alignment', 
					[
						'label' => $this->l('Alignment', 'elementor'),
		                'type' => ControlsManager::CHOOSE,
		                'options' => array(
		                    'left' => array(
		                        'title' => $this->l('Left', 'elementor'),
		                        'icon' => 'fa fa-align-left',
		                    ),
		                    'center' => array(
		                        'title' => $this->l('Center', 'elementor'),
		                        'icon' => 'fa fa-align-center',
		                    ),
		                    'right' => array(
		                        'title' => $this->l('Right', 'elementor'),
		                        'icon' => 'fa fa-align-right',
		                    ),
		                ),
		                'selectors' => array(
		                    '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .desc-banner' => 'text-align: {{VALUE}};',
		                ),
		                'separator' => 'after'
					]
				);
				//Start title 1
				$repeater->addControl(
					'heading1', 
					[
	                	'label' => $this->l('Title 1'),
	                	'type' => ControlsManager::HEADING,
	                	'separator' => 'before'
					]
				);
				$repeater->addControl(
					'title1_color', 
					[
		            	'label' => $this->l('Color'),
		                'type' => ControlsManager::COLOR,
		                'selectors' => [
		                    '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .title1' => 'color: {{VALUE}};',
		                ],
					]
				);
				$repeater->addGroupControl(
					GroupControlTypography::getType(),
					[
						'name' => 'title1_typography',
						'selector' => '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .title1',
						'separator' => 'none',
					]
				);

	        	$repeater->addResponsiveControl(
	            	'title1_space',
	    			[
	                    'label' => $this->l('Space'),
	                    'type' => ControlsManager::SLIDER,
	                    'default' => [
	                        'size' => 10,
	                        'unit' => 'px',
	                    ],
	                    'range' => [
	                        '%' => [
	                            'min' => 0,
	                            'max' => 100,
	                        ],
	                    ],
	                    'selectors' => [
	                        '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .title1' => 'margin-bottom: {{SIZE}}{{UNIT}}',
	                    ],
	                    'separator' => 'none',
	                ]
	        	);
				$repeater->addControl(
					'title1_animation', 
					[
	                	'label' => $this->l('Animation'),
		                'type' => ControlsManager::ANIMATION,
		                'separator' => 'after'
					]
				);
				//Start title 2
				$repeater->addControl(
					'heading2', 
					[
	                	'label' => $this->l('Title 2'),
	                	'type' => ControlsManager::HEADING,
	                	'separator' => 'before'
					]
				);
				$repeater->addControl(
					'title2_color', 
					[
		            	'label' => $this->l('Color'),
		                'type' => ControlsManager::COLOR,
		                'selectors' => [
		                    '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .title2' => 'color: {{VALUE}};',
		                ],
					]
				);
				$repeater->addGroupControl(
					GroupControlTypography::getType(),
					[
						'name' => 'title2_typography',
						'selector' => '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .title2',
						'separator' => 'none',
					]
				);

	        	$repeater->addResponsiveControl(
	            	'title2_space',
	    			[
	                    'label' => $this->l('Space'),
	                    'type' => ControlsManager::SLIDER,
	                    'default' => [
	                        'size' => 10,
	                        'unit' => 'px',
	                    ],
	                    'range' => [
	                        '%' => [
	                            'min' => 0,
	                            'max' => 100,
	                        ],
	                    ],
	                    'selectors' => [
	                        '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .title2' => 'margin-bottom: {{SIZE}}{{UNIT}}',
	                    ],
	                    'separator' => 'none',
	                ]
	        	);
				$repeater->addControl(
					'title2_animation', 
					[
	                	'label' => $this->l('Animation'),
		                'type' => ControlsManager::ANIMATION,
		                'separator' => 'after'
					]
				);
				//Start title 3
				$repeater->addControl(
					'heading3', 
					[
	                	'label' => $this->l('Title 3'),
	                	'type' => ControlsManager::HEADING,
	                	'separator' => 'before'
					]
				);
				$repeater->addControl(
					'title3_color', 
					[
		            	'label' => $this->l('Color'),
		                'type' => ControlsManager::COLOR,
		                'selectors' => [
		                    '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .title3' => 'color: {{VALUE}};',
		                ],
					]
				);
				$repeater->addGroupControl(
					GroupControlTypography::getType(),
					[
						'name' => 'title3_typography',
						'selector' => '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .title3',
						'separator' => 'none',
					]
				);

	        	$repeater->addResponsiveControl(
	            	'title3_space',
	    			[
	                    'label' => $this->l('Space'),
	                    'type' => ControlsManager::SLIDER,
	                    'default' => [
	                        'size' => 10,
	                        'unit' => 'px',
	                    ],
	                    'range' => [
	                        '%' => [
	                            'min' => 0,
	                            'max' => 100,
	                        ],
	                    ],
	                    'selectors' => [
	                        '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .title3' => 'margin-bottom: {{SIZE}}{{UNIT}}',
	                    ],
	                    'separator' => 'none',
	                ]
	        	);
				$repeater->addControl(
					'title3_animation', 
					[
	                	'label' => $this->l('Animation'),
		                'type' => ControlsManager::ANIMATION,
		                'separator' => 'after'
					]
				);
				//Start subtitle
				$repeater->addControl(
					'heading4', 
					[
	                	'label' => $this->l('Subtitle'),
	                	'type' => ControlsManager::HEADING,
	                	'separator' => 'before'
					]
				);
				$repeater->addControl(
					'subtitle_color', 
					[
		            	'label' => $this->l('Color'),
		                'type' => ControlsManager::COLOR,
		                'selectors' => [
		                    '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .subtitle' => 'color: {{VALUE}};',
		                ],
					]
				);
				$repeater->addGroupControl(
					GroupControlTypography::getType(),
					[
						'name' => 'subtitle_typography',
						'selector' => '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .subtitle',
						'separator' => 'none',
					]
				);

	        	$repeater->addResponsiveControl(
	            	'subtitle_space',
	    			[
	                    'label' => $this->l('Space'),
	                    'type' => ControlsManager::SLIDER,
	                    'default' => [
	                        'size' => 10,
	                        'unit' => 'px',
	                    ],
	                    'range' => [
	                        '%' => [
	                            'min' => 0,
	                            'max' => 100,
	                        ],
	                    ],
	                    'selectors' => [
	                        '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
	                    ],
	                    'separator' => 'none',
	                ]
	        	);
				$repeater->addControl(
					'subtitle_animation', 
					[
	                	'label' => $this->l('Animation'),
		                'type' => ControlsManager::ANIMATION,
		                'separator' => 'after'
					]
				);
				//Start button
				$repeater->addControl(
					'heading5', 
					[
	                	'label' => $this->l('Button'),
	                	'type' => ControlsManager::HEADING,
	                	'separator' => 'before'
					]
				);
				$repeater->addGroupControl(
					GroupControlTypography::getType(),
					[
						'name' => 'button_typography',
						'selector' => '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} .button',
						'separator' => 'none',
					]
				);
				$repeater->addControl(
					'button_color', 
					[
		            	'label' => $this->l('Color'),
		                'type' => ControlsManager::COLOR,
		                'scheme' => [
		                    'type' => SchemeColor::getType(),
		                    'value' => SchemeColor::COLOR_1,
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}}  a.slideshow-button' => 'color: {{VALUE}};',
		                ],
					]
				);
				$repeater->addControl(
		            'background_color',
		            array(
		                'label' => __('Background Color', 'elementor'),
		                'type' => ControlsManager::COLOR,
		                'scheme' => array(
		                    'type' => SchemeColor::getType(),
		                    'value' => SchemeColor::COLOR_4,
		                ),
		                'selectors' => array(
		                    '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} a.slideshow-button' => 'background-color: {{VALUE}};',
		                ),
		            )
		        );
		        $repeater->addControl(
					'button_colorh', 
					[
		            	'label' => $this->l('Hover Color'),
		                'type' => ControlsManager::COLOR,
		                'scheme' => [
		                    'type' => SchemeColor::getType(),
		                    'value' => SchemeColor::COLOR_1,
		                ],
		                'selectors' => [
		                    '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} a.slideshow-button:hover' => 'color: {{VALUE}};',
		                ],
					]
				);
				$repeater->addControl(
		            'background_colorh',
		            array(
		                'label' => __('Hover Background Color'),
		                'type' => ControlsManager::COLOR,
		                'scheme' => array(
		                    'type' => SchemeColor::getType(),
		                    'value' => SchemeColor::COLOR_4,
		                ),
		                'selectors' => array(
		                    '{{WRAPPER}} .pos-slideshow-wrapper {{CURRENT_ITEM}} a.slideshow-button:hover' => 'background-color: {{VALUE}};',
		                ),
		            )
		        );
		        $repeater->addGroupControl(
		            GroupControlBorder::getType(),
		            array(
		                'name' => 'border',
		                'label' => __('Border'),
		                'placeholder' => '1px',
		                'default' => '1px',
		                'selector' => '{{WRAPPER}} a.slideshow-button',
		            )
		        );

		        $repeater->addControl(
		            'border_radius',
		            array(
		                'label' => __('Border Radius'),
		                'type' => ControlsManager::DIMENSIONS,
		                'size_units' => array('px', '%'),
		                'selectors' => array(
		                    '{{WRAPPER}} a.slideshow-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		                ),
		            )
		        );

		        $repeater->addGroupControl(
		            GroupControlBoxShadow::getType(),
		            array(
		                'name' => 'button_box_shadow',
		                'selector' => '{{WRAPPER}} a.slideshow-button',
		            )
		        );

		        $repeater->addResponsiveControl(
		            'text_padding',
		            array(
		                'label' => __('Text Padding'),
		                'type' => ControlsManager::DIMENSIONS,
		                'size_units' => array('px', 'em', '%'),
		                'selectors' => array(
		                    '{{WRAPPER}} a.slideshow-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		                ),
		                'separator' => 'before',
		            )
		        );
				$repeater->addControl(
					'button_animation', 
					[
	                	'label' => $this->l('Animation'),
		                'type' => ControlsManager::ANIMATION,
					]
				);
			$repeater->endControlsTab();
			
			$this->addControl(
	            'slideshow_list',
	            [
	                'label' => 'Slideshow items',
	                'type' => ControlsManager::REPEATER,
	                'fields' => $repeater->getControls(),
	                'default' => [],
	            ]
	        );
            
        $this->endControlsSection();

		//Tab Setting
		$this->startControlsSection(
			'setting_section',
			[
				'label' => $this->l( 'Slider'),
				'tab' => ControlsManager::TAB_SETTINGS,
			]
		);
			$this->addControl(
				'arrows',
				[
					'label' 		=> $this->l('Arrows', [], 'Admin.Global'),
					'type' 			=> ControlsManager::SWITCHER,
					'default' 		=> 'yes',
					'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
				]
			);

			$this->addControl(
				'dots',
				[
					'label' 		=> $this->l('Dots', [], 'Admin.Global'),
					'type' 			=> ControlsManager::SWITCHER,
					'default' 		=> 'no',
					'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
				]
			);
			$this->addControl(
				'autoplay',
				[
					'label' => $this->l( 'Autoplay'),
					'type' 			=> ControlsManager::SWITCHER,
					'default' => 'false',  
					'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
				]
			);
			$this->addControl(
				'autoplay_speed',
				[
					'label'     	=> $this->l('AutoPlay Transition Speed (ms)', [], 'Admin.Global'),
					'type'      	=> ControlsManager::NUMBER,
					'default'  	 	=> 3000,
				]
			);
			$this->addControl(
				'pause_on_hover',
				[
					'label' 		=> $this->l('Pause on Hover', [], 'Admin.Global'),
					'type' 			=> ControlsManager::SWITCHER,
					'default' 		=> 'yes',
					'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
				]
			);

			$this->addControl(
				'infinite',
				[
					'label'        	=> $this->l('Infinite Loop', [], 'Admin.Global'),
					'type'         	=> ControlsManager::SWITCHER,
					'default'      	=> 'no',
					'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
				]
			);
			$this->addControl(
				'transition_speed',
				[
					'label'     	=> $this->l('Transition Speed (ms)', [], 'Admin.Global'),
					'type'      	=> ControlsManager::NUMBER,
					'default'  	 	=> 500,
				]
			);
		
		$this->endControlsSection();
	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	 
	protected function render() {

		$settings = $this->getSettings(); 

		// Data settings
        $slick_options = [
			'slidesToShow'   => 1,
			'slidesToScroll' => 1,
			'autoplay'       => ($settings['autoplay'] == 'yes') ? true : false,
			'autoplaySpeed'  => (int)$settings['autoplay_speed'] ? (int)$settings['autoplay_speed'] : 5000,
			'infinite'       => ($settings['infinite'] == 'yes') ? true : false,
			'pauseOnHover'   => ($settings['pause_on_hover'] == 'yes') ? true : false,
			'speed'          => (int)$settings['transition_speed'] ? (int)$settings['transition_speed'] : 500,
			'arrows'         => ($settings['arrows'] == 'yes') ? true : false,
			'dots'           => ($settings['dots'] == 'yes') ? true : false, 
			'fade'			 => true,
		]; 
		
		$slick_responsive = [
			'items_laptop'            => 1,
            'items_landscape_tablet'  => 1,
            'items_portrait_tablet'   => 1,
            'items_landscape_mobile'  => 1,
            'items_portrait_mobile'   => 1,
            'items_small_mobile'      => 1,
		];
	 
		
		$this->addRenderAttribute(
			'slideshow', 
			[
				'class' => ['pos-slideshow', 'slick-slider-block', 'column-desktop-1', 'column-tablet-1', 'column-mobile-1'],
				'data-slider_responsive' => json_encode($slick_responsive),
				'data-slider_options' => json_encode($slick_options),
			]
			
		);

		if ( $settings['slideshow_list'] ) { ?>
			<div class="pos-slideshow-wrapper">
				<div <?php echo $this->getRenderAttributeString('slideshow'); ?>>
				<?php foreach (  $settings['slideshow_list'] as $item ) :
					$image = Tools::safeOutput(Helper::getMediaLink($item['slideshow_image']['url']));

					$this->addRenderAttribute('class-item', 'class', ['slideshow-item','elementor-repeater-item-' . $item['_id']]); ?>
					<div <?php echo $this->getRenderAttributeString('class-item'); ?>>

						<div class="slider-item" style="background:url(<?= $image ?>);background-size: cover; background-position: center;">
							<div class="desc-banner">
								<div class="container">				
									<div class="slideshow-content">
										<?php if(isset($item['title1']) && $item['title1'] != '') : ?>
											<div class="title1" data-animation="animated <?= $item['title1_animation'] ?>">
												<?= $item['title1'] ?>
											</div>
										<?php endif; ?>
										<?php if(isset($item['title2']) && $item['title2'] != '') : ?>
											<div class="title2" data-animation="animated <?= $item['title2_animation'] ?>">
												<?= $item['title2'] ?>
											</div>
										<?php endif; ?>
										<?php if(isset($item['title2']) && $item['title3'] != '') : ?>
											<div class="title3" data-animation="animated <?= $item['title3_animation'] ?>">
												<?= $item['title3'] ?>
											</div>
										<?php endif; ?>
										<?php if(isset($item['subtitle']) && $item['subtitle'] != '') : ?>
											<div class="subtitle" data-animation="animated <?= $item['subtitle_animation'] ?>">
												<?= $item['subtitle'] ?>
											</div>
										<?php endif; ?>
										<?php if(isset($item['link']['url']) && $item['link']['url'] != '' && $item['button'] != '') : ?>
											<a class="slideshow-button" href="<?= $item['link']['url'] ?>" data-animation="animated <?= $item['button_animation'] ?>">
												<?= $item['button'] ?>
											</a>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			</div>
			<?php 
		}  
		

	} 


	protected function _contentTemplate() {
		
	}
	/**
     * Get translation for a given widget text
     *
     * @access protected
     *
     * @param string $string    String to translate
     *
     * @return string Translation
     */
    protected function l($string)
    {
        return translate($string, 'poselements', basename(__FILE__, '.php'));
    }
}