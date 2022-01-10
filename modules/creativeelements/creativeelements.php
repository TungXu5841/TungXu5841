<?php
/**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   One domain support license
 */

defined('_PS_VERSION_') or die;

define('_CE_VERSION_', '2.5.7');
define('_CE_PATH_', _PS_MODULE_DIR_ . 'creativeelements/');
define('_CE_URL_', defined('_PS_BO_ALL_THEMES_DIR_') ? _MODULE_DIR_ . 'creativeelements/' : 'modules/creativeelements/');
define('_CE_ASSETS_URL_', _CE_URL_ . 'views/');
define('_CE_TEMPLATES_', _CE_PATH_ . 'views/templates/');

require_once _CE_PATH_ . 'classes/CETheme.php';
require_once _CE_PATH_ . 'classes/CEContent.php';
require_once _CE_PATH_ . 'classes/CETemplate.php';
require_once _CE_PATH_ . 'classes/CESmarty.php';
require_once _CE_PATH_ . 'includes/plugin.php';

class CreativeElements extends Module
{
    protected static $controller;

    public $controllers = [
        'ajax',
        'preview',
    ];

    protected $overrides = [
        'Category',
        'CmsCategory',
        'Manufacturer',
        'Supplier',
    ];
    protected $tplOverride = false;

    public function __construct($name = null, Context $context = null)
    {
        $this->name = 'creativeelements';
        $this->tab = 'content_management';
        $this->version = '2.5.7';
        $this->author = 'WebshopWorks';
        $this->module_key = '7a5ebcc21c1764675f1db5d0f0eacfe5';
        $this->ps_versions_compliancy = ['min' => '1.7.0', 'max' => '1.7'];
        $this->bootstrap = true;
        $this->displayName = $this->l('Creative Elements - live PageBuilder');
        $this->description = $this->l('The most advanced frontend drag & drop page builder. Create high-end, pixel perfect websites at record speeds. Any theme, any page, any design.');
        parent::__construct($this->name, null);

        $this->checkThemeChange();

        Shop::addTableAssociation(CETheme::$definition['table'], ['type' => 'shop']);
        Shop::addTableAssociation(CETheme::$definition['table'] . '_lang', ['type' => 'fk_shop']);
        Shop::addTableAssociation(CEContent::$definition['table'], ['type' => 'shop']);
        Shop::addTableAssociation(CEContent::$definition['table'] . '_lang', ['type' => 'fk_shop']);
    }

    public function install()
    {
        require_once _CE_PATH_ . 'classes/CEDatabase.php';

        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        CEDatabase::initConfigs();

        if (!CEDatabase::createTables()) {
            $this->_errors[] = Db::getInstance()->getMsgError();
            return false;
        }

        if ($res = parent::install() && CEDatabase::updateTabs()) {
            foreach (CEDatabase::getHooks() as $hook) {
                $res = $res && $this->registerHook($hook, null, 1);
            }
        }

        return $res;
    }

    public function uninstall()
    {
        foreach (Tab::getCollectionFromModule($this->name) as $tab) {
            $tab->delete();
        }

        return parent::uninstall();
    }

    public function enable($force_all = false)
    {
        return parent::enable($force_all) && Db::getInstance()->update(
            'tab',
            ['active' => 1],
            "module = 'creativeelements' AND class_name != 'AdminCEEditor'"
        );
    }

    public function disable($force_all = false)
    {
        return Db::getInstance()->update(
            'tab',
            ['active' => 0],
            "module = 'creativeelements'"
        ) && parent::disable($force_all);
    }

