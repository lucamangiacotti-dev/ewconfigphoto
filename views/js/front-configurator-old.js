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

function testCropButton() {
  pixie = new Pixie({
      tools: {
          crop: {
              replaceDefault: false,
              items: ['1:1', '2:3']
          }
      }
  });
}


$( document ).ready(function() {
  // eliminino i blocchi interni nel dettaglio prodotto
  $(".product-add-to-cart, .product-variants, .product-discounts").html("");
  $("#add-to-cart-or-refresh").submit(function(e){
      e.preventDefault();
  });

  $(".customer-photos a").ready(function() {
    $("#btnOfConfigurator").removeClass("disabled");
  });
  var hashParameter = window.top.location.hash.substr(1);
  if (hashParameter == "configurator") {
    setTimeout(function(){
      $("#btnOfConfigurator").click();
    }, 500 );
  } else {
    window.top.location.hash = "";
  }
});

$(".box-formato").click(function(){
  // get id formato
  EW_idFormatoSelected = $(this).data("id");  
  if (EW_customizations.formato.id == EW_idFormatoSelected) {
    swal("Stai selezionando lo stesso formato.");
    return;
  } else {
    swal({
      title: "Vuoi procedere?",
      text: "Cambiando il formato perderai le modifiche effettuate.",
      icon: "warning",
      buttons: true,
      dangerMode: false,
    }).then((value) => {
      if (value) {
        $("#formato-selezionato").html("");
        $(".box-formato").removeClass("selected");
        $(this).addClass("selected");
        addFormato(false, false);
      }
    });
  }
});


function modalFormatoCustom(id_formato, price_formato) {
  $('#id_formato_custom').val(id_formato);
  $('#pricemq_formato_custom').val(price_formato);
  var price_totale = getPriceCustomFormat();
  $("#price_formato_custom").val(price_totale);
  $("#total-price-custom-format").html(formatMoney(addPriceTax(price_totale), 2, ",", ".") + " €");
  $('#modalformaticustom').modal("show");
}


$("#larghezza, #altezza").change(function() {
  var price_totale = getPriceCustomFormat();
  $("#price_formato_custom").val(price_totale);
  $("#total-price-custom-format").html(formatMoney(addPriceTax(price_totale), 2, ",", ".") + " €");
});


function addFormatoCustom() {
  $(".box-formato").removeClass("selected");
  var id_formato_custom = $("#id_formato_custom").val();
  EW_idFormatoSelected = id_formato_custom;
  var larghezza_cm = parseInt($("#larghezza").val());
  var altezza_cm = parseInt($("#altezza").val());
  $("#name_formato_custom").val("Formato: "+larghezza_cm+" x "+altezza_cm);
  $("#formato-selezionato").html("Scelto: "+larghezza_cm+" x "+altezza_cm);
  var price_totale = getPriceCustomFormat();
  $("#price_formato_custom").val(price_totale);
  $("#total-price-custom-format").html(formatMoney(addPriceTax(price_totale), 2, ",", ".") + " €");
  addFormato(false, true);
}


function getPriceCustomFormat() {
  var price_mq = $('#pricemq_formato_custom').val();
  var larghezza_cm = parseInt($("#larghezza").val());
  var altezza_cm = parseInt($("#altezza").val());
  var larghezza_m = larghezza_cm / 100;
  var altezza_m = altezza_cm / 100;
  var area = larghezza_m * altezza_m; // area formato metri quadri
  return (price_mq * area);
}


