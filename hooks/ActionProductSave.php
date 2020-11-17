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

trait ActionProductSave
{
    public function hookActionProductSave(array $params)
    {
        $id_product = $params["id_product"];
        $product = new Product((int) $id_product);
        $isCustomizable = Tools::getValue("is-customizable");
        $isElement = Tools::getValue("is-element");
        $isBaseProduct = Tools::getValue("is-base-product");

        $productConfigurator = new ProductConfigurator();
        $productConfigurator->product_id = $product->id;
        
        // verifico se è presente nella tabella photo_configurator
        $configuration = ProductConfigurator::getConfigurationByIdProduct($id_product);

        if ($configuration != false) {
            $productConfigurator = new ProductConfigurator($configuration->id);
        }

        // set category id default
        $productConfigurator->id_category = $product->id_category_default;

        if ($isCustomizable == "on") {
            $productConfigurator->enable();
        } else {
            $productConfigurator->disable();
        }

        if ($isElement == "on") {
            $productConfigurator->is_element = 1;
        } else {
            $productConfigurator->is_element = 0;
        }

        if ($isBaseProduct == "on") {
            $productConfigurator->is_base_product = 1;
        } else {
            $productConfigurator->is_base_product = 0;
        }

        $productConfigurator->saveConfig();
    }
}