    public function addOverride($classname)
    {
        try {
            return parent::addOverride($classname);
        } catch (Exception $ex) {
            return false;
        }
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminCESettings'));
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        if (!Configuration::get("PS_ALLOW_HTML_\x49FRAME")) {
            Configuration::updateValue("PS_ALLOW_HTML_\x49FRAME", 1);
        }

        // Handle migrate
        if ((Configuration::getGlobalValue('ce_migrate') || Tools::getIsset('CEMigrate')) &&
            Db::getInstance()->executeS("SHOW TABLES LIKE '%_ce_meta'")
        ) {
            require_once _CE_PATH_ . 'classes/CEMigrate.php';
            CEMigrate::registerJavascripts();
        }

        $footer_product = '';
        preg_match('~/([^/]+)/(\d+)/edit\b~', $_SERVER['REQUEST_URI'], $req);
        $controller = Tools::strtolower(Tools::getValue('controller'));

        switch ($controller) {
            case 'admincetemplates':
                $id_type = CE\UId::TEMPLATE;
                $id = (int) Tools::getValue('id_ce_template');
                break;
            case 'adminceheader':
                $id_type = CE\UId::THEME;
                $id = (int) Tools::getValue('id_ce_theme');
                break;
            case 'admincehome':
                $id_type = CE\UId::THEME;
                $id = (int) Tools::getValue('id_ce_theme');
                break;
            case 'admincefooter':
                $id_type = CE\UId::THEME;
                $id = (int) Tools::getValue('id_ce_theme');
                break;
            case 'admincecontent':
                $id_type = CE\UId::CONTENT;
                $id = (int) Tools::getValue('id_ce_content');
                break;
            case 'admincmscontent':
                if ($req && $req[1] == 'category' || Tools::getIsset('addcms_category') || Tools::getIsset('updatecms_category')) {
                    $id_type = CE\UId::CMS_CATEGORY;
                    $id = (int) Tools::getValue('id_cms_category', $req ? $req[2] : 0);
                    break;
                }
                $id_type = CE\UId::CMS;
                $id = (int) Tools::getValue('id_cms', $req ? $req[2] : 0);
                break;
            case 'adminproducts':
                $id_type = CE\UId::PRODUCT;
                $id = (int) Tools::getValue('id_product', basename(explode('?', $_SERVER['REQUEST_URI'])[0]));
                $footer_product = new CE\UId(CEContent::getFooterProductId($id), CE\UId::CONTENT, 0, $this->context->shop->id);
                break;
            case 'admincategories':
                $id_type = CE\UId::CATEGORY;
                $id = (int) Tools::getValue('id_category', $req ? $req[2] : 0);
                break;
            case 'adminmanufacturers':
                $id_type = CE\UId::MANUFACTURER;
                $id = (int) Tools::getValue('id_manufacturer', $req ? $req[2] : 0);
                break;
            case 'adminsuppliers':
                $id_type = CE\UId::SUPPLIER;
                $id = (int) Tools::getValue('id_supplier', $req ? $req[2] : 0);
                break;
            case 'adminxippost':
                $id_type = CE\UId::XIPBLOG_POST;
                $id = (int) Tools::getValue('id_xipposts');
                break;
            case 'adminstblog':
                $id_type = CE\UId::STBLOG_POST;
                $id = (int) Tools::getValue('id_st_blog');
                break;
            case 'adminblogposts':
                if ('advanceblog' === $this->context->controller->module->name) {
                    $id_type = CE\UId::ADVANCEBLOG_POST;
                    $id = (int) Tools::getValue('id_post');
                }
                break;
            case 'adminpsblogblogs':
                $id_type = CE\UId::PSBLOG_POST;
                $id = (int) Tools::getValue('id_psblog_blog');
                break;
            case 'admintvcmspost':
                $id_type = CE\UId::TVCMSBLOG_POST;
                $id = (int) Tools::getValue('id_tvcmsposts');
                break;
            case 'adminmodules':
                $configure = Tools::strtolower(Tools::getValue('configure'));

                if ('ybc_blog' == $configure && Tools::getValue('control') == 'post') {
                    $id_type = CE\UId::YBC_BLOG_POST;
                    $id = (int) Tools::getValue('id_post');
                    break;
                }
                if ('prestablog' == $configure && Tools::getIsset('editNews')) {
                    $id_type = CE\UId::PRESTABLOG_POST;
                    $id = (int) Tools::getValue('idN');
                    break;
                }
                if ('hiblog' == $configure) {
                    $id_type = CE\UId::HIBLOG_POST;
                    $id = 0;
                    $hideEditor = [];
                    break;
                }
                break;
            case 'adminmaintenance':
                $id_type = CE\UId::CONTENT;
                $id = CEContent::getMaintenanceId();

                $uids = CE\UId::getBuiltList($id, $id_type, $this->context->shop->id);
                $hideEditor = empty($uids) ? $uids : array_keys($uids[$this->context->shop->id]);
                break;
        }

        if (isset($id)) {
            self::$controller = $this->context->controller;
            self::$controller->addJQuery();
            self::$controller->js_files[] = $this->_path . 'views/js/admin.js?v=' . _CE_VERSION_;
            self::$controller->css_files[$this->_path . 'views/css/admin.css?v=' . _CE_VERSION_] = 'all';

            $uid = new CE\UId($id, $id_type, 0, Shop::getContext() === Shop::CONTEXT_SHOP ? $this->context->shop->id : 0);

            isset($hideEditor) or $hideEditor = $uid->getBuiltLangIdList();

            Media::addJsDef([
                'ceAdmin' => [
                    'uid' => "$uid",
                    'hideEditor' => $hideEditor,
                    'footerProduct' => "$footer_product",
                    'i18n' => [
                        'edit' => str_replace("'", "’", $this->l('Edit with Creative Elements')),
                        'save' => str_replace("'", "’", $this->l('Please save the form before editing with Creative Elements')),
                        'error' => str_replace("'", "’", $this->getErrorMsg()),
                    ],
                    'editorUrl' => Tools::safeOutput($this->context->link->getAdminLink('AdminCEEditor') . '&uid='),
                    'languages' => Language::getLanguages(true, $uid->id_shop),
                ],
            ]);
            $this->context->smarty->assign('edit_width_ce', $this->context->link->getAdminLink('AdminCEEditor'));
        }
        return $this->context->smarty->fetch(_CE_TEMPLATES_ . 'hook/backoffice_header.tpl');
    }