// funzione di selezione del formato
function addFormato(is_default, is_custom) {
  var formato_prezzo = 0;
  var formato_desc = "Formato: ";
  if(is_default) {
    $("#btn-formati").click(); 
  }
  if (is_custom) {
    formato_prezzo = parseFloat($("#price_formato_custom").val());
    formato_desc = $("#name_formato_custom").val();
  } else {
    $(".box-formato").each(function(){
      if ( $(this).data("id") == EW_idFormatoSelected) {
        $(this).addClass("selected");
        formato_prezzo = $(this).data("price");
        formato_desc += $(this).find("span").html();
      }
    });
  }
  
  EW_customizations.formato = {id: EW_idFormatoSelected, price: formato_prezzo, desc: formato_desc, is_custom: is_custom};

  $.ajax({
    url: EW_BaseUrl + "index.php?fc=module&module=ewphotocustomizer&controller=AjaxFrontConfigurator",
    type: 'POST',
    cache: false,
    dataType: "json",
    data: {
        action: "getElementiPerFormato",
        token: new Date().getTime(),
        product_id: EW_idProduct,
        formato_id: EW_idFormatoSelected,
      },
    success: function (result) {
      $("#baseOptions").html("");
      if (result != "no_elements") {
        for (i = 0; i < result.length; i++) {
            var el = result[i];
            var nome = el["nome_categoria"].toLowerCase();
            $("#baseOptions").append('<div class="card" id="'+nome+'"><button class="btn-right-menu" onclick="openModal(\'#modal'+nome+'\')">'+nome+' <span class="material-icons">add_circle_outline</span></button></div>');
            setBaseOptions(el["id_base_product"]);
        }
        $("#baseOptions div").ready(function() {
          setTimeout(function(){
            $(".box-configurations").each(function(){
              if ( $(this).data("default") == true) {
                $(this).click();
              }

              // $(".scroll-container button:nth-child(1)").click();
            });
          }, 1200);
        });
      }
    }
  }); 
}

// funzione per aprire la finestra del configuratore
function openConfigurator() {
  $("body").addClass("open-configurator");
  $('#configurator').modal("show");
  if (EW_customizations.visited == false) {
    renderingPixie();
    EW_customizations = {
        visited:true, 
        customer:CustomerID,
        image:DefaultImage,
        product:EW_idProduct,         
        formato: {id: ''}, 
        supportostampa: {}, 
        opzioni: {},
        quantity: 1
      };
    addFormato(true, false);
    setTimeout(function(){ pixie.resetAndOpenEditor({image: DefaultImage});  }, 600);
  }
}

function closeConfigurator() {
  $("body").removeClass("open-configurator");
  $('#configurator').modal("hide");
}

// funzione per caricare le voci delle opzioni base 
function setBaseOptions(idCategoria) {
  $.ajax({
    url: EW_BaseUrl + "index.php?fc=module&module=ewphotocustomizer&controller=AjaxFrontConfigurator",
    type: 'POST',
    cache: false,
    dataType: "json",
    data: {
        action: "getElementiBase",
        token: new Date().getTime(),
        product_id: EW_idProduct,
        formato_id: EW_idFormatoSelected,
        category_id: idCategoria
    },
    success: function (rslt) {
      if (rslt != "no_elements") {
        var type = rslt["nome_categoria"].toLowerCase();
        $("#modal"+type+" .modal-body").html("");
        $("#modal"+type+" .modal-body").data("type", rslt["nome_categoria"]);
        $("#modal"+type+" .modal-body").data("id", idCategoria);
        if (parseInt(rslt.count) > 0) {
          for (i = 0; i < parseInt(rslt.count); i++) {
              var el = rslt[i.toString()];
              var id = el.id_elemento; 
              var icon_name = el.icon;
              var is_default = el.is_default;
              var price = parseFloat(el.price);
              var discount = el.discount; 
              var discount_type = el.discount_type;

              var css_class = "box-configurations";
              var price_to_show = formatMoney(calcDiscountedPrice(price, discount, discount_type), 2, ",", ".");
              if (discount > 0) {
                css_class = "box-configurations wdiscount";
              }
              $("#modal"+type+" .modal-body").append('<div class="'+css_class+'" data-id="'+id+'" data-price="'+price+'" data-default="'+is_default+'" data-discount="'+discount+'" data-dsctype="'+discount_type+'" onclick="addBaseOption($(this));"><div></div><img src="'+ icon_name +'"><span>'+el.nome_elemento+'</span> <div>'+price_to_show+' €</div></div>');        
          }
          $('#modal'+type+' .modal-title').html(rslt["nome_categoria"] + "<a type='button' data-dismiss='modal'><i class='material-icons'>close</i></a>");
        } else {
          $("#"+type).remove();
        }
      }
    }
  });
}

