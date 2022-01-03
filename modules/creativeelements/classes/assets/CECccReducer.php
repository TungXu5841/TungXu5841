<?php
/**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   One domain support license
 */

defined('_PS_VERSION_') or exit;

class CECccReducer extends CccReducer
{
    public function reduceCss($cssFileList)
    {
        return empty($cssFileList['external']) ? $cssFileList : parent::reduceCss($cssFileList);
    }
}