    protected function getErrorMsg()
    {
        if (!Configuration::get('PS_SHOP_ENABLE', null, null, $this->context->shop->id)) {
            $ips = explode(',', Configuration::get('PS_MAINTENANCE_IP', null, null, $this->context->shop->id));

            if (!in_array(Tools::getRemoteAddr(), $ips)) {
                return $this->l('The shop is in maintenance mode, please whitelist your IP');
            }
        }

        $id_tab = Tab::getIdFromClassName('AdminCEEditor');
        $access = Profile::getProfileAccess($this->context->employee->id_profile, $id_tab);

        if ('1' !== $access['view']) {
            return CE\Helper::transError('You do not have permission to view this.');
        }

        $class = isset(self::$controller->className) ? self::$controller->className : '';

        if (in_array($class, $this->overrides)) {
            $loadObject = new ReflectionMethod(self::$controller, 'loadObject');
            $loadObject->setAccessible(true);

            if (empty($loadObject->invoke(self::$controller, true)->active) && !defined("$class::CE_OVERRIDE")) {
                return $this->l('You can not edit items which are not displayed, because an override file is missing. Please contact us on https://addons.prestashop.com');
            }
        }
        return '';
    }

    public function hookHeader()
    {
        // Compatibility fix for PS 1.7.7.x upgrade
        return $this->hookDisplayHeader();
    }

    public function hookDisplayHeader()
    {
        self::$controller = $this->context->controller;

        $plugin = CE\Plugin::instance();
        CE\did_action('template_redirect') or CE\do_action('template_redirect');

        if (self::getPreviewUId()) {
            if ('widget' === Tools::getValue('render') && Tools::getIsset('actions')) {
                $this->tplOverride = '';

                $request = json_decode(${'_POST'}['actions'], true);
                CE\setup_postdata($request['editor_post_id']);
                $response = $plugin->widgets_manager->ajaxRenderWidget($request);
                empty($response) or http_response_code(200);
                die(json_encode($response));
            }
            header_register_callback(function () {
                header_remove('Content-Security-Policy');
                header_remove('X-Content-Type-Options');
                header_remove('X-Frame-Options');
                header_remove('X-Xss-Protection');
            });
            if (Tools::getValue('ctx') > Shop::CONTEXT_SHOP) {
                self::$controller->warning[] = CESmarty::get(_CE_TEMPLATES_ . 'admin/admin.tpl', 'ce_warning_multistore');
            }
        }
        
        require_once _CE_PATH_ . 'classes/assets/CEAssetManager.php';
        
        CEAssetManager::instance();

        $uid_preview = self::getPreviewUId(false);

        if ($uid_preview && CE\UId::CONTENT === $uid_preview->id_type) {
            Tools::getIsset('maintenance') && $this->displayMaintenancePage();
        }
    }

