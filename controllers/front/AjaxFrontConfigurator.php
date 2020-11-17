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

use PhpParser\Node\Expr\Cast\Object_;

require_once( EW_MODULE_DIR . '/classes/JoinCombinations.php');
//require_once( _MODULE_DIR_ . 'ewphotocustomizer/classes/JoinCombinations.php');


if (!defined('_PS_VERSION_'))
    exit;

class EwphotocustomizerAjaxFrontConfiguratorModuleFrontController extends ModuleFrontController
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
            // 
            case "getElementiPerFormato":
                $product_id = Tools::getValue('product_id');
                $product_attribute_id = Tools::getValue('formato_id'); // id combinazione formato

                $query = new DbQuery();
                $query->select("*");
                $query->from("ew_photo_join_combinations");
                $query->where("product_id = $product_id");
                $query->where("product_attribute_id = $product_attribute_id");
                $query->where("id_child = 0");
                $query->where("id_combo = 0");
                $results = Db::getInstance()->executeS($query);

                $elementi = array();
                $count = 0;

                foreach ($results as $el) {
                    $base_product = new Product($el["id_parent"]);
                    $product_category = new Category($base_product->id_category_default);
                    $elementi[$count] = [
                        "id_categoria" => $product_category->id,
                        "nome_categoria" =>  $product_category->getName(), 
                        "id_base_product" => $base_product->id, //Product::getProductName($base_product->id, null, $this->context->language->id),
                    ];
                    $count++;
                }

                if (count($elementi) < 1) {
                    exit(Tools::jsonEncode("no_elements"));
                }

                exit(Tools::jsonEncode($elementi));    
            break;

            case "getElementiBase":
                $product_id = Tools::getValue('product_id');
                $product_attribute_id = Tools::getValue('formato_id');
                $id_parent = Tools::getValue('category_id');

                $base_product = new Product($id_parent);
                $product_category = new Category($base_product->id_category_default);

                $query = new DbQuery();
                $query->select("*");
                $query->from("ew_photo_join_combinations");
                $query->where("product_id = $product_id");
                $query->where("product_attribute_id = $product_attribute_id");
                $query->where("id_parent = $id_parent");
                $query->where("id_child != 0");
                $query->where("id_combo = 0");
                $results = Db::getInstance()->executeS($query);

                $elementi = array();
                $elementi["nome_categoria"] = $product_category->getName();
                $count = 0;
                $icon = '/modules/ewphotocustomizer/views/img/' . $this->getIconOfElements($product_category->getName());

                foreach ($results as $el) {   
                    $img = Product::getCombinationImageById($el["id_child"], Context::getContext()->language->id); 
                    $image_url = "";
                    if ($img != false) {
                        $image = new Image($img['id_image']);
                        $image_folder = $image->getImgPath();
                        $image_url = '/img/p/'. $image_folder .".".$image->image_format;
                    }         
                    $elementi[$count] = [
                        "id_elemento" => $el["id_child"],
                        "nome_elemento" => Product::getProductName($id_parent, $el["id_child"], Context::getContext()->language->id),
                        "icon" => ($img != false) ? $image_url : $icon,
                        "is_default" => $el["is_default"],
                        "price" => $el["price"],
                        "discount" => $el["discount"],
                        "discount_type" => (empty($el["discount_type"])) ? '%' : $el["discount_type"],
                    ];
                    $count++;
                }
                
                if (count($elementi) < 1) {
                    exit(Tools::jsonEncode("no_elements"));
                } else {
                    $elementi["count"] = $count;
                    exit(Tools::jsonEncode($elementi)); 
                }
            break;

            case "getElementiOpzioni":
                $product_id = Tools::getValue('product_id');
                $product_attribute_id = Tools::getValue('formato_id');
                $id_parent = Tools::getValue('category_id');
                $id_child = Tools::getValue('child_id');            

                $query = new DbQuery();
                $query->select("*");
                $query->from("ew_photo_join_combinations");
                $query->where("product_id = $product_id");
                $query->where("product_attribute_id = $product_attribute_id");
                $query->where("id_parent = $id_parent");
                $query->where("id_child = $id_child");
                $query->where("id_combo != 0");
                $results = Db::getInstance()->executeS($query);

                $elementi = array();
                $count = 0;

                foreach ($results as $el) {    
                    $option_product = new Product($this->getIdProductByIdProductAttribute($el["id_combo"]));
                    $product_category = new Category($option_product->id_category_default);
                    // get combination image
                    $icon = '/modules/ewphotocustomizer/views/img/' . $this->getIconOfElements($product_category->getName());
                    $img = Product::getCombinationImageById($el["id_combo"], Context::getContext()->language->id); 
                    $image_url = "";
                    if ($img != false) {
                        $image = new Image($img['id_image']);
                        $image_folder = $image->getImgPath();
                        $image_url = '/img/p/'. $image_folder .".".$image->image_format;
                    }
                    // get combination color 
                    $color = $this->getColorAttribute($el["id_combo"]);
                    $elementi[$count] = [
                        "id_elemento" => $el["id_combo"],
                        "nome_categoria" => $product_category->getName(),
                        "nome_elemento" => Product::getProductName($option_product->id, $el["id_combo"], Context::getContext()->language->id),
                        "icon" => ($img != false) ? $image_url : $icon,
                        "color" => $color,
                        "is_default" => $el["is_default"],
                        "price" => $el["price"],
                        "discount" => $el["discount"],
                        "discount_type" => (empty($el["discount_type"])) ? '%' : $el["discount_type"],
                    ];
                    $count++;
                }
                
                if (count($elementi) < 1) {
                    exit(Tools::jsonEncode("no_elements"));
                } else {
                    $elementi["count"] = $count;
                    exit(Tools::jsonEncode($elementi)); 
                }
            break;

            case "add-to-cart":                
                $customizations = Tools::jsonDecode(Tools::getValue('customizations'));
                $image_base64 = Tools::jsonDecode(Tools::getValue('final_image'));
                $this->addToCart($customizations, $image_base64);
                exit(Tools::jsonEncode("test ok"));
            break;

            case "saveImage":
                exit(Tools::jsonEncode("test ok"));
            break;
        }
    }

    public function getIconOfElements($elementName) 
    {
        switch (strtolower($elementName)) {
            case "tele":
                return $icon = "tela.svg";
                break;
            case "supporti":
                return $icon = "supporti.svg";
                break;
            case "cornici":
                return $icon = "cornice.svg";
                break;
            case "stampe":
                return $icon = "stampa.svg";
                break;
            case "carta":
                return $icon = "carta.svg";
                break;
            case "telaio":
                return $icon = "telaio.svg";
               break;
       }
    }


    public function getIdProductByIdProductAttribute($id_product_attribute)
    {
        $id_shop = $this->context->shop->id;
        $query = new DbQuery();
        $query->select("id_product");
        $query->from("product_attribute_shop");
        $query->where("id_product_attribute = $id_product_attribute");
        $query->where("id_shop = $id_shop");
        return Db::getInstance()->getValue($query);
    }


    public function getColorAttribute($id_product_attribute)
    {
        $id_attribute = Db::getInstance()->getValue('
			SELECT a.id_attribute
			FROM ' . _DB_PREFIX_ . 'product_attribute_combination pac
			JOIN ' . _DB_PREFIX_ . 'attribute a ON (pac.id_attribute = a.id_attribute)
			JOIN ' . _DB_PREFIX_ . 'attribute_group ag ON (ag.id_attribute_group = a.id_attribute_group)
			WHERE pac.id_product_attribute=' . (int) $id_product_attribute . ' AND ag.is_color_group = 1 
        ');
        
        if ($id_attribute != false) {
            $query = new DbQuery();
            $query->select("color");
            $query->from("attribute");
            $query->where("id_attribute = $id_attribute");
            return Db::getInstance()->getValue($query);
        } else {
            return "";
        }
    }


    public function addToCart($customizations, $image_base64)
    {   
        $context = $this->context;
        $id_cart = $this->createCartIfNotExists();    
        $customer = $context->customer;
        $currency = new CurrencyCore($context->currency->id);
        $curr_symbol = $currency->iso_code;
        $id_product = $customizations->product;
        $id_product_attribute = $customizations->formato->id;

        $value = "";
        // customizzazioni del prodotto
        $query =
            "INSERT INTO `" . _DB_PREFIX_ . "customization` " .
            "(`id_product_attribute`, `id_address_delivery`, `id_cart`, `id_product`, `quantity`, `quantity_refunded`, `quantity_returned`, `in_cart`) " .
            " VALUES($id_product_attribute, 0, $id_cart, $id_product, 0, 0, 0, 1)";
        Db::getInstance()->execute($query);
        $id_customization = (int)\Db::getInstance()->Insert_ID();

        // Original image
        $original_image_url = $customizations->image;
        $query =    "INSERT INTO `" . _DB_PREFIX_ . "customized_data` " .
                    "(`id_customization`, `type`, `index`, `value`, `id_module`, `price`, `weight`) " .
                    "VALUES ($id_customization, 1, 1, '$original_image_url', 0, 0.0, 0.0)";
            Db::getInstance()->execute($query);        

        // Customized image
        $image_name = "preview_" . $id_customization;
        $image_url = $this->addImageCustomized($image_base64, $image_name);
        $query =        "INSERT INTO `" . _DB_PREFIX_ . "customized_data` " .
                        "(`id_customization`, `type`, `index`, `value`, `id_module`, `price`, `weight`) " .
                        "VALUES ($id_customization, 1, 2, '$image_url', 0, 0.0, 0.0)";
            Db::getInstance()->execute($query);

        // formato
        $formato = $customizations->formato;
        if ((bool) $formato->is_custom) {
            $value = "" .  $formato->desc . " [ " . $this->addTax($id_product, $formato->price) . " $curr_symbol] ";
            $price_formato_notax = round($formato->price);
            $query =
                "INSERT INTO `" . _DB_PREFIX_ . "customized_data` " .
                "(`id_customization`, `type`, `index`, `value`, `id_module`, `price`, `weight`) " .
                "VALUES ($id_customization, 1, 3, '$value', 0, $price_formato_notax, 0.0)";
            Db::getInstance()->execute($query);
        } else {
            $value = "" .  $formato->desc . " [ " . $this->addTax($id_product, $formato->price) . " $curr_symbol] ";
            $query =
                "INSERT INTO `" . _DB_PREFIX_ . "customized_data` " .
                "(`id_customization`, `type`, `index`, `value`, `id_module`, `price`, `weight`) " .
                "VALUES ($id_customization, 1, 3, '$value', 0, 0.0, 0.0)";
            Db::getInstance()->execute($query);
        }
        

        // supporto stampa
        $supportostampa = $customizations->supportostampa;
        $price_suppstampa_wtax = $this->calculatePrice($id_product, $supportostampa->price, $supportostampa->discount, $supportostampa->dsctype, true);
        $price_suppstampa_notax = $this->calculatePrice($id_product, $supportostampa->price, $supportostampa->discount, $supportostampa->dsctype, false);

        $value = "" .  $supportostampa->name . " [ " . $price_suppstampa_wtax . " $curr_symbol] ";
        $query =
            "INSERT INTO `" . _DB_PREFIX_ . "customized_data` " .
            "(`id_customization`, `type`, `index`, `value`, `id_module`, `price`, `weight`) " .
            "VALUES ($id_customization, 1, 4, '$value', 0, ".floatval($price_suppstampa_notax).", 0.0)";
        Db::getInstance()->execute($query);

        // opzioni  
        foreach ($customizations->opzioni as $option => $properties) {
            if ($properties->id != 0) {
                $name = $properties->name;
                $price =  $properties->price;
                $discount = $properties->discount;
                $discount_type = $properties->dsctype;
                $index = $properties->id;

                $price_wtax = $this->calculatePrice($id_product, $price, $discount, $discount_type, true);
                $price_notax = $this->calculatePrice($id_product, $price, $discount, $discount_type, false);
                
                $valore  = "" .  $name . " [ " . $price_wtax . " $curr_symbol ] ";
                
                $query =
                        "INSERT INTO `" . _DB_PREFIX_ . "customized_data` " .
                        "(`id_customization`, `type`, `index`, `value`, `id_module`, `price`, `weight`) " .
                        "VALUES ($id_customization, 1, $index, '$valore', 0, ".floatval($price_notax).", 0.0)";
                Db::getInstance()->execute($query);
            }
        }
        
        $cart = new Cart($id_cart);
        $cart->id_currency = $context->currency->id;
        $cart->id_lang = $context->language->id;

        $quantity = (int) $customizations->quantity;

        $cart->updateQty($quantity, $id_product, $id_product_attribute, $id_customization, 'up', 0, null, false);

        $cart->update();

    }


    private function createCartIfNotExists()
    {
        if (!$this->context->cart->id) {
            if (\Context::getContext()->cookie->id_guest) {
                $guest = new Guest(\Context::getContext()->cookie->id_guest);
                $this->context->cart->mobile_theme = $guest->mobile_theme;
            }

            $this->context->cart->add();

            if ($this->context->cart->id) {
                $this->context->cookie->id_cart =
                    (int)$this->context->cart->id;
            }
        }

        return $this->context->cart->id;
    }


    public function calculatePrice($id_product, $price, $discount = 0, $disc_type = "%", $with_tax = false)
    {
        $price = floatval($price);
        $discount = floatval($discount);
        if ($discount > 0) {
            if ("%" == $disc_type) {
                if ($with_tax)
                    return $this->addTax($id_product, ($price - ($price * $discount / 100)));
                else 
                    return ($price - ($price * $discount / 100));
            } else {
                if ($price > $discount) {
                    if ($with_tax)
                        return $this->addTax($id_product, $price - $discount);
                    else
                        return ($price - $discount);
                }                    
                else 
                    return 0;
            }
        } else {
            if ($with_tax) {
                return $this->addTax($id_product, $price);
            } else {
                return $price;
            }            
        }        
    }

    public function addTax($id_product, $price_to_add_tax) {
        $tax_rate = Tax::getProductTaxRate($id_product);
        $tax = (floatval($price_to_add_tax) * floatval($tax_rate) ) / 100;
        return $price_to_add_tax + $tax;
    }


    public function addImageCustomized($base64_string, $image_name)
    {
        $dir =  _PS_ROOT_DIR_ . "/upload/" . $image_name . "_small.png";

        list($type, $base64_string) = explode(';', $base64_string);
        list(, $base64_string)      = explode(',', $base64_string);
        $content = base64_decode($base64_string);

        file_put_contents($dir, $content);

        return _PS_BASE_URL_ . "/upload/" . $image_name . "_small.png";
    }
}