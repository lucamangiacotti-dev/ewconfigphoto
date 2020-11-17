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
$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ew_photo_product_configurator` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `product_attribute_id` int(11) NULL,
    `id_category` int(11) NULL,
    `is_customizable` int(2) NULL DEFAULT 0,
    `is_element` int(2) NULL DEFAULT 0,
    `is_base_product` int(2) NULL DEFAULT 0,
    PRIMARY KEY  (`id`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';


$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ew_photo_join_combinations` (
    `id_jc` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `product_attribute_id` int(11) NOT NULL,
    `id_parent` int(11) NOT NULL,
    `id_child` int(11) NOT NULL,
    `id_combo` int(11) NOT NULL,
    `child_category` int(4),
    `quantity` int(10) NOT NULL DEFAULT 1,
    `price` decimal(17,6) NOT NULL DEFAULT 0.000000,
    `discount` int(10) NULL DEFAULT 0,
    `discount_type` varchar(10) NOT NULL DEFAULT "%",
    `is_default` int(2) NULL DEFAULT 0,
    `is_exlusive` int(2) NULL DEFAULT 0,
    PRIMARY KEY  (`id_jc`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ew_formati_custom` (
    `product_id` int(11) NOT NULL,
    `product_attribute_id` int(11) NOT NULL,
    `is_formato_custom` int(1) NOT NULL DEFAULT 0,
    `price_mq` decimal(17,6) NOT NULL DEFAULT 0.000000
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';


foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