    public function hookOverrideLayoutTemplate($params)
    {
        if (false !== $this->tplOverride || !self::$controller) {
            return $this->tplOverride;
        }
        $this->tplOverride = '';

        if (self::isMaintenance()) {
            return $this->tplOverride;
        }
        // Page Content
        $controller = self::$controller;
        $tpl_vars = &$this->context->smarty->tpl_vars;
        $front = Tools::strtolower(preg_replace('/(ModuleFront)?Controller(Override)?$/i', '', get_class($controller)));
        // PrestaBlog fix for non-default blog URL
        stripos($front, 'prestablog') === 0 && 'prestablog' . Configuration::get('prestablog_urlblog') === $front && $front = 'prestablogblog';

        switch ($front) {
            case 'creativeelementspreview':
                $model = self::getPreviewUId(false)->getModel();
                $key = $model::${'definition'}['table'];

                if (isset($tpl_vars[$key]->value['id'])) {
                    $id = $tpl_vars[$key]->value['id'];
                    $desc = ['description' => &$tpl_vars[$key]->value['content']];
                }
                break;
            case 'cms':
                $model = class_exists('CMS') ? 'CMS' : 'CMSCategory';
                $key = $model::${'definition'}['table'];

                if (isset($tpl_vars[$key]->value['id'])) {
                    $id = $tpl_vars[$key]->value['id'];
                    $desc = ['description' => &$tpl_vars[$key]->value['content']];

                    CE\add_action('wp_head', 'print_og_image');
                } elseif (isset($controller->cms->id)) {
                    $id = $controller->cms->id;
                    $desc = ['description' => &$controller->cms->content];
                } elseif (isset($tpl_vars['cms_category']->value['id'])) {
                    $model = 'CMSCategory';
                    $id = $tpl_vars['cms_category']->value['id'];
                    $desc = &$tpl_vars['cms_category']->value;
                } elseif (isset($controller->cms_category->id)) {
                    $model = 'CMSCategory';
                    $id = $controller->cms_category->id;
                    $desc = ['description' => &$controller->cms_category->description];
                }
                break;
            case 'product':
            case 'category':
            case 'manufacturer':
            case 'supplier':
                $model = $front;

                if (isset($tpl_vars[$model]->value['id'])) {
                    $id = $tpl_vars[$model]->value['id'];
                    $desc = &$tpl_vars[$model]->value;
                } elseif (method_exists($controller, "get$model") && Validate::isLoadedObject($obj = $controller->{"get$model"}())) {
                    $id = $obj->id;
                    $desc = ['description' => &$obj->description];
                }
                break;
            case 'ybc_blogblog':
                $model = 'Ybc_blog_post_class';

                if (isset($tpl_vars['blog_post']->value['id_post'])) {
                    $id = $tpl_vars['blog_post']->value['id_post'];
                    $desc = &$tpl_vars['blog_post']->value;

                    if (Tools::getIsset('adtoken') && self::hasAdminToken('AdminModules')) {
                        // override post status for preview
                        $tpl_vars['blog_post']->value['enabled'] = 1;
                    }
                }
                break;
            case 'xipblogsingle':
                $model = 'XipPostsClass';

                if (isset($tpl_vars['xipblogpost']->value['id_xipposts'])) {
                    $id = $tpl_vars['xipblogpost']->value['id_xipposts'];
                    $desc = ['description' => &$tpl_vars['xipblogpost']->value['post_content']];
                } elseif (isset($controller->blogpost['id_xipposts'])) {
                    $id = $controller->blogpost['id_xipposts'];
                    $desc = ['description' => &$controller->blogpost['post_content']];
                }
                break;
            case 'stblogarticle':
                $model = 'StBlogClass';

                if (isset($tpl_vars['blog']->value['id'])) {
                    $id = $tpl_vars['blog']->value['id'];
                    $desc = ['description' => &$tpl_vars['blog']->value['content']];
                    break;
                }
                $blogProp = new ReflectionProperty($controller, 'blog');
                $blogProp->setAccessible(true);
                $blog = $blogProp->getValue($controller);

                if (isset($blog->id)) {
                    $id = $blog->id;
                    $desc = ['description' => &$blog->content];
                }
                break;
            case 'advanceblogdetail':
                $model = 'BlogPosts';

                if (isset($tpl_vars['postData']->value['id_post'])) {
                    $id = $tpl_vars['postData']->value['id_post'];
                    $desc = ['description' => &$tpl_vars['postData']->value['post_content']];
                }
                break;
            case 'prestablogblog':
                $model = 'NewsClass';
                $newsProp = new ReflectionProperty($controller, 'news');
                $newsProp->setAccessible(true);
                $news = $newsProp->getValue($controller);

                if (isset($news->id)) {
                    $id = $news->id;

                    if (isset($tpl_vars['tpl_unique'])) {
                        $desc = ['description' => &$tpl_vars['tpl_unique']->value];
                    } else {
                        $desc = ['description' => &$news->content];
                    }
                }
                break;
            case 'ph_simpleblogsingle':
                $model = 'SimpleBlogPost';
                break;
            case 'tvcmsblogsingle':
                $model = 'TvcmsPostsClass';

                if (isset($tpl_vars['tvcmsblogpost']->value['id_tvcmsposts'])) {
                    $id = $tpl_vars['tvcmsblogpost']->value['id_tvcmsposts'];
                    $desc = ['description' => &$tpl_vars['tvcmsblogpost']->value['post_content']];
                }
                break;
        }

        if (isset($id)) {
            $uid_preview = self::getPreviewUId();

            if ($uid_preview && $uid_preview->id === (int) $id && $uid_preview->id_type === CE\UId::getTypeId($model)) {
                CE\UId::$_ID = $uid_preview;
            } elseif (!CE\UId::$_ID) {
                CE\UId::$_ID = new CE\UId($id, CE\UId::getTypeId($model), $this->context->language->id, $this->context->shop->id);
            }

            if (CE\UId::$_ID) {
                $this->filterBodyClasses();

                $desc['description'] = CE\apply_filters('the_content', $desc['description']);
            }
        }

        // Theme Builder
        $themes = [
            'header' => Configuration::get('CE_HEADER'),
            'footer' => Configuration::get('CE_FOOTER'),
        ];
        $pages = [
            'index' => 'page-index',
            'contact' => 'page-contact',
            'pagenotfound' => 'page-not-found',
        ];
        foreach ($pages as $page_type => $theme_type) {
            if ($front === $page_type) {
                $themes[$theme_type] = Configuration::get(self::getThemeVarName($theme_type));
                break;
            }
        }
        $uid_preview = self::getPreviewUId(false);

        if ($uid_preview && CE\UId::THEME === $uid_preview->id_type) {
            $preview = $this->renderTheme($uid_preview);

            $document = CE\Plugin::$instance->documents->getDocForFrontend($uid_preview);
            $type_preview = $document->getTemplateType();
            $this->context->smarty->assign(self::getThemeVarName($type_preview), $preview);

            if (stripos($type_preview, 'page-') === 0) {
                CE\UId::$_ID = $uid_preview;
                $desc = ['description' => &$preview];
                $this->filterBodyClasses();
                CE\add_action('wp_head', 'print_og_image');
            }
            unset($themes[$type_preview]);
        }
        if (isset($pages[$front]) && !empty($themes[$pages[$front]])) {
            $theme_type = $pages[$front];
            CE\UId::$_ID = new CE\UId($themes[$theme_type], CE\UId::THEME, $this->context->language->id, $this->context->shop->id);
            $desc = ['description' => $this->renderTheme(CE\UId::$_ID)];
            $this->context->smarty->assign(self::getThemeVarName($theme_type), $desc['description']);
            $this->filterBodyClasses();
            CE\add_action('wp_head', 'print_og_image');

            unset($themes[$theme_type]);
        }

        $this->tplOverride = CE\apply_filters('template_include', $this->tplOverride);

        if (strrpos($this->tplOverride, 'layout-canvas') !== false) {
            empty($desc) or $this->context->smarty->assign('ce_desc', $desc);
        } else {
            foreach ($themes as $theme_type => $id_ce_theme) {
                empty($id_ce_theme) or $this->context->smarty->assign(
                    self::getThemeVarName($theme_type),
                    $this->renderTheme(new CE\UId($id_ce_theme, CE\UId::THEME, $this->context->language->id, $this->context->shop->id))
                );
            }
        }

        return $this->tplOverride;
    }

