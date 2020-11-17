<?php
/**
* 2007-2020 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once "requires.php";

class Ewphotocustomizer extends Module
{
    // TPL Parts
    use DisplayProductAdditionalInfo;
    use DisplayAdminProductsExtra;
    use DisplayAdminProductsCombinationBottom;

    // Actions
    use ActionProductSave;
    use ActionFrontControllerSetMedia;

    protected $config_form = false;
    protected $file = "";

    public function __construct()
    {
        $this->name = 'ewphotocustomizer';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Elwood Agency';
        $this->need_instance = 0;
        $this->_path = _MODULE_DIR_ . $this->name . "/";

        $this->file = __FILE__;
        define("EW_MODULE_NAME", "ewphotocustomizer");

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Photo Customizer');
        $this->description = $this->l('');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        // Context::getContext()->link->getModuleLink('ew_xerjoff_kits', 'SampleKit');
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('actionProductSave') &&
            $this->registerHook('actionProductDelete') &&
            $this->registerHook('actionFrontControllerSetMedia') &&
            $this->registerHook('displayFooter') &&
            $this->registerHook('displayAdminProductsExtra') &&
            $this->registerHook('displayProductAdditionalInfo') &&
            $this->registerHook('displayAdminProductsCombinationBottom');
    }

    public function uninstall()
    {
        //Configuration::deleteByName('EWPHOTOCUSTOMIZER_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitEwphotocustomizerModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitEwphotocustomizerModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $configForm = $this->getConfigForm();

        return $helper->generateForm(array($configForm));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'EWPHOTOCSTM_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),

                    array(
                        'type' => 'text',
                        'prefix' => 'ID',
                        'label' => $this->l('Category of elements'),
                        'name' => 'EWPHOTOCSTM_ELEMENTS_CATEGORY',
                        'required' => true,
                        'size' => 6,
                        'class' => 'col-lg-1'
                    ),
                    
                    
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'EWPHOTOCSTM_LIVE_MODE' => Configuration::get('EWPHOTOCUSTOMIZER_LIVE_MODE', true),
            'EWPHOTOCSTM_ELEMENTS_CATEGORY' => Configuration::get('EWPHOTOCSTM_ELEMENTS_CATEGORY'),

        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/jquery.js');
        $this->context->controller->addCSS($this->_path.'views/css/select2.min.css');
        $this->context->controller->addJS($this->_path.'views/js/select2.full.min.js');
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        // add Pixie photo framework lib css
        $this->context->controller->addCSS($this->_path .'views/css/front.css');
        $this->context->controller->addCSS($this->_path .'lib/pixie/pixie-styles.min.css');
        $this->context->controller->addJS($this->_path . 'views/js/sweetalert.min.js');
    }


    public function hookFooter()
    {
        // add Pixie photo framework lib js
        //$this->context->controller->addJS($this->_path.'/lib/pixie/scripts.min.js');
    }

}
