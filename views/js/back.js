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
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/


function openTab(evt, el) {
    // Declare all variables
    var i, tabcontent, tablinks;
  
    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
  
    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tab-link");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
  
    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(el).style.display = "block";
    evt.currentTarget.className += " active";

    refreshSelectElements();
}


function configProductToJoin(idAttribute, idParent, idChild, idCombo = 0, actions){

  var dataToSend;
  var codeIdentify = "";

  if (idChild == 0) {
    codeIdentify = idAttribute + "" + idParent;
  } else {
    codeIdentify = idAttribute + "" + idParent + "" + idChild;
    if (idCombo != 0) {
      codeIdentify = idAttribute + "" + idParent + "" + idChild + "" + idCombo;
    }
  }

  console.log("#opt_id_"+codeIdentify);

  switch (actions) {
    case "addProduct":
    case "addCombo":
    case "addElement":
      dataToSend = {
        action: actions,
        token: new Date().getTime(),
        product_id: EW_IdProduct,
        combination_id: idAttribute,
        id_parent: idParent,
        id_child: idChild
      };
      break;

    case "saveProduct":
      dataToSend = {
        action: actions,
        token: new Date().getTime(),
        product_id: EW_IdProduct,
        combination_id: idAttribute,
        id_parent: idParent,
        id_child: idChild,
        id_combo: idCombo,
        id_jc: $("#opt_id_"+codeIdentify).val(),
        price: $("#opt_price_"+codeIdentify).val(),
        discount: $("#opt_discount_"+codeIdentify).val(),
        discount_type: $("#opt_discount_type_"+codeIdentify).val(),
        isdefault: $("#opt_isdefault_"+codeIdentify).is(":checked"),
        isexclusive: $("#opt_isexclusive_"+codeIdentify).is(":checked")
      };
      break;

    case "addComboCombination":
      dataToSend = {
        action: actions,
        token: new Date().getTime(),
        product_id: EW_IdProduct,
        combination_id: idAttribute,
        id_parent: idParent,
        id_child: idChild,
        id_combo: idCombo
      };
      break;

    case "removeBaseProduct":
      var response = confirm('Sei sicuro di procedere alla cancellazione? Confermando verranno eliminate tutte le combinazioni.');
      if (response) {
        dataToSend = {
          action: actions,
          token: new Date().getTime(),
          product_id: EW_IdProduct,
          combination_id: idAttribute,
          id_parent: idParent,
          id_child: idChild
        };
      } else {
        return;
      }
      break;

    case "removeComboProduct":
      if (idCombo == 0) {
        var response = confirm('Sei sicuro di procedere alla cancellazione? Confermando verranno eliminate tutte le combinazioni.');
      } else {
        var response = confirm('Sei sicuro di voler rimuovere la combinazione?');
      }
      
      if (response) {
        dataToSend = {
          action: actions,
          token: new Date().getTime(),
          product_id: EW_IdProduct,
          combination_id: idAttribute,
          id_parent: idParent,
          id_child: idChild,
          id_combo: idCombo
        };
      } else {
        return;
      }
      break;
  }

  $.ajax({
      url: EW_BaseUrl + "index.php?fc=module&module=ewphotocustomizer&controller=AdminProductsCombination",
      type: 'POST',
      cache: false,
      dataType: "json",
      data: dataToSend,
      success: function (result) {
        //console.log(result);
        if (result == "reload") {
          location.reload();
        }
        if (actions == "addCombo") {
          $("#opt_id_"+codeIdentify).val(result);
          $("#combo"+codeIdentify).removeClass('hide');
          $("#addCombo"+codeIdentify).addClass('hide');
        }
      }
  });
}

function getUrlParameter(sParam) {
  var sPageURL = window.location.search.substring(1),
      sURLVariables = sPageURL.split('&'),
      sParameterName,
      i;

  for (i = 0; i < sURLVariables.length; i++) {
      sParameterName = sURLVariables[i].split('=');

      if (sParameterName[0] === sParam) {
          return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
      }
  }
}

function refreshSelectElements() {
  $('.js-combo-select-ajax').select2({
    minimumInputLength: 3,
    maximumInputLength: 20,
    ajax: {
      url: EW_BaseUrl + "index.php?fc=module&module=ewphotocustomizer&controller=AjaxElementsFinder",
      dataType: 'json',
      data: function (params) {
        var idAttribute = $(this).data("idattr");
        var idParent = $(this).data("idbase");
        var idChild = $(this).data("idcombo");
        var query = {
          search: params.term,
          product_id: EW_IdProduct,
          combination_id: idAttribute,
          id_parent: idParent,
          id_child: idChild,
        };

        return query;
      },
      processResults: function (data) {
        return {
        results: data
        };
      }
    }
  });
}

function addNewElement(combination_id, id_parent, id_child) {
  var idNewElement = $("#search_"+combination_id+""+id_parent+""+id_child).val();
  if (idNewElement != null) {
    console.log(idNewElement);
    configProductToJoin(combination_id, id_parent, id_child, idNewElement, 'addComboCombination');

    $("#search_"+combination_id+""+id_parent+""+id_child).val(null).trigger('change');
  }
}

function saveFormatoCustom(product_attribute_id) {
  console.log(product_attribute_id);
  var is_formato_custom = $("#is_formato_custom_"+product_attribute_id).val();
  var price_mq = $("#price_mq_formato_custom_"+product_attribute_id).val();
  console.log(is_formato_custom);
  $.ajax({
    url: EW_BaseUrl + "index.php?fc=module&module=ewphotocustomizer&controller=SaveFormatoCustom",
    type: 'POST',
    cache: false,
    dataType: "json",
    data: {
      product_id: EW_IdProduct,
      product_attribute_id: product_attribute_id,
      is_formato_custom: is_formato_custom,
      price_mq: price_mq
    },
    success: function (result) {
      console.log(result);
    }
  });
}

$(document).ready(function(){
  
});