    public function hookDisplayOverrideTemplate($params)
    {
        
    }

    protected function filterBodyClasses()
    {
        $tpl_vars = &$this->context->smarty->tpl_vars;

        $body_classes = &$tpl_vars['page']->value['body_classes'];
        $body_classes['elementor-page'] = 1;
        $body_classes['elementor-page-' . CE\get_the_ID()->toDefault()] = 1;
        
    }

    protected function displayMaintenancePage()
    {
        Configuration::set('PS_SHOP_ENABLE', false);
        Configuration::set('PS_MAINTENANCE_IP', '');

        $displayMaintenancePage = new ReflectionMethod($this->context->controller, 'displayMaintenancePage');
        $displayMaintenancePage->setAccessible(true);
        $displayMaintenancePage->invoke($this->context->controller);
    }

    public function hookDisplayMaintenance($params)
    {
        if (self::getPreviewUId(false)) {
            http_response_code(200);
            header_remove('Retry-After');
        } else {
            $this->hookDisplayHeader();
        }

        CE\add_filter('the_content', function () {
            $uid = CE\get_the_ID();
            ${'this'}->context->smarty->assign('ce_content', new CEContent($uid->id, $uid->id_lang, $uid->id_shop));

            CE\remove_all_filters('the_content');
        }, 0);

        if (!$maintenance = $this->renderContent('displayMaintenance', $params)) {
            return;
        }
        
        self::$controller->registerJavascript('jquery', 'js/jquery/jquery-1.11.0.min.js');

        $this->unshiftTemplateDir(_CE_TEMPLATES_ . 'front/theme/');

        $this->context->smarty->assign([
            'iso_code' => $this->context->language->iso_code,
            'favicon' => Configuration::get('PS_FAVICON'),
            'favicon_update_time' => Configuration::get('PS_IMG_UPDATE_TIME'),
        ]);
        return $maintenance;
    }

