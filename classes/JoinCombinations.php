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

class JoinCombinations extends ObjectModel
{
    public $id_jc;

    public $product_id;

    public $product_attribute_id;

    public $id_parent;

    public $id_child;

    public $id_combo;

    public $child_category;

    public $quantity = "999999";

    public $price;

    public $discount;

    public $discount_type = "%";

    public $is_default;

    public $is_exclusive;


    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'ew_photo_join_combinations',
        'primary' => 'id_jc',
        'fields' => [
            'product_id' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ],
            'product_attribute_id' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ],
            'id_parent' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ],
            'id_child' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ],
            'id_combo' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ],
            'child_category' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ],
            'quantity' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ],
            'price' => [
                'type' => self::TYPE_FLOAT,
                'validate' => 'isPrice'
            ],
            'discount' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ],
            'discount_type' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isGenericName'
            ],
            'is_default' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ],
            'is_exclusive' => [
                'type' => self::TYPE_BOOL,
                'validate' => 'isBool'
            ],
        ]
    ];

    public static function getJoinCobinationByCombo($product_id, $product_attribute_id, $id_parent, $id_child, $id_combo = 0)
    {
        $query = new DbQuery();
        $query->select("id_jc");
        $query->from("ew_photo_join_combinations");
        $query->where("product_id = $product_id");
        $query->where("product_attribute_id = $product_attribute_id");
        $query->where("id_parent = $id_parent");
        $query->where("id_child = $id_child");
        if ($id_combo == 1) {
            $query->where("id_combo != 0");
        } else {
            $query->where("id_combo = 0");
        }
        
        $query->limit(1);

        $rtn = Db::getInstance()->executeS($query);

        if (count($rtn) > 0) {
            $id_jc = $rtn[0]['id_jc'];
            return new JoinCombinations($id_jc);
        } else {
            return new JoinCombinations();
        }

        return Db::getInstance()->executeS($query);
    }


    public static function checkProductJoin($product_id, $product_attribute_id)
    {
        $query = new DbQuery();
        $query->select("*");
        $query->from("ew_photo_join_combinations");
        $query->where("product_id = $product_id");
        $query->where("product_attribute_id = $product_attribute_id");
        $query->limit(1);

        return Db::getInstance()->executeS($query);
    }


    public static function checkJoinProduct($product_id, $product_attribute_id, $id_parent, $id_child, $id_combo = 0)
    {
        $query = new DbQuery();
        $query->select("id_jc");
        $query->from("ew_photo_join_combinations");
        $query->where("product_id = $product_id");
        $query->where("product_attribute_id = $product_attribute_id");
        $query->where("id_parent = $id_parent");
        $query->where("id_child = $id_child");
        if ($id_combo == 1) {
            $query->where("id_combo != 0");
        } else {
            $query->where("id_combo = $id_combo");
        }
            

        $return = Db::getInstance()->getValue($query);

        if ($return != false) {
            return true;
        } else {
            return false;
        }
    }


    public static function getChildCombination($parent, $idProduct, $idProductAttribute)
    {
        $comboList = array();
        $context = \Context::getContext();

        foreach ($parent->getAttributesResume($context->language->id) as $cmb)
        {
            $options = self::getJoinCobinationByCombo($idProduct, $idProductAttribute, $parent->id, $cmb["id_product_attribute"]);

            $listCombinationOfCombo = self::getCombinations4Combo($idProduct, $idProductAttribute, $parent->id, $cmb["id_product_attribute"]);

            $comboList[] = [
                "id" => $cmb["id_product_attribute"],
                "name" => $cmb["attribute_designation"],
                "is_checked" => self::checkJoinProduct($idProduct, $idProductAttribute, $parent->id, $cmb["id_product_attribute"]),
                "options" => $options,
                "combinationList" => $listCombinationOfCombo,
            ];
        }

        return $comboList;
    }

    public static function getCombinations4Combo($id_base_product, $id_product_attribute, $id_parent, $id_child)
    {
        $query = new DbQuery();
        $query->select("id_jc");
        $query->from("ew_photo_join_combinations");
        $query->where("product_id = $id_base_product");
        $query->where("product_attribute_id = $id_product_attribute");
        $query->where("id_parent = $id_parent");
        $query->where("id_child = $id_child");
        $query->where("id_combo != 0");
        $res = Db::getInstance()->executeS($query);

        $comboList = array();
        foreach($res as $combo) {
            $combo2 = new JoinCombinations($combo["id_jc"]);
            $comboList[] = [
                "id" => $combo2->id_combo,
                "id_child" => $combo2->id_child,
                "name" => self::getNameByIdProductAttribute($combo2->id_combo),
                "is_checked" => true,
                "options" => $combo2,
            ];
        }

        return $comboList;
    }


    public static function getNameByIdProductAttribute($id_product_attribute) 
    {
        $query = new DbQuery();
        $query->select("id_product");
        $query->from("product_attribute");
        $query->where("id_product_attribute = $id_product_attribute");
        $id_product = Db::getInstance()->getValue($query);

        if ($id_product != false) {
            return Product::getProductName($id_product, $id_product_attribute, null);
        } else {
            return "";
        }
    }


    public static function removeSubProduct($product_id, $product_attribute_id, $id_parent, $id_child = null, $id_combo = null) 
    {
        $where = "product_id = $product_id AND product_attribute_id = $product_attribute_id AND id_parent = $id_parent ";
        if (!empty($id_child)) {
            $where .= "AND id_child = $id_child ";
            if (!empty($id_combo)) {
                $where .= "AND id_combo = $id_combo ";
            }
        }
        Db::getInstance()->delete("ew_photo_join_combinations", $where);
    }

}