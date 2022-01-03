<?php
/**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks, Elementor
 * @copyright 2019-2021 WebshopWorks.com & Elementor.com
 * @license   https://www.gnu.org/licenses/gpl-3.0.html
 */

namespace CE;

defined('_PS_VERSION_') or die;

use CE\CoreXBaseXThemeDocument as ThemeDocument;
use CE\CoreXDocumentTypesXPost as Post;
use CE\TemplateLibraryXSourceLocal as SourceLocal;

abstract class CoreXBaseXThemePageDocument extends ThemeDocument
{
    public function getCssWrapperSelector()
    {
        return 'body.elementor-page-' . $this->getMainId();
    }

    protected function _registerControls()
    {
        parent::_registerControls();

        Post::registerPostFieldsControl($this);

        Post::registerStyleControls($this);
    }
}