    public function hookDisplayFooterProduct($params)
    {
        return $this->renderContent('displayFooterProduct', $params);
    }

    public function __call($method, $args)
    {
        if (stripos($method, 'hookActionObject') === 0 && stripos($method, 'DeleteAfter') !== false) {
            call_user_func_array([$this, 'hookActionObjectDeleteAfter'], $args);
        } elseif (stripos($method, 'hook') === 0) {
            // render hook only after Header init or if it's Home
            if (false !== $this->tplOverride || !strcasecmp($method, 'hookDisplayHome')) {
                return $this->renderContent(Tools::substr($method, 4), $args);
            }
        } else {
            throw new Exception('Can not find method: ' . $method);
        }
    }

    public function renderContent($hook_name = null)
    {
        if (!$hook_name) {
            return;
        }
        $out = '';
        $rows = CEContent::getIdsByHook(
            $hook_name,
            $id_lang = $this->context->language->id,
            $id_shop = $this->context->shop->id,
            Tools::getValue('id_product', 0),
            self::getPreviewUId()
        );
        if ($rows) {
            $uid = CE\UId::$_ID;

            foreach ($rows as $row) {
                CE\UId::$_ID = new CE\UId($row['id'], CE\UId::CONTENT, $id_lang, $id_shop);

                $out .= CE\apply_filters('the_content', '');
            }
            CE\UId::$_ID = $uid;
        }
        return $out;
    }

    public function renderTheme($uid)
    {
        static $unshift;
        is_null($unshift) && $unshift = $this->unshiftTemplateDir(_CE_TEMPLATES_ . 'front/theme/');

        $tmp = CE\UId::$_ID;
        CE\UId::$_ID = $uid;
        $out = CE\apply_filters('the_content', '');
        CE\UId::$_ID = $tmp;

        return $out;
    }

    protected function unshiftTemplateDir($path)
    {
        $tpl_dir = $this->context->smarty->getTemplateDir();
        $res = array_unshift($tpl_dir, $path);
        $this->context->smarty->setTemplateDir($tpl_dir);

        return $res;
    }

    public function registerHook($hook_name, $shop_list = null, $position = null)
    {
        $res = parent::registerHook($hook_name, $shop_list);

        if ($res && is_numeric($position)) {
            $this->updatePosition(Hook::getIdByName($hook_name), 0, $position);
        }
        return $res;
    }

