<?php
 
/**
* 2010-2016 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through this link for complete license : https://store.webkul.com/license.html
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to https://store.webkul.com/customisation-guidelines/ for more information.
*
*  @author    Webkul IN <support@webkul.com>
*  @copyright 2010-2016 Webkul IN
*  @license   https://store.webkul.com/license.html
*/


require_once( EW_MODULE_DIR . '/classes/JoinCombinations.php');
//require_once( _MODULE_DIR_ . 'ewphotocustomizer/classes/JoinCombinations.php');


if (!defined('_PS_VERSION_'))
    exit;

class EwphotocustomizerAdminProductsCombinationModuleFrontController extends ModuleFrontController
{

    public function __construct()
    {
        parent::__construct();
        $this->context = Context::getContext();
    }


    public function initContent()
    {
       $this->ajax = true;
       parent::initContent();
    }


    public function postProcess()
    {
        parent::postProcess();
        $action = Tools::getValue('action');

        switch ($action) {
            case "addProduct":
            case "addCombo":
            case "addElement":
                $jc = new JoinCombinations();
                $jc->product_id = Tools::getValue('product_id');
                $jc->product_attribute_id = Tools::getValue('combination_id');
                $jc->id_parent = Tools::getValue('id_parent');
                $jc->id_child = Tools::getValue('id_child');
                $jc->save();

                if ("addCombo" == $action) {
                    exit(Tools::JsonEncode(\Db::getInstance()->Insert_ID()));
                } else
                if ("addElement" == $action) {

                    $this->context->smarty->fetch(EW_MODULE_DIR . "/views/templates/hook/include/adminElements.tpl");
                    //exit(Tools::JsonEncode("reload"));
                } else {
                    exit(Tools::JsonEncode("reload"));
                }
                break;

            case "saveProduct":
                $jc = new JoinCombinations(Tools::getValue('id_jc'));
                $jc->product_id = Tools::getValue('product_id');
                $jc->product_attribute_id = Tools::getValue('combination_id');
                $jc->id_parent = Tools::getValue('id_parent');
                $jc->id_child = Tools::getValue('id_child');
                $jc->id_combo = Tools::getValue('id_combo');
                $jc->quantity = 1; // default
                $jc->price = Tools::getValue('price');
                $jc->discount = Tools::getValue('discount');
                $jc->discount_type = (empty(Tools::getValue('discount_type'))) ? "%" : Tools::getValue('discount_type');
                $jc->is_default = ( Tools::getValue('isdefault') == "false" ) ? 0 : 1;
                $jc->is_exclusive = ( Tools::getValue('isexclusive') == "false" ) ? 0 : 1;
                $jc->save();

                exit(Tools::JsonEncode(\Db::getInstance()->Insert_ID()));
                break;

            case "addComboCombination":
                $jc = new JoinCombinations();
                $jc->product_id = Tools::getValue('product_id');
                $jc->product_attribute_id = Tools::getValue('combination_id');
                $jc->id_parent = Tools::getValue('id_parent');
                $jc->id_child = Tools::getValue('id_child');
                $jc->id_combo = Tools::getValue('id_combo');
                $jc->quantity = 1; // default
                $jc->discount_type = "%";
                $jc->save();
                exit(Tools::JsonEncode("reload"));
                break;

            case "removeBaseProduct":
                JoinCombinations::removeSubProduct(Tools::getValue('product_id'), 
                                                    Tools::getValue('combination_id'), 
                                                    Tools::getValue('id_parent'));
                exit(Tools::JsonEncode("reload"));
                break;

            case "removeComboProduct":
                $idCombo = (Tools::getValue('id_combo') == 0) ? null :Tools::getValue('id_combo');
                JoinCombinations::removeSubProduct(Tools::getValue('product_id'), 
                                                    Tools::getValue('combination_id'), 
                                                    Tools::getValue('id_parent'),
                                                    Tools::getValue('id_child'),
                                                    $idCombo );
                exit(Tools::JsonEncode("reload"));
                break;
        }

        die(Tools::JsonEncode(false));
    }


}