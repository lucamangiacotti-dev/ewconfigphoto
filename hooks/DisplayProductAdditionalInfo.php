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

if (!defined('_PS_VERSION_'))
    exit;

trait DisplayProductAdditionalInfo
{
    public function hookDisplayProductAdditionalInfo(array $params)
    {
        $id_product = $params['product']->getId();
        // configuratore prodotto
        $configuration = ProductConfigurator::getConfigurationByIdProduct($id_product);

        if ($configuration->is_customizable == true) {

            // Customer
            $photoList = array();
            $customer = Context::getContext()->customer;
            $customer_id = 0;
            if (!empty($customer->id)) {
                $photoList = $this::getPhotosByCustomer($customer->id);
                $customer_id = $customer->id;
            }
            $product = new Product($id_product);

            $this->context->smarty->assign([
                "BaseUrl" => SessionManager::getBaseUrl(),
                //"Token" => Tools::getAdminTokenLite('AdminProductsCombination'),
                "RequestUri" => $_SERVER['REQUEST_URI'],
                "ModulePath" => $this->_path,
                "CustomerPhotos" => $photoList,
                "ListaFormati" => self::getFormati($product), 
                "ProuctPrice" => $product->getPrice(),
                "CustomerID" => $customer_id,
                "TaxRate" => Tax::getProductTaxRate($product->id),
            ]);

            return $this->display(
                $this->file,
                "displayProductAdditionalInfo.tpl"
            );
        } 
    }

    public static function getPhotosByCustomer($id_customer)
    {
        $query = new DbQuery();
        $query->select("*");
        $query->from("ewcustomerphotogallery");
        $query->where("id_customer = $id_customer");

        $result = Db::getInstance()->executeS($query);
        return $result;
    }

    public static function getFormati($product)
    {
        $formati = array();
        $combinations = $product->getAttributeCombinations();

        
        
        foreach ($combinations as $attr) {
            if (strtolower($attr["group_name"]) == "formato") {
                //var_dump($attr);
                $formatoCustom = SessionManager::getPriceOfFormatoCustom($product->id, $attr["id_product_attribute"]);

                $formati[$attr["id_product_attribute"]] = [
                    "formato_id" => $attr["id_product_attribute"],
                    "formato_full" => $attr["attribute_name"],
                    "formato_small" => trim(str_replace("cm", "", $attr["attribute_name"])),
                    "formato_prezzo" => Product::getPriceStatic($product->id, false, $attr["id_product_attribute"]),
                    "formato_is_custom" => (is_null($formatoCustom[0]["is_formato_custom"]) ? 0 : 1),
                    "formato_price_mq" => (is_null($formatoCustom[0]["price_mq"]) ? "0" : floatval($formatoCustom[0]["price_mq"])),
                ];
            }
                
        }

        return $formati;
    }


}