    public function hookCETemplate($params)
    {
        if (empty($params['id']) || !Validate::isLoadedObject($tpl = new CETemplate($params['id'])) || !$tpl->active) {
            return;
        }
        $uid = CE\UId::$_ID;
        CE\UId::$_ID = new CE\UId($params['id'], CE\UId::TEMPLATE);
        $out = CE\apply_filters('the_content', '');
        CE\UId::$_ID = $uid;

        return $out;
    }

    public function hookActionObjectDeleteAfter($params)
    {
        $model = get_class($params['object']);
        $id_type = CE\UId::getTypeId($model);
        $id_half = sprintf('%d%02d', $params['object']->id, $id_type);

        // Delete meta data
        Db::getInstance()->delete('ce_meta', "id LIKE '{$id_half}____'");

        // Delete CSS files
        $css_files = glob(_CE_PATH_ . "views/css/ce/$id_half????.css", GLOB_NOSORT);

        foreach ($css_files as $css_file) {
            Tools::deleteFile($css_file);
        }
    }

    public function hookActionObjectProductDeleteAfter($params)
    {
        $this->hookActionObjectDeleteAfter($params);

        // Delete displayFooterProduct content
        if ($id = CEContent::getFooterProductId($params['object']->id)) {
            $content = new CEContent($id);
            Validate::isLoadedObject($content) && $content->delete();
        }
    }

    public function hookActionProductAdd($params)
    {
        if (isset($params['request']) && $params['request']->attributes->get('action') === 'duplicate') {
            $id_product_duplicate = (int) $params['request']->attributes->get('id');
        } elseif (Tools::getIsset('duplicateproduct')) {
            $id_product_duplicate = (int) Tools::getValue('id_product');
        }

        if (isset($id_product_duplicate, $params['id_product']) &&
            $built_list = CE\UId::getBuiltList($id_product_duplicate, CE\UId::PRODUCT)
        ) {
            $db = CE\Plugin::instance()->db;
            $uid = new CE\UId($params['id_product'], CE\UId::PRODUCT, 0);

            foreach ($built_list as $id_shop => &$langs) {
                foreach ($langs as $id_lang => $uid_from) {
                    $uid->id_lang = $id_lang;
                    $uid->id_shop = $id_shop;

                    $db->copyElementorMeta($uid_from, $uid);
                }
            }
        }
    }

    protected function checkThemeChange()
    {
        if (!empty($this->context->shop->theme)) {
            $theme = $this->context->shop->theme->get('name');
            $ce_theme = Configuration::get('CE_THEME');

            if (empty($ce_theme)) {
                Configuration::updateValue('CE_THEME', $theme);
            } elseif ($ce_theme != $theme) {
                require_once _CE_PATH_ . 'classes/CEDatabase.php';

                // register missing hooks after changing theme
                foreach (CEDatabase::getHooks() as $hook) {
                    $this->registerHook($hook, null, 1);
                }
                Configuration::updateValue('CE_THEME', $theme);
            }
        }
    }

    public static function getPreviewUId($embed = true)
    {
        static $res = null;

        if (null === $res && $res = Tools::getIsset('preview_id') &&
            $uid = CE\UId::parse(Tools::getValue('preview_id'))
        ) {
            $admin = $uid->getAdminController();
            $key = 'AdminBlogPosts' === $admin ? 'blogtoken' : 'adtoken';
            $res = self::hasAdminToken($admin, $key) ? $uid : false;
        }
        return !$embed || Tools::getIsset('ver') ? $res : false;
    }

    public static function hasAdminToken($tab, $key = 'adtoken')
    {
        $adtoken = Tools::getAdminToken($tab . (int) Tab::getIdFromClassName($tab) . (int) Tools::getValue('id_employee'));

        return Tools::getValue($key) == $adtoken;
    }

    public static function getThemeVarName($theme_type)
    {
        return 'CE_' . Tools::strtoupper(str_replace('-', '_', $theme_type));
    }

    public static function isMaintenance()
    {
        return !Configuration::get('PS_SHOP_ENABLE') &&
            !in_array(Tools::getRemoteAddr(), explode(',', Configuration::get('PS_MAINTENANCE_IP')));
    }

