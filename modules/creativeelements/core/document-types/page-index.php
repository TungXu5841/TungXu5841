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

use CE\CoreXBaseXThemePageDocument as ThemePageDocument;

class CoreXDocumentTypesXPageIndex extends ThemePageDocument
{
    public function getName()
    {
        return 'page-index';
    }

    public static function getTitle()
    {
        return __('Home Page');
    }

    protected function getRemoteLibraryConfig()
    {
        $config = parent::getRemoteLibraryConfig();

        $config['type'] = 'page';

        return $config;
    }
}
