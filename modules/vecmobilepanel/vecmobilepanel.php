<?php
/**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author PrestaShop SA <contact@prestashop.com>
 * @copyright  2007-2019 PrestaShop SA
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

include_once(_PS_MODULE_DIR_.'vecmobilepanel/classes/MobilepanelActivity.php');

class vecMobilePanel extends Module implements WidgetInterface
{

    public $name;   
    public $version;
    public $author;
    public $need_instance;
    public $module_key;
    public $controller_name;
    public $bootstrap;
    public $displayName;
    public $description;
    public $img_path;
    public $img_path_perso;
    public $logo_path;
    public $module_path;
    /** @var string Text to display when ask for confirmation on uninstall action */
    public $confirmUninstall;
    
    public $ps_url;
    
    public $folder_file_upload;
    
    private $templateFile;

    public function __construct()
    {
        // Settings
        $this->name = 'vecmobilepanel';
        $this->tab = 'seo';
        $this->version = '1.1.0';
        $this->author = 'ThemeVec';
        $this->need_instance = 0;
        $this->module_key = '938b96386d4d79aa7cb891439cb0ef11';

        $this->bootstrap = true;
        parent::__construct();
        if ($this->context->link == null) {
            $protocolPrefix = Tools::getCurrentUrlProtocolPrefix();
            $this->context->link = new Link($protocolPrefix, $protocolPrefix);
        }

        $this->displayName = $this->l('Vec - Mobile panel');
        $this->description = $this->l('Add quick menu at bottom in mobile');

        // Settings paths
        if (!$this->_path) {
            $this->_path = __PS_BASE_URI__ . 'modules/' . $this->name . '/';
        }
        $this->img_path = $this->_path . 'views/img/';
        $this->img_path_perso = $this->img_path . 'img_perso';
        $this->logo_path = $this->_path . 'logo.png';
        $this->module_path = $this->_path;
        $this->folder_file_upload = _PS_MODULE_DIR_ . $this->name . '/views/img/img_perso/';

        // Confirm uninstall
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall this module?', [], 'Modules.Blockreassurance.Admin');
        $this->ps_url = $this->context->link->getBaseLink();
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->templateFile = 'module:vecmobilepanel/views/templates/hook/vecmobilepanel.tpl';
    }

    /**
     * install pre-config
     *
     * @return bool
     */
    public function install()
    {
        // SQL
        $sqlQueries = [];
        $sqlQueries[] = ' CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'vecmobilepanel` (
            `id_mobilepanel` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `icon` varchar(255) NULL,
            `custom_icon` varchar(255) NULL,
            `status` int(10) unsigned NOT NULL,
            `position` int(10) unsigned NOT NULL,
            `id_shop` int(10) unsigned NOT NULL,
            `type_content` int(10) unsigned NULL,
            `id_cms` varchar(10) NULL,
            PRIMARY KEY (`id_mobilepanel`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';
        $sqlQueries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'vecmobilepanel_lang` (
            `id_mobilepanel` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned NOT NULL,
            `id_shop` int(10) unsigned NOT NULL,
            `title` varchar(255) NOT NULL,
            `html_content` varchar(1000) NOT NULL,
            `link` varchar(255) NOT NULL,
            PRIMARY KEY (`id_mobilepanel`,`id_shop`,`id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

        $sqlQueries[] = 'INSERT INTO ' . _DB_PREFIX_ . 'vecmobilepanel (icon, custom_icon, status, position, id_shop, type_content, id_cms) VALUES '
            . "('" . $this->img_path . "reassurance/pack1/House.svg', null, 1, 1, 1, 1, 'PAGhomepag'),"
            . "('" . $this->img_path . "reassurance/pack1/FullShoppingCart.svg', null, 1, 2, 1, 4, null),"
            . "('" . $this->img_path . "reassurance/pack1/Tools.svg', null, 1, 3, 1, 6, null)";
        foreach (Language::getLanguages(false) as $lang) {
            $sqlQueries[] = 'INSERT INTO ' . _DB_PREFIX_ . 'vecmobilepanel_lang (id_mobilepanel, id_lang, id_shop, title, html_content, link) VALUES '
                . '(1, ' . $lang['id_lang'] . ", 1, '" . $this->trans('Home', [], 'Modules.Blockreassurance.Shop', $lang['locale']) . "', '', ''),"
                . '(2, ' . $lang['id_lang'] . ", 1, '" . $this->trans('Cart', [], 'Modules.Blockreassurance.Shop', $lang['locale']) . "', '', '#'),"
                . '(3, ' . $lang['id_lang'] . ", 1, '" . $this->trans('Custom content', [], 'Modules.Blockreassurance.Shop', $lang['locale']) . "', '" . $this->trans('<p>This is custom content</p>', [], 'Modules.Blockreassurance.Shop', $lang['locale']) . "', '')";
        }

        foreach ($sqlQueries as $query) {
            if (Db::getInstance()->execute($query) == false) {
                return false;
            }
        }

        // Configuration
        Configuration::updateValue('QM_ICON_COLOR', '#F19D76');
        Configuration::updateValue('QM_TEXT_COLOR', '#000000');
        Configuration::updateValue('QM_SHOW_TEXT', '1');

        // Hooks
        if (parent::install() &&
            $this->_createMenu() &&
            $this->registerHook('displayAfterBodyOpeningTag') &&
            $this->registerHook('header') &&
            $this->registerHook('actionFrontControllerSetMedia')
        ) {
            return true;
        }

        $this->_errors[] = $this->trans('There was an error during the installation. Please contact us through Addons website.', [], 'Modules.Blockreassurance.Admin');

        return false;
    }

    /**
     * Uninstall module configuration
     *
     * @return bool
     */
    public function uninstall()
    {
        // SQL
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'vecmobilepanel`, `' . _DB_PREFIX_ . 'vecmobilepanel_lang`';
        if (Db::getInstance()->execute($sql) == false) {
            return false;
        }

        // Configuration
        Configuration::deleteByName('QM_ICON_COLOR');
        Configuration::deleteByName('QM_SHOW_TEXT');
        Configuration::deleteByName('QM_TEXT_COLOR');

        if (parent::uninstall() && $this->_deleteMenu()) {
            return true;
        }

        $this->_errors[] = $this->trans('There was an error during the uninstallation. Please contact us through Addons website.', [], 'Modules.Blockreassurance.Admin');

        return false;
    }

    protected function _createMenu() {
        $response = true;
        // First check for parent tab
        $parentTabID = Tab::getIdFromClassName('VecThemeMenu');
        if($parentTabID){
            $parentTab = new Tab($parentTabID);
        }else{
            $parentconfigure = Tab::getIdFromClassName('IMPROVE');
            $parentTab = new Tab();
            $parentTab->active = 1;
            $parentTab->name = array();
            $parentTab->class_name = "VecThemeMenu";
            foreach (Language::getLanguages() as $lang) {
                $parentTab->name[$lang['id_lang']] = "THEMEVEC";
            }
            $parentTab->id_parent = 0;
            $response &= $parentTab->add();
        }
        
        //Add parent tab: modules
        $parentTabID2 = Tab::getIdFromClassName('VecModules');
        if($parentTabID2){
            $parentTab = new Tab($parentTabID);
        }else{
            $tab = new Tab();
            $tab->active = 1;
            $tab->class_name = "VecModules";
            $tab->name = array();
            foreach (Language::getLanguages(true) as $lang) {
                $tab->name[$lang['id_lang']] = "Modules";
            }
            $tab->id_parent = (int)Tab::getIdFromClassName('VecThemeMenu');
            $tab->module = $this->name;
            $tab->icon = 'open_with';
            $response &= $tab->add();
        }
        //Add tab
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = "AdminMobilepanelListing";
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = "- Mobile bottom panel";
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('VecModules');
        $tab->module = $this->name;
        $response &= $tab->add();

        return $response;
    }

    protected function _deleteMenu() {
        $parentTabID = Tab::getIdFromClassName('VecModules');

        // Get the number of tabs inside our parent tab
        $tabCount = Tab::getNbTabs($parentTabID);
        if ($tabCount == 0) {
            $parentTab = new Tab($parentTabID);
            $parentTab->delete();
        }
        
        $id_tab = (int)Tab::getIdFromClassName('AdminMobilepanelListing');
        $tab = new Tab($id_tab);
        $tab->delete();

        return true;
    }

    /**
     * load dependencies
     */
    public function loadAsset()
    {
        $this->addJsDefList();

        $this->context->controller->addCSS($this->_path . 'views/css/back.css', 'all');
        $this->context->controller->addJS($this->_path . 'views/js/back.js');
        $this->context->controller->addJS(__PS_BASE_URI__.'js/tiny_mce/tiny_mce.js');
        $this->context->controller->addJS(__PS_BASE_URI__.'js/admin/tinymce.inc.js');
        $this->context->controller->addJqueryPlugin('colorpicker');
        $this->context->controller->addJqueryUI('ui.sortable');
    }

    /**
     * Check if folder img_perso is writable and executable
     *
     * @return bool
     */
    private function folderUploadFilesHasGoodRights()
    {
        return is_writable($this->folder_file_upload)
            && is_executable($this->folder_file_upload);
    }

    /**
     * @return string
     *
     * @throws PrestaShopException
     */
    public function getContent()
    {
        $this->loadAsset();

        $id_lang = $this->context->language->id;

        $currentPage = 'global';
        $getPage = Tools::getValue('page');
        if (!empty($getPage)) {
            $currentPage = $getPage;
        }

        $moduleAdminLink = Context::getContext()->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name . '&module_name=' . $this->name;

        //$allCms = CMS::listCms($id_lang);
        $allCms = $this->getAllDefaultLink($id_lang);

        $this->context->smarty->assign([
            'psr_text_color' => Configuration::get('QM_TEXT_COLOR'),
            'psr_icon_color' => Configuration::get('QM_ICON_COLOR'),
            'psr_show_text' => Configuration::get('QM_SHOW_TEXT'),
            'logo_path' => $this->logo_path,
            'languages' => Language::getLanguages(),
            'allblock' => MobilepanelActivity::getAllBlockByLang($id_lang, $this->context->shop->id),
            'allblockByShop' => MobilepanelActivity::getAllBlockByShop(),
            'moduleAdminLink' => $moduleAdminLink,
            'img_path' => $this->img_path,
            'allCms' => $allCms,
            'defaultFormLanguage' => (int) $this->context->employee->id_lang,
            'img_url' => $this->img_path,
            'folderIsWritable' => $this->folderUploadFilesHasGoodRights(),
            'folderPath' => $this->img_path_perso,
            'vecicons' => $this->getIconsVec(),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }


    public function hookActionFrontControllerSetMedia()
    {
        Media::addJsDef([
            'qm_icon_color' => Configuration::get('QM_ICON_COLOR'),
        ]);

        $this->context->controller->registerStylesheet(
            'mobilepanel-css',
            'modules/' . $this->name . '/views/css/vecmobilepanel.css'
        );
        $this->context->controller->registerJavascript(
            'mobilepanel-js',
            'modules/' . $this->name . '/views/js/vecmobilepanel.js'
        );
    }
    
    public function hookHeader(){
        $this->smarty->assign(
            array(
                'text_color' => Configuration::get('QM_TEXT_COLOR'),
            ));
        return $this->display(__FILE__, 'views/templates/hook/vecmobilepanel-header.tpl');
    }
    /**
     * @param string $hookName
     *
     * @return string
     *
     * @throws PrestaShopDatabaseException
     */
    public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('vecmobilepanel'))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId('vecmobilepanel'));
    }

    /**
     * @param string $hookName
     *
     * @return array
     *
     * @throws PrestaShopDatabaseException
     */
    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $id_lang = $this->context->language->id;
        $wishlist_url = $compare_url = '';
        if(Module::isEnabled('poswishlist')){
            $wishlist_url = $this->context->link->getModuleLink('poswishlist', 'mywishlist', array(), true);
        }
        if(Module::isEnabled('poscompare')){
            $compare_url = $this->context->link->getModuleLink('poscompare', 'comparePage', array(), true);
        }
        if(Module::isEnabled('posshoppingcart')){
            $compare_url = $this->context->link->getModuleLink('poscompare', 'comparePage', array(), true);
        }
        $blocks = MobilepanelActivity::getAllBlockByStatus($id_lang, $this->context->shop->id);
        foreach($blocks as &$block){
            if($block['icon']){
                $block['pack'] = 'pack1'; 
                if(!strpos($block['icon'],'pack1')){
                    $block['pack'] = 'pack2';
                }
            }elseif($block['custom_icon']){
                $block['pack'] = 'custom'; 
            }
        }
        return [
            'blocks' => $blocks,
            'show_text' => Configuration::get('QM_SHOW_TEXT'),
            'wishlist_url' => $wishlist_url,
            'compare_url' => $compare_url,
            'wishlist_count' => Module::isEnabled('poswishlist') ? (int)Db::getInstance()->getValue('SELECT count(id_wishlist_product) FROM '._DB_PREFIX_.'poswishlist w, '._DB_PREFIX_.'poswishlist_product wp where w.id_wishlist = wp.id_wishlist and w.id_customer='.(int)$this->context->customer->id) : 0,
            'cart_url' => $this->context->link->getPageLink('cart',null, $this->context->language->id, array('action' => 'show'), false, null)
        ];

    }

    /**
     * @throws PrestaShopException
     */
    protected function addJsDefList()
    {
        Media::addJsDef([
            'psr_icon_color' => Configuration::get('QM_ICON_COLOR'),
            'psr_text_color' => Configuration::get('QM_TEXT_COLOR'),
            'psr_controller_block_url' => $this->context->link->getAdminLink('AdminMobilepanelListing'),
            'psr_controller_block' => 'AdminMobilepanelListing',
            'psr_lang' => (int) Configuration::get('PS_LANG_DEFAULT'),
            'block_updated' => $this->trans('Block updated', [], 'Modules.Blockreassurance.Admin'),
            'active_error' => $this->trans('Oops... looks like an error occurred', [], 'Modules.Blockreassurance.Admin'),
            'min_field_error' => $this->trans('The field %field_name% is required at least in your default language.', ['%field_name%' => sprintf('"%s"', $this->trans('Title', [], 'Admin.Global'))], 'Admin.Notifications.Error'),
            'psre_success' => $this->trans('Configuration updated successfully!', [], 'Modules.Blockreassurance.Admin'),
            'successPosition' => $this->trans('Position changed successfully!', [], 'Modules.Blockreassurance.Admin'),
            'errorPosition' => $this->trans('An error occurred when switching position', [], 'Modules.Blockreassurance.Admin'),
            'txtConfirmRemoveBlock' => $this->trans('Are you sure?', [], 'Admin.Notifications.Warning'),
            'errorRemove' => $this->trans('An error occurred when removing block', [], 'Modules.Blockreassurance.Admin'),
        ]);
    }

    private function getAllDefaultLink($id_lang = null, $link = false)
    {
        if (is_null($id_lang)) $id_lang = (int)$this->context->language->id;
        $html = '<option value="PAGhomepag">'.$this->l('Homepage').'</option>';
        $html .= '<optgroup label="'.$this->l('Category').'">';
        $html .= $this->getCategoryOption(1, $id_lang, false, true, $link);
        $html .= '</optgroup>';
        $html .= '<optgroup label="'.$this->l('Cms').'">';
        $html .= $this->getCMSOptions(0, 0, $id_lang, $link);
        $html .= '</optgroup>';
        $html .= '<optgroup label="'.$this->l('Manufacturer').'">';
        $manufacturers = Manufacturer::getManufacturers(false, $id_lang);
        foreach ($manufacturers as $manufacturer)
        {
            if ($link)
                $html .= '<option value="'.$this->context->link->getManufacturerLink($manufacturer['id_manufacturer']).'">'.$manufacturer['name'].'</option>';
            else
                $html .= '<option value="MAN'.(int)$manufacturer['id_manufacturer'].'">'.$manufacturer['name'].'</option>';
        }
        $html .= '</optgroup>';
        $html .= '<optgroup label="'.$this->l('Supplier').'">';
        $suppliers = Supplier::getSuppliers(false, $id_lang);
        foreach ($suppliers as $supplier)
        {
        if ($link)
            $html .= '<option value="'.$this->context->link->getSupplierLink($supplier['id_supplier']).'">'.$supplier['name'].'</option>';
        else
            $html .= '<option value="SUP'.(int)$supplier['id_supplier'].'">'.$supplier['name'].'</option>';
        }
        $html .= '</optgroup>';
        $html .= '<optgroup label="'.$this->l('Page').'">';
        $html .= $this->getPagesOption($id_lang, $link);
        $shoplink = Shop::getShops();
        if (count($shoplink) > 1)
        {
            $html .= '<optgroup label="'.$this->l('Shops').'">';
            foreach ($shoplink as $sh)
                $html .= '<option value="SHO'.(int)$sh['id_shop'].'">'.$sh['name'].'</option>';
        }
        $html .= '</optgroup>';
        return $html;
    }
    public function getCategoryOption($id_category = 1, $id_lang = false, $id_shop = false, $recursive = true, $link = false)
    {
        $html = '';
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $id_shop = $id_shop ? (int)$id_shop : (int)Context::getContext()->shop->id;
        $category = new Category((int)$id_category, (int)$id_lang, (int)$id_shop);
        if (is_null($category->id)) return;
        if ($recursive)
        {
            $children = Category::getChildren((int)$id_category, (int)$id_lang, true, (int)$id_shop);
            $spacer = str_repeat('&nbsp;', 3 * (int)$category->level_depth);
        }
        $shop = (object)Shop::getShop((int)$category->getShopID());
        if (!in_array($category->id, array(Configuration::get('PS_HOME_CATEGORY'), Configuration::get('PS_ROOT_CATEGORY'))))
        {
        if ($link)
            $html .= '<option value="'.$this->context->link->getCategoryLink($category->id).'">'.(isset($spacer) ? $spacer : '').str_repeat('&nbsp;', 3 * (int)$category->level_depth).$category->name.'</option>';
        else
            $html .= '<option value="CAT'.(int)$category->id.'">'.str_repeat('&nbsp;', 3 * (int)$category->level_depth).$category->name.'</option>';
        }
        elseif ($category->id != Configuration::get('PS_ROOT_CATEGORY'))
            $html .= '<optgroup label="'.str_repeat('&nbsp;', 3 * (int)$category->level_depth).$category->name.'">';
        if (isset($children) && count($children))
            foreach ($children as $child)
            {
                $html .= $this->getCategoryOption((int)$child['id_category'], (int)$id_lang, (int)$child['id_shop'],
                $recursive, $link);
            }
        return $html;
    }
    
    public function getCMSOptions($parent = 0, $depth = 0, $id_lang = false, $link = false)
    {
        $html = '';
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $categories = $this->getCMSCategories(false, (int)$parent, (int)$id_lang);
        $pages = $this->getCMSPages((int)$parent, false, $id_lang);
        $spacer = str_repeat('&nbsp;', 3 * (int)$depth);
        foreach ($categories as $category)
            $html .= $this->getCMSOptions($category['id_cms_category'], (int)$depth + 1, (int)$id_lang, $link);
        foreach ($pages as $page)
            if ($link)
                $html .= '<option value="'.$this->context->link->getCMSLink($page['id_cms']).'">'.(isset($spacer) ? $spacer : '').$page['meta_title'].'</option>';
            else
                $html .= '<option value="CMS'.$page['id_cms'].'">'.$page['meta_title'].'</option>';
        return $html;
    }
    
    public function getCMSCategories($recursive = false, $parent = 1, $id_lang = false)
    {
        $categories = array();
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        if ($recursive === false)
        {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
                FROM `'._DB_PREFIX_.'cms_category` bcp
                INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
                ON (bcp.`id_cms_category` = cl.`id_cms_category`)
                WHERE cl.`id_lang` = '.(int)$id_lang.'
                AND bcp.`id_parent` = '.(int)$parent;
            return Db::getInstance()->executeS($sql);
        }
        else
        {
            $sql = 'SELECT bcp.`id_cms_category`, bcp.`id_parent`, bcp.`level_depth`, bcp.`active`, bcp.`position`, cl.`name`, cl.`link_rewrite`
                FROM `'._DB_PREFIX_.'cms_category` bcp
                INNER JOIN `'._DB_PREFIX_.'cms_category_lang` cl
                ON (bcp.`id_cms_category` = cl.`id_cms_category`)
                WHERE cl.`id_lang` = '.(int)$id_lang.'
                AND bcp.`id_parent` = '.(int)$parent;
                $results = Db::getInstance()->executeS($sql);
            foreach ($results as $result)
            {
            $sub_categories = $this->getCMSCategories(true, $result['id_cms_category'], (int)$id_lang);
            if ($sub_categories && count($sub_categories) > 0) $result['sub_categories'] = $sub_categories;
                $categories[] = $result;
            }
            return isset($categories) ? $categories : false;
        }
    }
    
    public function getCMSPages($id_cms_category, $id_shop = false, $id_lang = false)
    {
        $id_shop = ($id_shop !== false) ? (int)$id_shop : (int)Context::getContext()->shop->id;
        $id_lang = $id_lang ? (int)$id_lang : (int)Context::getContext()->language->id;
        $sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`
            FROM `'._DB_PREFIX_.'cms` c
            INNER JOIN `'._DB_PREFIX_.'cms_shop` cs
            ON (c.`id_cms` = cs.`id_cms`)
            INNER JOIN `'._DB_PREFIX_.'cms_lang` cl
            ON (c.`id_cms` = cl.`id_cms` AND cs.`id_shop` = cl.`id_shop`)
            WHERE c.`id_cms_category` = '.(int)$id_cms_category.'
            AND cl.`id_shop` = '.(int)$id_shop.'
            AND cl.`id_lang` = '.(int)$id_lang.'
            AND c.`active` = 1
            ORDER BY `position`';
        return Db::getInstance()->executeS($sql);
    }
    
    public function getPagesOption($id_lang = null, $link = false)
    {
        if (is_null($id_lang)) $id_lang = (int)$this->context->cookie->id_lang;
        $files = Meta::getMetasByIdLang($id_lang);
        $html = '';
        foreach ($files as $file)
        {
            if ($link) $html .= '<option value="'.$this->context->link->getPageLink($file['page']).'">'.(($file['title'] != '') ? $file['title'] : $file['page']).'</option>';
            else  $html .= '<option value="PAG'.$file['page'].'">'.(($file['title'] != '') ? $file['title'] :$file['page']).'</option>';
        }
        return $html;
    }
    public static function getIconsVec()
    {
        return [
            'vecicon-bag-outline' => 'bag-outline',
            'vecicon-cart-outline' => 'cart-outline',
            'vecicon-bag' => 'bag',
            'vecicon-handbag' => 'hanbag',
            'vecicon-shopping-cart' => 'shopping-cart',
            'vecicon-bag2' => 'bag2',
            'vecicon-shopping-cart-solid' => 'shopping-cart-solid',
            'vecicon-basket-outline' => 'basket-outline',
            'vecicon-shopping-basket-solid' => 'shopping-basket-solid',
            'vecicon-FullShoppingCart' => 'FullShoppingCart',
            'vecicon-ShoppingCart' => 'ShoppingCart',
            'vecicon-heart' => 'heart',
            'vecicon-heart2' => 'heart2',
            'vecicon-heart-solid' => 'heart-solid',
            'vecicon-heart-outline' => 'heart-outline',
            'vecicon-ios-heart-outline' => 'ios-heart-outline',
            'vecicon-Heart' => 'Heart',
            'vecicon-shuffle' => 'shuffle',
            'vecicon-refresh' => 'refresh',
            'vecicon-ios-shuffle' => 'ios-shuffle',
            'vecicon-ios-shuffle-strong' => 'ios-shuffle-strong',
            'vecicon-repeat-outline' => 'repeat-outline',
            'vecicon-sync-alt-solid' => 'sync-alt-solid',
            'vecicon-star2' => 'star2',
            'vecicon-star-solid' => 'star-solid',
            'vecicon-star-half-alt-solid' => 'star-half',
            'vecicon-search' => 'search',
            'vecicon-magnifier' => 'magnifier',
            'vecicon-search1' => 'search1',
            'vecicon-search2' => 'search2',
            'vecicon-Search' => 'search3',
            'vecicon-loupe' => 'loupe',
            'vecicon-eye-outline' => 'eye-outline',
            'vecicon-eye' => 'eye',
            'vecicon-look' => 'look',
            'vecicon-eye2' => 'eye2',
            'vecicon-Eye' => 'eye3',
            'vecicon-bars-solid' => 'bars-solid',
            'vecicon-menu-outline' => 'menu-outline',
            'vecicon-navicon-round' => 'navicon-round',
            'vecicon-grid-outline' => 'grid-outline',
            'vecicon-grid' => 'grid',
            'vecicon-grid2' => 'grid2',
            'vecicon-apps-outline' => 'apps-outline',
            'vecicon-apps-sharp' => 'apps-sharp',
            'vecicon-list' => 'list',
            'vecicon-list-outline' => 'list-outline',
            'vecicon-list-solid' => 'list-solid',
            'vecicon-arrow-down' => 'arrow-down',
            'vecicon-arrow-left' => 'arrow-left',
            'vecicon-arrow-right' => 'arrow-right',
            'vecicon-arrow-up' => 'arrow-up',
            'vecicon-logo-facebook' => 'logo-facebook',
            'vecicon-logo-google' => 'logo-google',
            'vecicon-logo-pinterest' => 'logo-pinterest',
            'vecicon-logo-twitter' => 'logo-twitter',
            'vecicon-logo-youtube' => 'logo-youtube',
            'vecicon-logo-instagram' => 'logo-instagram',
            'vecicon-logo-linkedin' => 'logo-linkedin',
            'vecicon-logo-rss' => 'logo-rss',
            'vecicon-logo-twitch' => 'logo-twitch',
            'vecicon-0-facebook2' => 'logo-facebook2',
            'vecicon-1-twitter2' => 'logo-twitter2',
            'vecicon-2-youtube2' => 'logo-youtube2',
            'vecicon-3-dribbble' => 'logo-dribbble',
            'vecicon-4-facebook-f' => 'logo-facebook',
            'vecicon-5-google-plus-g' => 'logo-google+',
            'vecicon-6-pinterest-p' => 'logo-pinterest',
            'vecicon-logo-tiktok' => 'logo-tiktok',
            'vecicon-close-outline' => 'close-outline',
            'vecicon-close-circle' => 'close-circle',
            'vecicon-close-circle-outline' => 'close-circle-outline',
            'vecicon-person-outline' => 'person-outline',
            'vecicon-person-circle-outline' => 'person-circle-outline',
            'vecicon-user' => 'user',
            'vecicon-user-circle' => 'user-circle',
            'vecicon-call-outline' => 'call-outline',
            'vecicon-call-sharp' => 'call-sharp',
            'vecicon-headphones' => 'headphones',
            'vecicon-headphones-mic' => 'headphones-mic',
            'vecicon-headphones2' => 'headphones2',
            'vecicon-phone-volume-solid' => 'phone-volume-solid',
            'vecicon-phone-call' => 'phone-call',
            'vecicon-headset-outline' => 'headset-outline',
            'vecicon-settings-outline' => 'settings-outline',
            'vecicon-settings' => 'settings',
            'vecicon-shipping-fast-solid' => 'shipping-fast-solid',
            'vecicon-truck-solid' => 'truck-solid',
            'vecicon-ship-solid' => 'ship-solid',
            'vecicon-boat-outline' => 'boat-outline',
            'vecicon-rocket' => 'rocket',
            'vecicon-rocket-outline' => 'rocket-outline',
            'vecicon-location-pin' => 'location-pin',
            'vecicon-map-marked-alt-solid' => 'map-marked-alt-solid',
            'vecicon-location-outline' => 'location-outline',
            'vecicon-mail-outline' => 'mail-outline',
            'vecicon-mail-open-outline' => 'mail-open-outline',
            'vecicon-globe-solid' => 'globe-solid',
            'vecicon-globe-alt' => 'globe-alt',
            'vecicon-scan-outline' => 'scan-outline',
            'vecicon-expand-outline' => 'expand-outline',
            'vecicon-checkmark' => 'checkmark',
            'vecicon-card-outline' => 'card-outline',
            'vecicon-credit-card-solid' => 'credit-card-solid',
            'vecicon-money-bill-wave-solid' => 'money-bill-wave-solid',
            'vecicon-cash-outline' => 'cash-outline',
            'vecicon-gift-outline' => 'gift-outline',
            'vecicon-gift-solid' => 'gift-solid',
            'vecicon-gifts-solid' => 'gifts-solid',
            'vecicon-ribbon-outline' => 'ribbon-outline',
            'vecicon-time-outline' => 'time-outline',
            'vecicon-help-buoy-outline' => 'help-buoy-outline',
            'vecicon-exchange-1' => 'exchange-1',
            'vecicon-call-center-24-7' => 'call-center-24-7',
            'vecicon-headphone-24-7' => 'headphone-24-7',
            'vecicon-credit-card-secure1' => 'credit-card-secure1',
            'vecicon-payment-security' => 'payment-security',
            'vecicon-logo-vk' => 'logo-vk',
            'vecicon-logo-whatsapp' => 'logo-whatsapp',
            'vecicon-logo-telegram' => 'logo-telegram',
            'vecicon-play-outline' => 'play-outline',
            'vecicon-videocam-outline' => 'videocam-outline',
            'vecicon-play-circle' => 'play-circle',
            'vecicon-home-outline' => 'home-outline',
            'vecicon-diamond-outline' => 'diamond-outline',
            'vecicon-camera-outline' => 'camera-outline',
            'vecicon-fitness-outline' => 'fitness-outline',
            'vecicon-medkit-outline' => 'medkit-outline',
            'vecicon-nuclear-outline' => 'nuclear-outline',
            'vecicon-shirt-outline' => 'shirt-outline',
            'vecicon-watch-outline' => 'watch-outline',
            'vecicon-couch-solid' => 'couch-solid',
            'vecicon-filter-outline' => 'filter-outline',
            'vecicon-options-outline' => 'options-outline',
            'vecicon-chatbox-outline' => 'chatbox-outline',
            'vecicon-chatbubble-outline' => 'chatbubble-outline',
            'vecicon-logo-behance' => 'logo-behance',
            'vecicon-logo-flickr' => 'logo-flickr',
            'vecicon-logo-foursquare' => 'logo-foursquare',
            'vecicon-logo-github' => 'logo-github',
            'vecicon-logo-skype' => 'logo-skype',
            'vecicon-logo-snapchat' => 'logo-snapchat',
            'vecicon-logo-soundcloud' => 'logo-soundcloud',
            'vecicon-logo-tumblr' => 'logo-tumblr',
            'vecicon-logo-vimeo' => 'logo-vimeo',
            'vecicon-logo-wechat' => 'logo-wechat',
            'vecicon-logo-weibo' => 'logo-weibo',
            'vecicon-add-outline' => 'add-outline',
            'vecicon-remove-outline' => 'remove-outline',
            'vecicon-left-quotes-sign' => 'left-quotes-sign',
            'vecicon-right-quotes-symbol' => 'right-quotes-symbol',
            'vecicon-arrow-left-solid' => 'arrow-left-solid',
            'vecicon-arrow-right-solid' => 'arrow-right-solid',
            'vecicon-cauliflower' => 'cauliflower',
            'vecicon-bread' => 'bread',
            'vecicon-meat' => 'meat',
            'vecicon-anise-candy' => 'anise-candy',
            'vecicon-snowflake' => 'snowflake',
            'vecicon-coffee-cup' => 'coffee-cup',
            'vecicon-almond' => 'almond',
            'vecicon-egg' => 'egg',
            'vecicon-MusicMixer' => 'MusicMixer',
            'vecicon-Blog' => 'Blog',
            'vecicon-Agenda' => 'Agenda',
            'vecicon-Edit' => 'Edit',
            'vecicon-Settings' => 'Settings',
            'vecicon-Mouse' => 'Mouse',
            'vecicon-android-arrow-dropleft-circle' => 'android-arrow-dropleft-circle',
            'vecicon-android-arrow-dropright-circle' => 'android-arrow-dropright-circle',
            
        ];
    }
}