    public function _getProductsPath($items_type)
    {       
        $items_type_path = [];

        for( $i = 1; $i <= 30; $i++ ){
            $items_type_path[$i] = 'catalog/_partials/miniatures/_partials/_product/product-' . $i . '.tpl';
        }

        $items_type_path = Wp_Helper::apply_filters( 'axoncreator_products_type_path', $items_type_path );  
        
        return $items_type_path[$items_type];
    }
    
    public function _prepProductsSelected($settings)
    {   
        $content = array();
        $data = array();
        
        $content['products'] = $this->execProducts('s', $settings, 0, null, null, 1);   
        $content['lastPage'] = true;
        
        $content['items_type_path'] = $this->_getProductsPath($settings['items_type']);
        
        return $content;
    }
        
    public function _prepProducts($settings)
    {   
        $content = array();
        
        $source = $settings['source'];
        $limit = (int)$settings['limit'] <= 0 ? 10 : (int)$settings['limit'];
        $order_by = $settings['order_by'];
        $order_way = $settings['order_way'];
        
        if($source == 'c'){
            $source = $settings['category'];
            if ($settings['randomize']) {
                $order_by = 'rand';
            }
        }
                
        $page = $settings['paged'];
                
        $content['products'] = $this->execProducts($source,  $settings, $limit, $order_by, $order_way, $page);
        
        $content['lastPage'] = true;
        
        if( $page > 1 ){
            $content['lastPage'] = !(bool)$this->execProducts($source,  $settings, $limit, $order_by, $order_way, $page + 1);
        }
        
        $content['items_type_path'] = $this->_getProductsPath($settings['items_type']);
        
        return $content;
    }

    protected function getProduct($id)
    {
        $presenter = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
            new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link),
            $this->context->link,
            new \PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
            new \PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $this->context->getTranslator()
        );
        $presenterFactory = new \ProductPresenterFactory($this->context);
        $assembler = new \ProductAssembler($this->context);
        $result = ['id_product' => $id];

        try {
            if (!$assembledProduct = $assembler->assembleProduct($result)) {
                return false;
            }
            return $presenter->present(
                $presenterFactory->getPresentationSettings(),
                $assembledProduct,
                $this->context->language
            );
        } catch (\Exception $ex) {
            return false;
        }
    }
    
    public function getProducts($listing, $order_by, $order_dir, $limit, $id_category = 2, $products = [])
    {                       
        $tpls = [];

        if ('products' === $listing) {
            // Custom Products
            if ('rand' === $order_by) {
                shuffle($products);
            }
            foreach ($products as &$product) {
                if ($product['id']) {
                    $tpls[] = $this->getProduct($product['id']);
                }
            }
            return $tpls;
        }

        $translator = $this->context->getTranslator();
        $query = new \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery();
        $query->setResultsPerPage($limit <= 0 ? 8 : (int) $limit);
        $query->setQueryType($listing);

        switch ($listing) {
            case 'category':
                $category = new \Category((int) $id_category);
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider($translator, $category);
                $query->setSortOrder(
                    'rand' == $order_by
                    ? \PrestaShop\PrestaShop\Core\Product\Search\SortOrder::random()
                    : new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir)
                );
                break;
            case 'prices-drop':
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\PricesDrop\PricesDropProductSearchProvider($translator);
                $query->setSortOrder(new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir));
                break;
            case 'new-products':
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\NewProducts\NewProductsProductSearchProvider($translator);
                $query->setSortOrder(new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir));
                break;
            case 'best-sales':
                $searchProvider = new \PrestaShop\PrestaShop\Adapter\BestSales\BestSalesProductSearchProvider($translator);
                $query->setSortOrder(new \PrestaShop\PrestaShop\Core\Product\Search\SortOrder('product', $order_by, $order_dir));
                break;
        }
        $result = $searchProvider->runQuery(new \PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext($this->context), $query);

        $assembler = new \ProductAssembler($this->context);
        $presenterFactory = new \ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new \PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
            new \PrestaShop\PrestaShop\Adapter\Image\ImageRetriever($this->context->link),
            $this->context->link,
            new \PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
            new \PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $translator
        );

        foreach ($result->getProducts() as $rawProduct) {
            $tpls[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }
        return $tpls;
    }
    
}