// funzione per caricare le voci selezionabili delle opzioni secondarie 
function setOptions(idCategoria, idBaseOption) {
  $.ajax({
    url: EW_BaseUrl + "index.php?fc=module&module=ewphotocustomizer&controller=AjaxFrontConfigurator",
    type: 'POST',
    cache: false,
    dataType: "json",
    data: {
        action: "getElementiOpzioni",
        token: new Date().getTime(),
        product_id: EW_idProduct,
        formato_id: EW_idFormatoSelected,
        category_id: idCategoria,
        child_id: idBaseOption
    },
    success: function (rslt) {
      if (rslt != "no_elements") {
        $("#configOptions").html("");
        for (x = 0; x < parseInt(rslt.count); x++) {
          var el = rslt[x.toString()];
          var cat_name = el.nome_categoria.toLowerCase();
          var modal_id = "#modal" + $.trim(cat_name);
          var section_id = $.trim(cat_name);
          
          if ($(".card").is("[data-id='"+section_id+"']") == false) {
            $("#configOptions").append('<div class="card" data-id="'+section_id+'"><button class="btn-right-menu" data-toggle="modal" data-target="'+modal_id+'">'+cat_name+' <span class="material-icons">add_circle_outline</span></button></div>');
            $(modal_id+" .modal-body").data("type", cat_name);
            Object.defineProperty(EW_customizations.opzioni, cat_name, {
              value: {id: "0", price: "0"}, writable: true, configurable: true, enumerable: true
            });
          }

          $(modal_id+" .modal-title span").html(cat_name);
          $(modal_id+" .modal-body").html("");
          $(modal_id+" .modal-footer").html("");
        }

        for (i = 0; i < parseInt(rslt.count); i++) {
          var el = rslt[i.toString()];
          var element_id = el.id_elemento;
          var cat_name = el.nome_categoria.toLowerCase();
          var icon_name = el.icon;
          var color = el.color;
          var icon_ban = EW_IconsUrl + "no-selezione.jpg";
          var is_default = el.is_default;
          var price = parseFloat(el.price);
          var discount = parseFloat(el.discount); 
          var discount_type = el.discount_type;
          var modal_id = "#modal" + $.trim(cat_name);

          var css_class = "box-options";
          if (discount > 0) {
            css_class = "box-options wdiscount";
          }
          var price_to_show = formatMoney(calcDiscountedPrice(price, discount, discount_type), 2, ",", ".");

          if ($(modal_id+" .box-options").is("[data-id='0']") == false && cat_name != "stampe") {
            $(modal_id+" .modal-body").append('<div class="box-options" data-id="0" data-price="0" data-default="true" data-discount="0" data-dsctype="%" onclick="addOption($(this));"><img src="'+ icon_ban +'">Non selezionato</div>');
          }

          if ($(".box-options").is("[data-id='"+element_id+"']") == false) {
            $(modal_id+" .modal-body").append('<div class="'+css_class+'" data-id="'+element_id+'" data-price="'+price+'" data-discount="'+discount+'" data-dsctype="'+discount_type+'" data-default="'+is_default+'" data-color="'+color+'" onclick="addOption($(this));"><div></div><img src="'+ icon_name +'"><span>'+el.nome_elemento+'</span> <div>'+price_to_show+' €</div></div>');
          }
        }
        // inizializza i default
        setTimeout(function(){
          $(".box-options").each(function(){
            if ( $(this).data("default") == true) {
              $(this).click();
            }
          });
        }, 1500);
      }
    }
  });
}

// funzione per la gestione dei modal con le opzioni base
function openModal(el) {
  $(el).modal("show");
  $(el+" .modal-body").children().removeClass("selected");
  $(el+" .modal-footer").html("");
  var element_price = EW_customizations.supportostampa.price;
  var element_discount = EW_customizations.supportostampa.discount;
  var element_discount_type = EW_customizations.supportostampa.dsctype;
  var element_selected = "#modal"+ EW_customizations.supportostampa.tipo;
  var element_id = EW_customizations.supportostampa.id;

  var original_price = formatMoney(addPriceTax(element_price), 2, ",", ".");
  var price_showed = calcDiscountedPrice(element_price, element_discount, element_discount_type);
  if (element_discount > 0) {    
    var html = "<span class=\"price-wdiscount\">"+original_price+"</span> " + formatMoney(price_showed, 2, ",", ".");
    $(element_selected+" .modal-footer").html("<h4>Aggiunta: + "+html+" €</h4>");
  } else {
    $(element_selected+" .modal-footer").html("<h4>Aggiunta: + "+formatMoney(price_showed, 2, ",", ".")+" €</h4>");
  }

  $('.box-configurations[data-id="'+element_id+'"]').addClass('selected');
}

