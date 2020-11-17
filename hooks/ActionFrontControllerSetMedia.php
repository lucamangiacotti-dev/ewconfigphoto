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

trait ActionFrontControllerSetMedia
{
    public function hookActionFrontControllerSetMedia(array $params)
    {
        switch (Tools::getValue("controller"))
        {
            case "product":
                $this->productController();
                break;
            default: 
                break;
        }

    }

    private function productController()
    {
        $id_product = Tools::getValue("id_product");
        // $isEnabled = PhotoConfigurator::isConfiguratorEnabled($id_product);

        // se il prodotto è abilitato alla personalizzazione, includo il js e il css
        if (true)
        {

            $this->context->controller->registerJavascript(
                'front-slim',
                'modules/' . EW_MODULE_NAME . '/lib/slim-image-cropper/js/slim.global.min.js',
                ['position' => 'top', 'priority' => 999]
            );

            $this->context->controller->registerJavascript(
                'front-configurator',
                'modules/' . EW_MODULE_NAME . '/views/js/front-configurator.js',
                ['position' => 'bottom', 'priority' => 999]
            );

            $this->context->controller->registerStylesheet(
                'front-configurator',
                'modules/' . EW_MODULE_NAME . '/views/css/front-configurator.css',
                ['position' => 'bottom', 'priority' => 1000]
            );

            $this->context->controller->registerStylesheet(
                'front-configurator-mobile',
                'modules/' . EW_MODULE_NAME . '/views/css/front-configurator-mobile.css',
                ['position' => 'bottom', 'priority' => 1000]
            );
        }
    }




}