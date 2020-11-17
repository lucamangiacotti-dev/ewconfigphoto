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

require_once(dirname(__FILE__).'../../../../config/config.inc.php');
require_once(dirname(__FILE__).'../../../../init.php');

class EwphotocustomizerAdminProductsCombinationModuleFrontController extends ModuleFrontController
{

    public function initContent()
    {
       $this->ajax = true;
        parent::initContent();
    }


    // This function will get ajax call where pass two variables and a method name doSomeAction    
    public function ajaxProcessDoSomeAction()
    {
        $var1 = Tools::getValue('variable_1');

        die(Tools::JsonEncode($var1));
        
        //return $var1;
 
    	// your action code ....
    }


}