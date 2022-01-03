<?php
/**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   One domain support license
 */

defined('_PS_VERSION_') or die;

class CEDatabase
{
    private static $hooks = [
        'displayBackOfficeHeader',
        'displayHeader',
        'displayFooterProduct',
        'overrideLayoutTemplate',
        'CETemplate',
        // Actions
        'actionObjectCERevisionDeleteAfter',
        'actionObjectCETemplateDeleteAfter',
        'actionObjectCEThemeDeleteAfter',
        'actionObjectCEContentDeleteAfter',
        'actionObjectProductDeleteAfter',
        'actionObjectCategoryDeleteAfter',
        'actionObjectManufacturerDeleteAfter',
        'actionObjectSupplierDeleteAfter',
        'actionObjectCmsDeleteAfter',
        'actionObjectCmsCategoryDeleteAfter',
        'actionObjectYbc_blog_post_classDeleteAfter',
        'actionObjectXipPostsClassDeleteAfter',
        'actionObjectStBlogClassDeleteAfter',
        'actionObjectBlogPostsDeleteAfter',
        'actionObjectNewsClassDeleteAfter',
        'actionObjectTvcmsBlogPostsClassDeleteAfter',
        'actionProductAdd',
    ];

    public static function initConfigs()
    {
        $defaults = [
            // General
            'elementor_frontend_edit' => 1,
            'elementor_max_revisions' => 10,
            // Style
            'elementor_default_generic_fonts' => 'sans-serif',
            'elementor_container_width' => 1140, //update it using themeoptions
            'elementor_space_between_widgets' => 20,
            'elementor_page_title_selector' => 'header.page-header h1',
            'elementor_page_wrapper_selector' => '#wrapper, #wrapper .container, #content'
            ,
            'elementor_viewport_lg' => 1025,
            'elementor_viewport_md' => 768,
        ];
        foreach ($defaults as $key => $value) {
            Configuration::hasKey($key) or Configuration::updateValue($key, $value);
        }
    }

    public static function createTables()
    {
        $db = Db::getInstance();
        $ce_revision = _DB_PREFIX_ . 'ce_revision';
        $ce_template = _DB_PREFIX_ . 'ce_template';
        $ce_content = _DB_PREFIX_ . 'ce_content';
        $ce_theme = _DB_PREFIX_ . 'ce_theme';
        $ce_meta = _DB_PREFIX_ . 'ce_meta';
        $engine = _MYSQL_ENGINE_;

        return $db->execute("
            CREATE TABLE IF NOT EXISTS `$ce_revision` (
                `id_ce_revision` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `parent` bigint(20) UNSIGNED NOT NULL,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `title` varchar(255) NOT NULL,
                `type` varchar(64) NOT NULL DEFAULT '',
                `content` longtext NOT NULL,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_revision`),
                KEY `id` (`parent`),
                KEY `date_add` (`date_upd`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `$ce_template` (
                `id_ce_template` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `title` varchar(128) NOT NULL DEFAULT '',
                `type` varchar(64) NOT NULL DEFAULT '',
                `content` longtext,
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_template`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `$ce_content` (
                `id_ce_content` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `title` varchar(128) NOT NULL DEFAULT '',
                `id_product` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `hook` varchar(64) NOT NULL DEFAULT '',
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_content`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `{$ce_content}_shop` (
                `id_ce_content` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL,
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_content`,`id_shop`),
                KEY `id_shop` (`id_shop`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `{$ce_content}_lang` (
                `id_ce_content` int(10) UNSIGNED NOT NULL,
                `id_lang` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL DEFAULT 1,
                `content` longtext,
                PRIMARY KEY (`id_ce_content`,`id_shop`,`id_lang`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `$ce_theme` (
                `id_ce_theme` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_employee` int(10) UNSIGNED NOT NULL,
                `title` varchar(128) NOT NULL DEFAULT '',
                `type` varchar(64) NOT NULL DEFAULT '',
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_theme`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `{$ce_theme}_shop` (
                `id_ce_theme` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL,
                `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
                `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_ce_theme`,`id_shop`),
                KEY `id_shop` (`id_shop`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `{$ce_theme}_lang` (
                `id_ce_theme` int(10) UNSIGNED NOT NULL,
                `id_lang` int(10) UNSIGNED NOT NULL,
                `id_shop` int(10) UNSIGNED NOT NULL DEFAULT 1,
                `content` text,
                PRIMARY KEY (`id_ce_theme`,`id_shop`,`id_lang`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ") && $db->execute("
            CREATE TABLE IF NOT EXISTS `$ce_meta` (
                `id_ce_meta` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
                `name` varchar(255) DEFAULT NULL,
                `value` longtext,
                PRIMARY KEY (`id_ce_meta`),
                KEY `id` (`id`),
                KEY `name` (`name`)
            ) ENGINE=$engine DEFAULT CHARSET=utf8;
        ");
    }

    public static function updateTabs()
    {
        $id = (int) Tab::getIdFromClassName('IMPROVE');

        try {
            $pos = $id ? 1 : Tab::getInstanceFromClassName('AdminParentModules')->position;
            $parent = self::updateTab($id, $pos, 'AdminParentCEContent', true, 'V-Elements', 'ce');

            self::updateTab($parent->id, 1, 'AdminCEHeader', true, 'Header Builder');
            self::updateTab($parent->id, 2, 'AdminCEHome', true, 'Home Builder');
            self::updateTab($parent->id, 3, 'AdminCEFooter', true, 'Footer Builder');
            self::updateTab($parent->id, 4, 'AdminCEContent', true, 'Content to hooks');
            self::updateTab($parent->id, 5, 'AdminCESettings', false, 'Settings');
            self::updateTab($parent->id, 6, 'AdminCEEditor', false, 'Live Editor');
        } catch (Exception $ex) {
            return false;
        }

        return true;
    }

    protected static function updateTab($id_parent, $position, $class, $active, $name, $icon = '')
    {
        $id = (int) Tab::getIdFromClassName($class);
        $tab = new Tab($id);
        $tab->id_parent = $id_parent;
        $tab->position = (int) $position;
        $tab->module = 'creativeelements';
        $tab->class_name = $class;
        $tab->active = $active;
        $tab->icon = $icon;
        $tab->name = [];

        foreach (Language::getLanguages(false) as $lang) {
            $tab->name[$lang['id_lang']] = $name;
        }

        if (!$tab->save()) {
            throw new Exception('Can not save Tab: ' . $class);
        }

        if (!$id && $tab->position != $position) {
            $tab->position = (int) $position;
            $tab->update();
        }

        return $tab;
    }

    public static function getHooks($all = true)
    {
        $hooks = self::$hooks;

        if ($all) {
            $ce_content = _DB_PREFIX_ . 'ce_content';
            $rows = Db::getInstance()->executeS("SELECT DISTINCT hook FROM $ce_content");

            if (!empty($rows)) {
                foreach ($rows as &$row) {
                    $hook = $row['hook'];

                    if ($hook && !in_array($hook, $hooks)) {
                        $hooks[] = $hook;
                    }
                }
            }
        }
        return $hooks;
    }
}