// funzione per aggiungere l'immagine dalla galleria
function addImageToPixie(imageUrl) {
  var completeUrlImage = EW_BaseUrl + imageUrl;
  pixie.resetAndOpenEditor({image: completeUrlImage});
  EW_customizations.image = completeUrlImage;
}

// funzione per selezionare le opzioni necessarie / base
function addBaseOption(el) {
  el.parent().children().removeClass("selected");
  el.addClass("selected");  
  var option_type = el.parent().data("type").toLowerCase();
  var option_name = el.find("span").html();
  var category_id = el.parent().data("id");
  var option_price = el.data("price");
  var option_discount = el.data("discount");
  var option_discount_type = el.data("dsctype");
  var option_id = el.data("id");

  EW_customizations.supportostampa = {id: option_id, name: option_name, tipo: option_type, price: option_price, discount: option_discount, dsctype: option_discount_type};

  var original_price = formatMoney(addPriceTax(option_price), 2, ",", ".");
  var price_showed = calcDiscountedPrice(option_price, option_discount, option_discount_type);
  if (option_discount > 0) {    
    var html = "<span class=\"price-wdiscount\">"+original_price+"</span> " + formatMoney(price_showed, 2, ",", ".");
    el.parent().parent().find(".modal-footer").html("<h4>Aggiunta: + "+html+" €</h4>");
  } else {
    el.parent().parent().find(".modal-footer").html("<h4>Aggiunta: + "+formatMoney(price_showed, 2, ",", ".")+" €</h4>");
  }

  setOptions(category_id, option_id);

  // calcolo il totale
  //calcTotalPrice();
}

// funzione per selezionare le opzioni secondarie
function addOption(el) {
  el.parent().children().removeClass("selected");
  el.addClass("selected");
  var option_type = el.parent().data("type").toLowerCase();
  var option_price = el.data("price");
  var option_discount = el.data("discount");
  var option_discount_type = el.data("dsctype");
  var option_id = el.data("id");
  var option_name = el.find("span").html();
  var option_color = el.data("color");

  for (var key in EW_customizations.opzioni) {
    if (key == option_type) {
      EW_customizations.opzioni[key].id = option_id;
      EW_customizations.opzioni[key].name = option_name;
      EW_customizations.opzioni[key].price = option_price;
      EW_customizations.opzioni[key].discount = option_discount;
      EW_customizations.opzioni[key].dsctype = option_discount_type;
    }
    if (key == "cornici" && key == option_type) {
      var price_cornice = parseFloat(EW_customizations.opzioni[key].price);
      if (0 != price_cornice) {
          $("#pixie-canvas").css("border", "32px solid "+option_color);
      } else {
          $("#pixie-canvas").css("border", "none");
      }
    }
  }

  var original_price = formatMoney(addPriceTax(option_price), 2, ",", ".");
  var price_showed = calcDiscountedPrice(option_price, option_discount, option_discount_type);
  if (option_discount > 0) {    
    var html = "<span class=\"price-wdiscount\">"+original_price+"</span> " + formatMoney(price_showed, 2, ",", ".");
    el.parent().parent().find(".modal-footer").html("<h4>Aggiunta: + "+html+" €</h4>");
  } else {
    el.parent().parent().find(".modal-footer").html("<h4>Aggiunta: + "+formatMoney(price_showed, 2, ",", ".")+" €</h4>");
  }
  calcTotalPrice();
}

// cambiando la quantità si aggiorna il totale
$("#product-quantity").change(function(){
  var quantita = $(this).val();
  EW_customizations.quantity = quantita;
  if (EW_customizations.supportostampa.id == undefined) {
    swal("Selezionare un supporto di stampa per procedere");
  } else {
    calcTotalPrice();
  }  
});

// funzione per calcolare la tassa ai prezzi
function addPriceTax(price) {
  var tax = (parseFloat(price) * parseFloat(ProductTaxRate)) / 100;
  return parseFloat(price + tax);
}

// funzione per il calcolo del prezzo scontato
function calcDiscountedPrice(price, discount, discount_type) {
  if (discount > 0) {
    if ("%" == discount_type) {
      return addPriceTax(parseFloat(price) - (parseFloat(price) * discount / 100));
    } else {
      if (price > discount)
        return addPriceTax(parseFloat(price) - discount);
      else 
        return 0;
    }
  } else {
    return addPriceTax(parseFloat(price));
  }
}

