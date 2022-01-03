<?php
/**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   One domain support license
 */

defined('_PS_VERSION_') or die;

require_once _PS_MODULE_DIR_ . 'creativeelements/classes/CETemplate.php';

class AdminCETemplatesController extends ModuleAdminController
{
    public $bootstrap = true;

    public $table = 'ce_template';

    public $identifier = 'id_ce_template';

    public $className = 'CETemplate';

    protected $_defaultOrderBy = 'title';

    public function __construct()
    {
        parent::__construct();
    }

    public function processBulkExport()
    {
        $uids = [];

        foreach ($this->boxes as $id) {
            $uids[] = new CE\UId($id, CE\UId::TEMPLATE);
        }

        CE\Plugin::instance()->templates_manager->getSource('local')->exportMultipleTemplates($uids);
    }

    protected function processUpdateOptions()
    {
        // Process import template
        CE\UId::$_ID = new CE\UId(0, CE\UId::TEMPLATE);

        $res = CE\Plugin::instance()->templates_manager->directImportTemplate();

        if ($res instanceof CE\WPError) {
            $this->errors[] = $res->getMessage();
        } elseif (isset($res[1]['template_id'])) {
            // More templates
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminCETemplates') . '&conf=18');
        } elseif (isset($res[0]['template_id'])) {
            // Simple template
            $id = Tools::substr($res[0]['template_id'], 0, -6);

            Tools::redirectAdmin(
                $this->context->link->getAdminLink('AdminCETemplates') . "&id_ce_template=$id&updatece_template&conf=18"
            );
        } else {
            $this->errors[] = $this->l('Unknown error during import!');
        }
    }

    public function ajaxProcessMigrate()
    {
        if ($ids = Tools::getValue('ids')) {
            require_once _CE_PATH_ . 'classes/CEMigrate.php';

            $done = [];

            foreach ($ids as $id) {
                CEMigrate::moveTemplate($id) && $done[] = (int) $id;
            }
            $res = CEMigrate::removeIds('template', $done);

            die(json_encode($res));
        }
    }
}
