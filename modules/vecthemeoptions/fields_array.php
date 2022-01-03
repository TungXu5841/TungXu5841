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
            'label' => $this->l('Title block'),
            'name' => 'heading_content'
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Title block- Google font URL'),
            'name' => 'g_title_gfont_url',
            'desc' => $this->l('Example: https://fonts.googleapis.com/css?family=Open+Sans:400,700 Add 400 and 700 font weigh if exist. If you need adds latin-ext or cyrilic too. Go to '). '<a href="https://www.google.com/fonts" target="_blank">'.$this->l('Google font').'</a> to get font URL',
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Title block- Google font name'),
            'name' => 'g_title_gfont_name',
            'desc' => $this->l('Example: \'Montserrat\', sans-serif'),
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Title block font size'),
            'name' => 'g_title_font_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Title block color'),
            'name' => 'g_title_font_color',
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Title block transform'),
            'name' => 'g_title_font_transform',
            'options' => array (
                'query' => self::$text_transform,
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'text',
            'label' => $this->l('Title block fontsize in column'),
            'name' => 'g_title_font_size_column',
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
        'title' => $this->l('Header'),
        'icon' => 'icon-header'
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
        'icon' => 'icon-th'
    ),
    'input' => array(
        array(
            'type' => 'image-select',
            'label' => $this->l('Product grid display'),
            'name' => 'p_display',
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
            'name' => 'p_name_color',
        ), 
        array(
            'type' => 'color2',
            'label' => $this->l('Product name color hover'),
            'name' => 'p_name_colorh',
        ), 
        array(
            'type' => 'text',
            'label' => $this->l('Product name font size'),
            'name' => 'p_name_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'radio',
            'label' => $this->l('Product name length'),
            'name' => 'p_name_length',
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
            'name' => 'p_name_transform',
            'options' => array (
                'query' => self::$text_transform,
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Price color'),
            'name' => 'p_price_color',
        ),  
        array(
            'type' => 'text',
            'label' => $this->l('Price font size'),
            'name' => 'p_price_size',
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
            'type' => 'image-select',
            'label' => $this->l('Category layout'),
            'name' => 'cp_layout',
            'options' => array(
                'query' => array(
                    array(
                        'id_option' => 1,
                        'name' => $this->l('Left column & filter in left column'),
                        'img' => 'category1.jpg'
                    ),
                    array(
                        'id_option' => 2,
                        'name' => $this->l('No column & filter in top'),
                        'img' => 'category2.jpg'
                    ),
                ),
                'id' => 'id_option',
                'name' => 'name',
            ),
			'desc' => $this->l('Go to Design > Theme & Logo > Choose Layouts to manage category page layout'),
        ),
        array(
            'type' => 'infoheading',
            'label' => $this->l('Category header'),
            'name' => 'heading_category_header'
        ),
        array(
            'type' => 'image-select',
            'label' => $this->l('Category header position'),
            'name' => 'cate_header_layout',
            'options' => array(
                'query' => array(
                    array(
                        'id_option' => 1,
                        'name' => $this->l('Default'),
                        'img' => 'category-header1.jpg'
                    ),
                    array(
                        'id_option' => 2,
                        'name' => $this->l('Full width'),
                        'img' => 'category-header2.jpg'
                    ),
                ),
                'id' => 'id_option',
                'name' => 'name',
            ),
            'desc' => $this->l(''),
        ),
        array(
            'type' => 'vec-switch',
            'label' => $this->l('Align'),
            'name' => 'cate_align',
            'class' => 'fixed-width-xs',
            'multi' => 3,
            'values' => array(
                array(
                    'id' => 'left',
                    'value' => 'left',
                    'label' => $this->l('Left'),
                ),
                array(
                    'id' => 'center',
                    'value' => 'center',
                    'label' => $this->l('Center'),
                ),
                array(
                    'id' => 'right',
                    'value' => 'right',
                    'label' => $this->l('Right'),
                ),
            ),
        ),
        array(
            'type' => 'switch',
            'label' => $this->l('Show thumbnail'),
            'name' => 'cate_thumbnail',
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
            'name' => 'cate_description',
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
            'name' => 'cate_description_bottom',
            'class' => 'fixed-width-xs',
            'desc' => $this->l(''),
            'values' => array(
                array(
                    'id' => 'cate_description_bottom_on',
                    'value' => 1,
                    'label' => $this->l('Yes')
                    ),
                array(
                    'id' => 'cate_description_bottom_off',
                    'value' => 0,
                    'label' => $this->l('No')
                )
            ),
            'desc' => $this->l('Category description will be displayed in bottom of page'),
        ),
        array(
            'type' => 'switch',
            'label' => $this->l('Show subcategories'),
            'name' => 'cate_subcategories',
            'class' => 'fixed-width-xs',
            'desc' => $this->l(''),
            'values' => array(
                array(
                    'id' => 'cate_subcategories_on',
                    'value' => 1,
                    'label' => $this->l('Yes')
                    ),
                array(
                    'id' => 'cate_subcategories_off',
                    'value' => 0,
                    'label' => $this->l('No')
                )
            )
        ),
        array(
            'type' => 'vec-switch',
            'label' => $this->l('Subcategories style'),
            'name' => 'cate_subcategories_style',
            'class' => 'fixed-width-xs',
            'multi' => 2,
            'values' => array(
                array(
                    'id' => 'default',
                    'value' => 1,
                    'label' => $this->l('Image'),
                ),
                array(
                    'id' => 'bottom',
                    'value' => 2,
                    'label' => $this->l('Text'),
                ),
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
            'name' => 'cate_perrow',
            'options' => array (
                'query' =>self::$product_row,
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'vec-switch',
            'label' => $this->l('Pagination type'),
            'name' => 'cate_pagination',
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
        array(
            'type' => 'vec-switch',
            'label' => $this->l('Mobile pagination type'),
            'name' => 'cate_pagination_mobile',
            'class' => 'fixed-width-xs',
            'multi' => 3,
            'values' => array(
                array(
                    'id' => 'inherit',
                    'value' => 1,
                    'label' => $this->l('Inherit'),
                ),
                array(
                    'id' => 'infinite',
                    'value' => 2,
                    'label' => $this->l('Infinite'),
                ),
                array(
                    'id' => 'loadmore',
                    'value' => 2,
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
        'icon' => 'icon-archive'
    ),
    'input' => array(
    	array(
            'type' => 'infoheading',
            'label' => $this->l('Product page layout'),
            'name'=> 'ppage'
        ),
    	array(
            'type' => 'image-select',
            'label' => $this->l('Product detail layout'),
            'name' => 'productp_layout',
            'direction' => 'vertical',
            'options' => array(
                'query' => array(
                    array(
                        'id_option' => 1,
                        'name' => $this->l('Layout 1'),
                        'img' => 'product1.jpg'
                    ),
                    array(
                        'id_option' => 2,
                        'name' => $this->l('Layout 2'),
                        'img' => 'product2.jpg'
                    ),
                    array(
                        'id_option' => 3,
                        'name' => $this->l('Layout 3'),
                        'img' => 'product3.jpg'
                    ),
                    array(
                        'id_option' => 4,
                        'name' => $this->l('Layout 4'),
                        'img' => 'product4.jpg'
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
            'type' => 'radio',
            'label' => $this->l('Product thumbnails on desktop devices:'),
            'name' => 'ppl1_thumbnail',
            'default_value' => 0,
            'values' => array(
                array(
                    'id' => 'product_thumbnails_0',
                    'value' => 0,
                    'label' => $this->l('Default'),
                    ),
                array(
                    'id' => 'product_thumbnails_1',
                    'value' => 1,
                    'label' => $this->l('Left side vertical slider'),
                    ),
                array(
                    'id' => 'product_thumbnails_2',
                    'value' => 2,
                    'label' => $this->l('Right side vertical slider'),
                    ),
            ),
            'icon_path' => $this->_path,
            'validation' => 'isUnsignedInt',
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Number of thumbnails'),
            'name' => 'ppl1_items',
            'options' => array (
                'query' => array(
                	1 => array('id' =>3 , 'name' => '3'),
       				2 => array('id' =>4 , 'name' => '4'),
       				3 => array('id' =>5 , 'name' => '5'),
       				4 => array('id' =>6 , 'name' => '6'),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
        	'type' => 'wrapper_close',
        ),
        array(
        	'type' => 'wrapper_open',
        	'class' => 'productp-layout2 productp-layout'
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Column'),
            'name' => 'ppl2_column',
            'options' => array (
                'query' => array(
                	1 => array('id' =>1 , 'name' => '1'),
       				2 => array('id' =>2 , 'name' => '2'),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
        	'type' => 'wrapper_close',
        ),
        array(
            'type' => 'wrapper_open',
            'class' => 'productp-layout3 productp-layout'
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Background color'),
            'name' => 'productp_background',
        ),
        array(
            'type' => 'wrapper_close',
        ),
        array(
        	'type' => 'wrapper_open',
        	'class' => 'productp-layout4 productp-layout'
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Number of image on screen'),
            'name' => 'ppl3_items',
            'options' => array (
                'query' => array(
                	1 => array('id' =>2 , 'name' => '2'),
       				2 => array('id' =>3 , 'name' => '3'),
       				3 => array('id' =>4 , 'name' => '4'),
       				4 => array('id' =>5 , 'name' => '5'),
                ),
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
        	'type' => 'wrapper_close',
        ),
        array(
            'type' => 'infoheading',
            'label' => $this->l('Configurations'),
            'name'=> 'ppagec'
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Product name color'),
            'name' => 'pp_name_color',
        ), 
        array(
            'type' => 'text',
            'label' => $this->l('Product name font size'),
            'name' => 'pp_name_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'select',
            'label' => $this->l('Product name transform'),
            'name' => 'pp_name_transform',
            'options' => array (
                'query' => self::$text_transform,
                'id' => 'id',
                'name' => 'name'
            ),
        ),
        array(
            'type' => 'color2',
            'label' => $this->l('Price color'),
            'name' => 'pp_price_color',
        ),  
        array(
            'type' => 'text',
            'label' => $this->l('Price font size'),
            'name' => 'pp_price_size',
            'class' => 'fixed-width-sm',
            'suffix' => 'px',
        ),
        array(
            'type' => 'radio',
            'label' => $this->l('Product infomation tab display'),
            'name' => 'pp_infortab',
            'default_value' => 0,
            'values' => array(
                array(
                    'id' => 'product_thumbnails_0',
                    'value' => 0,
                    'label' => $this->l('Horizontal tab'),
                    ),
                array(
                    'id' => 'product_thumbnails_1',
                    'value' => 1,
                    'label' => $this->l('Vertical tab'),
                    ),
                array(
                    'id' => 'product_thumbnails_2',
                    'value' => 2,
                    'label' => $this->l('Accordion'),
                    ),
            ),
            'icon_path' => $this->_path,
            'validation' => 'isUnsignedInt',
        ),
    ),
    'submit' => array(
        'title' => $this->l('Save'),
    )

);
$this->fields_form[]['form'] = array(
    'legend' => array(
        'title' => $this->l('Custom CSS/JS'),
        'icon' => 'icon-pencil-square'
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