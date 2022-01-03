<?php
/**
 * Creative Elements - live PageBuilder
 *
 * @author    WebshopWorks
 * @copyright 2019-2021 WebshopWorks.com
 * @license   One domain support license
 */

defined('_PS_VERSION_') or die;

require_once _PS_MODULE_DIR_ . 'creativeelements/classes/CEContent.php';

class AdminCEContentController extends ModuleAdminController
{
    public $bootstrap = true;

    public $table = 'ce_content';

    public $identifier = 'id_ce_content';

    public $className = 'CEContent';

    public $lang = true;

    protected $_defaultOrderBy = 'title';

    public function __construct()
    {
        parent::__construct();

        if ((Tools::getIsset('updatece_content') || Tools::getIsset('addce_content')) && Shop::getContextShopID() === null) {
            $this->displayWarning(
                $this->trans('You are in a multistore context: any modification will impact all your shops, or each shop of the active group.', [], 'Admin.Catalog.Notification')
            );
        }

        $table_shop = _DB_PREFIX_ . $this->table . '_shop';
        $this->_select = 'sa.*';
        $this->_join = "LEFT JOIN $table_shop sa ON sa.id_ce_content = a.id_ce_content AND b.id_shop = sa.id_shop";
        $this->_where = "AND sa.id_shop = " . (int) $this->context->shop->id . " AND a.id_product = 0";

        $this->fields_list = [
            'id_ce_content' => [
                'title' => $this->trans('ID', [], 'Admin.Global'),
                'class' => 'fixed-width-xs',
                'align' => 'center',
            ],
            'title' => [
                'title' => $this->trans('Title', [], 'Admin.Global'),
            ],
            'hook' => [
                'title' => $this->trans('Hook', [], 'Admin.Global'),
                'class' => 'fixed-width-xl',
            ],
            'active' => [
                'title' => $this->trans('Displayed', [], 'Admin.Global'),
                'filter_key' => 'sa!active',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
            ],
        ];

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->trans('Delete selected', [], 'Admin.Notifications.Info'),
                'icon' => 'fa fa-icon-trash',
                'confirm' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Info'),
            ],
        ];
    }

    public function ajaxProcessHideEditor()
    {
        $id = (int) Tools::getValue('id');
        $id_type = (int) Tools::getValue('idType');

        $uids = CE\UId::getBuiltList($id, $id_type, $this->context->shop->id);
        $res = empty($uids) ? $uids : array_keys($uids[$this->context->shop->id]);

        die(json_encode($res));
    }

    public function ajaxProcessMigrate()
    {
        if ($ids = Tools::getValue('ids')) {
            require_once _CE_PATH_ . 'classes/CEMigrate.php';

            $done = [];

            foreach ($ids as $id) {
                CEMigrate::moveContent($id, $this->module) && $done[] = (int) $id;
            }
            $res = CEMigrate::removeIds('content', $done);

            die(json_encode($res));
        }
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->addJquery();
        $this->js_files[] = _MODULE_DIR_ . 'creativeelements/views/lib/e-select2/js/e-select2.full.min.js?v=4.0.6-rc.1';
        $this->css_files[_MODULE_DIR_ . 'creativeelements/views/lib/e-select2/css/e-select2.min.css?v=4.0.6-rc.1'] = 'all';
    }

    public function initToolBarTitle()
    {
        $this->page_header_toolbar_title = $this->l('Place Content Anywhere');

        $this->context->smarty->assign('icon', 'icon-list');

        $this->toolbar_title[] = $this->l('Contents List');
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['addce_content'] = [
                'href' => self::$currentIndex . '&addce_content&token=' . $this->token,
                'desc' => $this->trans('Add new', [], 'Admin.Actions'),
                'icon' => 'process-icon-new',
            ];
        }
        parent::initPageHeaderToolbar();
    }

    public function initContent()
    {
        $this->context->smarty->assign('current_tab_level', 3);

        return parent::initContent();
    }

    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    public function renderForm()
    {
        $col = count(Language::getLanguages(false, false, true)) > 1 ? 9 : 7;

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Content'),
                'icon' => 'icon-edit',
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->trans('Title', [], 'Admin.Global'),
                    'name' => 'title',
                    'col' => 6,
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Hook', [], 'Admin.Global'),
                    'name' => 'hook',
                    'required' => true,
                    'col' => 3,
                ],
                [
                    'type' => 'textarea',
                    'label' => $this->l('Content'),
                    'name' => 'content',
                    'lang' => true,
                    'col' => $col,
                ],
                [
                    'type' => 'switch',
                    'label' => $this->trans('Displayed', [], 'Admin.Global'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->trans('Enabled', [], 'Admin.Global'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->trans('Disabled', [], 'Admin.Global'),
                        ],
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save', [], 'Admin.Actions'),
            ],
            'buttons' => [
                'save_and_stay' => [
                    'type' => 'submit',
                    'title' => $this->trans('Save and stay', [], 'Admin.Actions'),
                    'icon' => 'process-icon-save',
                    'name' => 'submitAddce_contentAndStay',
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];

        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = [
                'type' => 'shop',
                'label' => $this->trans('Shop association', [], 'Admin.Global'),
                'name' => 'checkBoxShopAsso',
            ];
        }

        return parent::renderForm();
    }

    protected function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        return empty($this->translator) ? $this->l($id) : parent::trans($id, $parameters, $domain, $locale);
    }

    protected function l($string, $module = 'creativeelements', $addslashes = false, $htmlentities = true)
    {
        $str = Translate::getModuleTranslation($module, $string, '', null, $addslashes || !$htmlentities);

        return $htmlentities ? $str : call_user_func('stripslashes', $str);
    }
}