// funzione per il calcolo del totale
function calcTotalPrice() {
  $("#total-price").html("<small>Aggiornamento..</small>");
  setTimeout(function(){
    var total = 0;
    total += addPriceTax(parseFloat(EW_customizations.formato.price));
    total += calcDiscountedPrice(EW_customizations.supportostampa.price, 
                                  EW_customizations.supportostampa.discount,
                                    EW_customizations.supportostampa.dsctype);
    for(var key in EW_customizations.opzioni) {
      var option_price = calcDiscountedPrice(EW_customizations.opzioni[key].price,
                                              EW_customizations.opzioni[key].discount,
                                                EW_customizations.opzioni[key].dsctype);
      total = total + option_price;
    } 
    // quantità
    total = total * EW_customizations.quantity;
    console.log(EW_customizations);
    $("#total-price").html(formatMoney(total, 2, ",", ".") + " €");   
  }, 500);  
}

// funzione per la formattazione dei prezzi
function formatMoney(number, decPlaces, decSep, thouSep) {
  decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
  decSep = typeof decSep === "undefined" ? "." : decSep;
  thouSep = typeof thouSep === "undefined" ? "," : thouSep;
  var sign = number < 0 ? "-" : "";
  var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
  var j = (j = i.length) > 3 ? j % 3 : 0;
  
  return sign +
    (j ? i.substr(0, j) + thouSep : "") +
    i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
    (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
}

function riepilogo() {
  $("#riepilogo").modal("show");
  var html = "";
  $("#opzioniRiepilogo tbody").html("");

  if (typeof EW_customizations.formato.id != "undefined") {
    var formato_desc = EW_customizations.formato.desc;
    var formato_price = addPriceTax(EW_customizations.formato.price);
    $("#opzioniRiepilogo tbody").append("<tr><td>"+ formato_desc +"</td><td>"+ formatMoney(formato_price, 2, ",", ".") +" €</td></tr>");
  }

  if (typeof EW_customizations.supportostampa.id != "undefined") {
    var suppstampa_nome = EW_customizations.supportostampa.name;
    var suppstampa_price = calcDiscountedPrice(EW_customizations.supportostampa.price,
                                                EW_customizations.supportostampa.discount,
                                                  EW_customizations.supportostampa.dsctype);
    $("#opzioniRiepilogo tbody").append("<tr><td>"+suppstampa_nome+"</td><td>"+ formatMoney(suppstampa_price, 2, ",", ".")+" €</td></tr>");
  }

  for (var key in EW_customizations.opzioni) {
    if (EW_customizations.opzioni[key].id != 0) {
      var option_name = EW_customizations.opzioni[key].name;
      var option_price = calcDiscountedPrice(EW_customizations.opzioni[key].price,
                                                EW_customizations.opzioni[key].discount,
                                                  EW_customizations.opzioni[key].dsctype);
      html += "<tr><td>"+option_name+"</td><td>"+ formatMoney(option_price, 2, ",", ".")+" €</td></tr>";
    }    
  }

  $("#opzioniRiepilogo tbody").append(html);
}


function addToCart() {
  if (typeof EW_customizations.supportostampa.id  == "undefined") {
    swal({text: "Selezionare un supporto di stampa per procedere", icon: "warning"});
    return;
  } else {
    swal({text: "Caricamento del prodotto nel carrello in corso..", buttons: false, icon: "success"});
    pixie.getTool('resize').apply(20, 20, true);
    pixie.getTool('export').export('imageName', 'png', 0.2);
    setTimeout(function(){
      $.ajax({
        url: EW_BaseUrl + "index.php?fc=module&module=ewphotocustomizer&controller=AjaxFrontConfigurator",
        type: 'POST',
        cache: false,
        dataType: "json",
        data: {
            action: "add-to-cart",
            token: new Date().getTime(),
            final_image: JSON.stringify(EW_final_image),
            customizations: JSON.stringify(EW_customizations)
        },
        success: function (rslt) {
          if (rslt != 'error') {
            document.location.href = EW_BaseUrl +
                  "index.php?controller=cart&action=show";
          }
        }
      });
    }, 50);
  }
}



 