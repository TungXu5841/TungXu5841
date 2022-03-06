<?php
// start tab general
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('General'),
        'icon' => 'icon-cogs'
    ),
    'input' => array(
        array(
            'type' => 'infoheading',
            'label' => $this->l('Body background'),
            'name'=> 'body'
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Main color'),
            'name' => 'g_main_color',
        ), 
        array(
            'type' => 'text',
            'label' => $this->l('Google font URL'),
            'name' => 'g_body_gfont_url',
            'desc' => $this->l('Example: https://fonts.googleapis.com/css?family=Open+Sans:400,700 Add 400 and 700 font weigh if exist. If you need adds latin-ext or cyrilic too. Go to '). '<a href="https://www.google.com/fonts" target="_blank">'.$this->l('Google font').'</a> to get font URL',
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Google font name'),
            'name' => 'g_body_gfont_name',
            'desc' => $this->l('Example: \'Montserrat\', sans-serif'),
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Body font size'),
            'name' => 'g_body_font_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Body font color'),
            'name' => 'g_body_font_color',
        ),  
        array(
            'type' => 'infoheading',
            'label' => $this->l('Button'),
            'name' => 'heading_content'
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Button background'),
            'name' => 'button_background',
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Button text color'),
            'name' => 'button_text',
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Button border'),
            'name' => 'button_border',
            'options' => array (
                'query' =>[
                    1 => ['id' => 'none', 'name' => 'None'],
                    2 => ['id' => 'solid', 'name' => 'Solid'],
                    3 => ['id' => 'dotted', 'name' => 'Dotted'],
                    4 => ['id' => 'dashed', 'name' => 'Dashed'],
                    5 => ['id' => 'groove', 'name' => 'Groove'],
                    6 => ['id' => 'double', 'name' => 'Double'],
                ],
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Border width'),
            'name' => 'button_border_width',
            'options' => array (
                'query' =>[
                    1 => ['id' => '1', 'name' => '1px'],
                    2 => ['id' => '2', 'name' => '2px'],
                    3 => ['id' => '3', 'name' => '3px'],
                    4 => ['id' => '4', 'name' => '4px'],
                    5 => ['id' => '5', 'name' => '5px'],
                    6 => ['id' => '6', 'name' => '6px'],
                    7 => ['id' => '7', 'name' => '7px'],
                    8 => ['id' => '8', 'name' => '8px'],
                    9 => ['id' => '9', 'name' => '9px'],
                    10 => ['id' => '10', 'name' => '10px'],
                ],
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Border color'),
            'name' => 'button_border_color',
        ),
        array(
            'type' => 'hr',
            'name' => 'button_hr',
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Button background hover'),
            'name' => 'button_backgroundh',
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Button text color hover'),
            'name' => 'button_texth',
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Border color hover'),
            'name' => 'button_border_colorh',
        ),
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Header'),
        'icon' => 'icon-columns'
    ),
    'input' => array(
        array(
            'type' => 'switch',
            'label' => $this->l('Header sticky'),
            'name' => 'header_sticky',
            'class' => 'fixed-width-xs',
            'values' => array(
                array(
                    'id' => 'header_sticky_on',
                    'value' => 1,
                    'label' => $this->l('Yes')
                    ),
                array(
                    'id' => 'header_sticky_off',
                    'value' => 0,
                    'label' => $this->l('No')
                )
            ),
            'desc' => $this->l('Add "sticky-inner" class to section element to make it sticky')
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Sticky background'),
            'name' => 'sticky_background',
        ),

    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Page title'),
        'icon' => 'icon-credit-card'
    ),
    'input' => array(
        array(
            'type' => 'filemanager',
            'label' => $this->l('Background image'),
            'name' => 'ptitle_bg_image',
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Text color'),
            'name' => 'ptitle_color',
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Page title size'),
            'name' => 'ptitle_size',
            'options' => array (
                'query' =>[
                    1 => ['id' => 'small', 'name' => 'Small'],
                    2 => ['id' => 'default', 'name' => 'Default'],
                    3 => ['id' => 'big', 'name' => 'Big'],
                ],
                'id' => 'id',
                'name' => 'name'
            ),
        ),
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Product grid settings'),
        'icon' => 'icon-windows'
    ),
    'input' => array(
        array(
            'type' => 'image-select',
            'label' => $this->l('Product grid display'),
            'name' => 'grid_type',
            'default_value' => 1,
            'options' => array(
                'query' => array(
                    array(
                        'id_option' => 1,
                        'name' => $this->l('Grid 1'),
                        'img' => 'img1.png',
                        ),
                    array(
                        'id_option' => 2,
                        'name' => $this->l('Grid 2'),
                        'img' => 'img2.png',
                        ),
                    array(
                        'id_option' => 3,
                        'name' => $this->l('Grid 3'),
                        'img' => 'img3.png',
                        ),
                    array(
                        'id_option' => 4,
                        'name' => $this->l('Grid 4'),
                        'img' => 'img4.png',
                        ),
                ),
                'id' => 'id_option',
                'name' => 'name',
            ),
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Product name color'),
            'name' => 'grid_name_color',
        ), 
        array(
            'type' => 'color2',
            'label' => $this->l('Product name color hover'),
            'name' => 'grid_name_colorh',
        ), 
        array(
            'type' => 'text',
            'label' => $this->l('Product name font size'),
            'name' => 'grid_name_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'radio',
            'label' => $this->l('Product name length'),
            'name' => 'grid_name_length',
            'default_value' => 0,
            'values' => array(
                array(
                    'id' => 'p_name_length_0',
                    'value' => 0,
                    'label' => $this->l('1 line, product name is cut.'),
                    ),
                array(
                    'id' => 'p_name_length_1',
                    'value' => 1,
                    'label' => $this->l('2 lines, product name is full'),
                    ),

            ),
            'validation' => 'isUnsignedInt',
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Product name transform'),
            'name' => 'grid_name_transform',
            'options' => array (
                'query' => self::$text_transform,
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Price color'),
            'name' => 'grid_price_color',
        ),  
        array(
            'type' => 'text',
            'label' => $this->l('Price font size'),
            'name' => 'grid_price_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Category page settings'),
        'icon' => 'icon-list-alt'
    ),
    'input' => array(
        array(
            'type' => 'select',
            'label' => $this->l('Category page width'),
            'name' => 'category_width',
            'options' => array (
                'query' => array(
                	'1' => array('id' => 'inherit' , 'name' => 'Inherit'),
       				'2' => array('id' => 'custom' , 'name' => 'Custom width'),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Add custom width'),
            'name' => 'category_custom_width',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'image-select',
            'label' => $this->l('Category layout'),
            'name' => 'category_layout',
            'options' => array(
                'query' => array(
                    array(
                        'id_option' => 1,
                        'name' => $this->l('Left column'),
                        'img' => 'layout-left.png'
                    ),
                    array(
                        'id_option' => 2,
                        'name' => $this->l('Full width'),
                        'img' => 'layout-full.png'
                    ),
                    array(
                        'id_option' => 3,
                        'name' => $this->l('Right column'),
                        'img' => 'layout-right.png'
                    ),
                ),
                'id' => 'id_option',
                'name' => 'name',
            ),
        ),
        array(
            'type' => 'infoheading',
            'label' => $this->l('Category header'),
            'name' => 'heading_category_header'
        ),
        array(
            'type' => 'switch',
            'label' => $this->l('Show categorty thumbnail'),
            'name' => 'category_thumbnail',
            'class' => 'fixed-width-xs',
            'desc' => $this->l(''),
            'values' => array(
                array(
                    'id' => 'cate_thumbnail_on',
                    'value' => 1,
                    'label' => $this->l('Yes')
                    ),
                array(
                    'id' => 'cate_thumbnail_off',
                    'value' => 0,
                    'label' => $this->l('No')
                )
            )
        ),
        array(
            'type' => 'vec-switch',
            'label' => $this->l('Description'),
            'name' => 'category_description',
            'class' => 'fixed-width-xs',
            'multi' => 3,
            'values' => array(
                array(
                    'id' => 'hide',
                    'value' => 'hide',
                    'label' => $this->l('Hide'),
                ),
                array(
                    'id' => 'full',
                    'value' => 'full',
                    'label' => $this->l('Full'),
                ),
                array(
                    'id' => 'part',
                    'value' => 'part',
                    'label' => $this->l('A part'),
                ),
            ),
        ),
        array(
            'type' => 'switch',
            'label' => $this->l('Show description in bottom'),
            'name' => 'category_description_bottom',
            'class' => 'fixed-width-xs',
            'desc' => $this->l(''),
            'values' => array(
                array(
                    'id' => 'category_description_bottom_on',
                    'value' => 1,
                    'label' => $this->l('Yes')
                    ),
                array(
                    'id' => 'category_description_bottom_off',
                    'value' => 0,
                    'label' => $this->l('No')
                )
            ),
            'desc' => $this->l('Category description will be displayed in bottom of page'),
        ),
        array(
            'type' => 'switch',
            'label' => $this->l('Show subcategories'),
            'name' => 'category_sub',
            'class' => 'fixed-width-xs',
            'desc' => $this->l(''),
            'values' => array(
                array(
                    'id' => 'category_sub_on',
                    'value' => 1,
                    'label' => $this->l('Yes')
                    ),
                array(
                    'id' => 'category_sub_off',
                    'value' => 0,
                    'label' => $this->l('No')
                )
            )
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Filters'),
            'name' => 'category_filter',
            'options' => array (
                'query' => array(
                    array(
                        'id' => 'top',
                        'label' => $this->l('Top')
                    ),
                    array(
                        'id' => 'canvas',
                        'label' => $this->l('Canvas')
                    )
                ),
                'id' => 'id',
                'name' => 'label'
            ),
        ),
        array(
            'type' => 'infoheading',
            'label' => $this->l('Category product list'),
            'name' => 'heading_category_product'
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Product per page'),
            'name' => 'PS_PRODUCTS_PER_PAGE',
            'class' => 'fixed-width-xl'
        ), 
        array(
            'type' => 'select',
            'label' => $this->l('Number product per row'),
            'name' => 'category_column',
            'options' => array (
                'query' =>self::$product_row,
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'vec-switch',
            'label' => $this->l('Pagination type'),
            'name' => 'category_pagination',
            'class' => 'fixed-width-xs',
            'multi' => 3,
            'values' => array(
                array(
                    'id' => 'default',
                    'value' => 'default',
                    'label' => $this->l('Default'),
                ),
                array(
                    'id' => 'infinite',
                    'value' => 'infinite',
                    'label' => $this->l('Infinite'),
                ),
                array(
                    'id' => 'loadmore',
                    'value' => 'loadmore',
                    'label' => $this->l('Load more'),
                ),
            ),
        ), 
        
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Product page settings'),
        'icon' => 'icon-location-arrow'
    ),
    'input' => array(
    	array(
            'type' => 'infoheading',
            'label' => $this->l('Product page layout'),
            'name'=> 'ppage'
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Product page width'),
            'name' => 'product_width',
            'options' => array (
                'query' => array(
                	'1' => array('id' => 'inherit' , 'name' => 'Inherit'),
       				'2' => array('id' => 'custom' , 'name' => 'Custom width'),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Add custom width'),
            'name' => 'product_custom_width',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'image-select',
            'label' => $this->l('Product page layout'),
            'name' => 'product_layout',
            'options' => array(
                'query' => array(
                    array(
                        'id_option' => 1,
                        'name' => $this->l('Left column'),
                        'img' => 'layout-left.png'
                    ),
                    array(
                        'id_option' => 2,
                        'name' => $this->l('Full width'),
                        'img' => 'layout-full.png'
                    ),
                    array(
                        'id_option' => 3,
                        'name' => $this->l('Right column'),
                        'img' => 'layout-right.png'
                    ),
                ),
                'id' => 'id_option',
                'name' => 'name',
            ),
            'desc' => $this->l('Our theme uses "displayLeftColumnProduct" and "displayRightColumnProduct" for column left/right in product page.'),
        ),
        array(
            'type' => 'image-select',
            'label' => $this->l('Main content layout'),
            'name' => 'main_layout',
            'options' => array(
                'query' => array(
                    array(
                        'id_option' => 1,
                        'name' => $this->l('Basic'),
                        'img' => 'main-layout-1.png'
                    ),
                    array(
                        'id_option' => 2,
                        'name' => $this->l('3 columns'),
                        'img' => 'main-layout-2.png'
                    ),
                    array(
                        'id_option' => 3,
                        'name' => $this->l('Specific'),
                        'img' => 'main-layout-3.png'
                    ),
                ),
                'id' => 'id_option',
                'name' => 'name',
            ),
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Image product'),
            'name' => 'product_image',
            'options' => array (
                'query' => array(
                	'1' => array('id' => 'horizontal' , 'name' => 'Horizontal in bottom'),
       				'2' => array('id' => 'left' , 'name' => 'Vertical at left'),
       				'3' => array('id' => 'right' , 'name' => 'Vertical at right'),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'image-select',
            'label' => $this->l('Information & reviews layout'),
            'name' => 'information_layout',
            'options' => array(
                'query' => array(
                    array(
                        'id_option' => 1,
                        'name' => $this->l('Show all content'),
                        'img' => 'information-layout-1.png'
                    ),
                    array(
                        'id_option' => 2,
                        'name' => $this->l('Tab - horizontal'),
                        'img' => 'information-layout-2.png'
                    ),
                    array(
                        'id_option' => 3,
                        'name' => $this->l('Tab - vertical'),
                        'img' => 'information-layout-3.png'
                    ),
                    array(
                        'id_option' => 4,
                        'name' => $this->l('Accordion'),
                        'img' => 'information-layout-4.png'
                    ),
                ),
                'id' => 'id_option',
                'name' => 'name',
            ),
        ),
        array(
        	'type' => 'wrapper_open',
        	'class' => 'productp-layout1 productp-layout3 productp-layout'
        ),
        array(
        	'type' => 'wrapper_close',
        ),
        array(
        	'type' => 'wrapper_open',
        	'class' => 'productp-layout2 productp-layout'
        ),
        array(
        	'type' => 'wrapper_close',
        ),
        array(
            'type' => 'wrapper_open',
            'class' => 'productp-layout3 productp-layout'
        ),
        array(
            'type' => 'wrapper_close',
        ),
        array(
        	'type' => 'wrapper_open',
        	'class' => 'productp-layout4 productp-layout'
        ),
        array(
        	'type' => 'wrapper_close',
        ),
        array(
            'type' => 'infoheading',
            'label' => $this->l('Style'),
            'name'=> 'ppagec'
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Product name color'),
            'name' => 'product_name_color',
        ), 
        array(
            'type' => 'text',
            'label' => $this->l('Product name font size'),
            'name' => 'product_name_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Product name transform'),
            'name' => 'product_name_transform',
            'options' => array (
                'query' => self::$text_transform,
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Price color'),
            'name' => 'product_price_color',
        ),  
        array(
            'type' => 'text',
            'label' => $this->l('Price font size'),
            'name' => 'product_price_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )

);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Custom CSS/JS'),
        'icon' => 'icon-clipboard'
    ),
    'input' => array(
        array(
            'type' => 'customtextarea',
            'name' => 'custom_css',
            'rows' => 15,
            'label' => $this->l('Custom CSS'),
            'required' => false,
            'lang' => false
        ),
        array(
            'type' => 'customtextarea',
            'name' => 'custom_js',
            'rows' => 15,
            'label' => $this->l('Custom JS'),
            'required' => false,
            'lang' => false
        )
        
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )
);
//Import tab
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Demo setup'),
        'icon' => 'icon-cloud-download'
    ),
    'input' => array(
        array(
            'type' => 'posthemes',
            'label' => $this->l('Demo setup'),
            'name'=> 'posthemes'
        ),
    ),
    
);
//Support
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Support'),
        'icon' => 'icon-question-circle'
    ),
    'input' => array(
        
    ),
    
);