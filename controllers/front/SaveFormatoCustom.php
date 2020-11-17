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

if (!defined('_PS_VERSION_'))
    exit;

class EwphotocustomizerSaveFormatoCustomModuleFrontController extends ModuleFrontController
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

        $product_id = Tools::getValue('product_id');
        $product_attribute_id = Tools::getValue('product_attribute_id');
        $is_formato_custom = (bool) Tools::getValue('is_formato_custom');
        $price_mq = str_replace(",", ".", Tools::getValue('price_mq'));

        $query = new DbQuery();
        $query->select("product_attribute_id");
        $query->from("ew_formati_custom");
        $query->where("product_id = $product_id");
        $query->where("product_attribute_id = $product_attribute_id");
        $rslt = Db::getInstance()->getValue($query);

        if ($is_formato_custom) {
            if ($rslt != false) {
                // update
                $update =
                        "UPDATE `" . _DB_PREFIX_ . "ew_formati_custom` " .
                        "SET `is_formato_custom` = $is_formato_custom, `price_mq` = '$price_mq' " .
                        "WHERE product_id = $product_id AND product_attribute_id = $product_attribute_id";
                Db::getInstance()->execute($update);
            } else {
                // insert
                $insert =
                        "INSERT INTO `" . _DB_PREFIX_ . "ew_formati_custom` " .
                        "(`product_id`, `product_attribute_id`, `is_formato_custom`, `price_mq`) " .
                        "VALUES ($product_id, $product_attribute_id, $is_formato_custom, '$price_mq')";
                Db::getInstance()->execute($insert);
            }
        } else {
            Db::getInstance()->delete('ew_formati_custom', "product_id = $product_id AND product_attribute_id = $product_attribute_id", 1);
        }
    }


}