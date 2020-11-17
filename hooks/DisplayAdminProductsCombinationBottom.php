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


trait DisplayAdminProductsCombinationBottom
{
    public function hookDisplayAdminProductsCombinationBottom(array $params)
    {
        $id_product = $params["id_product"];
        $idCombination = $params["id_product_attribute"];
        $product = new Product($id_product);
        $category = new Category($product->id_category_default);

        $configuration = ProductConfigurator::getConfigurationByIdProduct($id_product);
        $is_customizable = false;
        if ($configuration != false) {
            $is_customizable = $configuration->is_customizable;
        }

        // recupero la lista dei prodotti base 
        $basicList = ProductConfigurator::getBasicProducts();

        $listaProdottiBase = array();
        $listaComboProdottiBase = array();
        $listaComboProdottiCombo = array();
        $listaCPC = array();

        foreach ($basicList as $el) {

            if ($is_customizable) {
                $product = new Product($el["product_id"]);
                $listaComboProdottiBase = JoinCombinations::getChildCombination($product, $id_product, $idCombination);
                $listaProdottiBase[] = [
                    "id" =>  $product->id,
                    "name" => Product::getProductName($product->id, null, $this->context->language->id),
                    "is_checked" => JoinCombinations::checkJoinProduct($id_product, $idCombination, $product->id, 0, 0),
                    "options" => JoinCombinations::getJoinCobinationByCombo($id_product, $idCombination, $product->id, 0, 0),
                    "listaComboProdottiBase" => $listaComboProdottiBase,
                ];
            }
        }

        // controllo se la combinazione è impostata come custom
        $query = new DbQuery();
        $query->select("is_formato_custom, price_mq");
        $query->from("ew_formati_custom");
        $query->where("product_id = $id_product");
        $query->where("product_attribute_id = $idCombination");
        $rslt = Db::getInstance()->executeS($query);

        $this->context->smarty->assign([
            "BaseUrl" => SessionManager::getBaseUrl(),
            "Token" => Tools::getAdminTokenLite('AdminProductsCombination'),
            "RequestUri" => $_SERVER['REQUEST_URI'],
            "IsCustomizabled" => $is_customizable,
            "IdProduct" => $id_product,
            "IdAttribute" => $idCombination,
            "IdCategoryOfProduct" => $category->id_category,
            "IdCategoryParent" => $category->id_parent,
            "IdCategoryOfElements" => Configuration::get('EWPHOTOCSTM_ELEMENTS_CATEGORY'),
            "ListOfBasicProducts" => $listaProdottiBase,
            "ListOfComboProductCombo" => $listaComboProdottiCombo,
            "ListFormatoCustom" => array("NO" => 0, "SI" => 1),
            "IsFormatoCustom" => (is_null($rslt[0]["is_formato_custom"]) ? 0 : 1),
            "PriceMqFormatoCustom" => (is_null($rslt[0]["price_mq"]) ? "0,000000" : str_replace(".", ",", $rslt[0]["price_mq"])),
        ]);


        return $this->display(
            $this->file,
            "displayAdminProductsCombinationBottom.tpl"
        );
    }

}