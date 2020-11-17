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
require_once( EW_MODULE_DIR . '/classes/ProductConfigurator.php');


if (!defined('_PS_VERSION_'))
    exit;

class EwphotocustomizerAjaxElementsFinderModuleFrontController extends ModuleFrontController
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
        $context = \Context::getContext();
        $langId = $context->language->id;

        $search = Tools::getValue('search');
        $product_id = Tools::getValue('product_id');
        $combination_id = Tools::getValue('combination_id');
        $id_parent = Tools::getValue('id_parent');
        $id_child = Tools::getValue('id_child');
        
        $listOfSecondaryElements = array();

        // selezione di tutte le combinazioni dei prodotti secondari
        foreach (ProductConfigurator::getSecondaryProducts() as $el) {
            $scndProd = new \Product($el['product_id'], false, $langId);
            $name = $scndProd->name;
            
            foreach ($scndProd->getAttributeCombinations($langId) as $combo) {

                $name = $scndProd->name . " " . $combo["group_name"] . " " . $combo["attribute_name"];
                $check = JoinCombinations::checkJoinProduct($product_id, $combination_id, $id_parent, $id_child, $combo['id_product_attribute']);

                if (preg_match("/$search/i", $name) && !$check) {
                    $listOfSecondaryElements[] = [
                        "id" => $combo['id_product_attribute'],
                        "text" => $scndProd->name .  " - " . $combo["group_name"] . ": " . $combo["attribute_name"],
                        "idProduct" => $combo['id_product']
                    ];
                }
            }
        }

        exit(Tools::JsonEncode($listOfSecondaryElements));
    }

}