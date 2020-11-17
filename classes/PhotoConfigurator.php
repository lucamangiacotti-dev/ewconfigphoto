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

class PhotoConfigurator extends ObjectModel
{
    public $id;

    public $product_id;

    public $product_attribute_id;

    public $id_category;

    public $is_customizable;

    public $is_element;

    public $is_base_product;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'ew_photo_configurator',
        'primary' => 'id',
        'fields' => [
            'product_id' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ],
            'product_attribute_id' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ],
            'id_category' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ],
            'is_customizable' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ],
            'is_element' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ],
            'is_base_product' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ],
        ]
    ];


    public function enable()
    {
        $this->is_customizable = 1;
        $this->save();

        return true;
    }


    public function disable()
    {
        $this->is_customizable = 0;
        $this->save();

        return true;
    }


    public function saveConfig()
    {
        $this->save();
    }


    public static function getConfigurationByIdProduct($id_product) 
    {
        $context = \Context::getContext();

        $query = new DbQuery();
        $query->select("*");
        $query->from("ew_photo_configurator");
        $query->where("product_id = $id_product");
        $query->limit(1);

        $result = Db::getInstance()->executeS($query);
        $found = count($result) > 0;
        if ($found) {
            return new PhotoConfigurator($result[0]["id"]);
        } else {
            return false;
        }
    }

}