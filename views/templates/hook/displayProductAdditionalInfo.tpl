{**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}



<div class="row">
    <div class="col-md-12">
        <!-- Button trigger modal -->
        <a href="#configurator" id="btnOfConfigurator" type="button" class="btn btn-primary disabled" onclick="openConfigurator()">
            Configura il prodotto
        </a>
    </div>
</div>


<script type="text/javascript">

var EW_modulePath = '{$ModulePath}';
var EW_BaseUrl = '{$BaseUrl}';
var EW_ModuleUrl = EW_BaseUrl + EW_modulePath;
var EW_IconsUrl = EW_ModuleUrl + 'views/img/';
var EW_idFormatoSelected = {$product.id_product_attribute};
var EW_idProduct = {$product.id};
var EW_final_image = "";
var ProductPrice = {$ProuctPrice};
var ProductTaxRate = {$TaxRate};

var DefaultImage = EW_IconsUrl + 'default.jpg';
var CustomerID = {$CustomerID};
{literal}
var EW_customizations = {visited: false};
{/literal}
</script>


<!-- Modal -->
<div class="modal fade" id="configurator" tabindex="-1" role="dialog" aria-labelledby="configuratorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-9">
                        <p class="modal-title" id="exampleModalLabel">{$product.name}</p>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="close" onclick="closeConfigurator();">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="row">
                    <!-- container editor immagine -->
                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-9">
                        <div class="picture-container">
                            <pixie-editor></pixie-editor>
                        </div>
                    </div>
                    <!-- colonna di destra -->
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3 column-options">
                        <div class="accordion" id="configPanel">
                            <div class="card card-gallery">
                                <button class="btn-right-menu" type="button" data-toggle="collapse" aria-expanded="false" data-target="#collapse2" aria-controls="collapse2">
                                    Le mie foto <span class="material-icons">add_circle_outline</span>
                                </button>
                                <div id="collapse2" class="collapse show" aria-labelledby="headingOne" data-parent="#configPanel">
                                    <div class="customer-photos">
                                        {if $CustomerPhotos|count > 0}
                                            {foreach from=$CustomerPhotos item=photo}
                                                <a type="button" onclick="addImageToPixie('{$photo['image_path']}/{$photo['image_name']}')">
                                                    <img src="/{$photo['image_path']}/{$photo['image_name']}" class="img-fluid" />
                                                </a>
                                            {/foreach}
                                        {else}
                                            <a type="button" onclick="addImageToPixie('{$ModulePath}/views/img/default.jpg')">
                                                <img src="{$ModulePath}/views/img/default.jpg" class="img-fluid" />
                                            </a>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <button class="btn-right-menu" id="btn-formati" type="button" data-toggle="collapse" aria-expanded="true" data-target="#collapseOne" aria-controls="collapseOne">
                                    DIMENSIONI E FORMATI <span class="material-icons">add_circle_outline</span> 
                                </button>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#configPanel">
                                    <div class="div-formati">
                                        {assign var="is_custom" value="false" nocache}
                                        {assign var="pricemq_custom_format" value="0.000" nocache}
                                        {assign var="id_custom_format" value="0" nocache}
                                        {foreach from=$ListaFormati item=option}
                                            {if $option["formato_is_custom"] == 0}
                                                <a class="box-formato" data-id="{$option["formato_id"]}" data-price="{$option["formato_prezzo"]}">
                                                    <img src="{$ModulePath}/views/img/formato.svg"><br>
                                                    <span class="small">{$option["formato_small"]}</span>
                                                </a>
                                            {else}
                                                {$is_custom = true}
                                                {$id_custom_format = $option["formato_id"]}
                                                {$pricemq_custom_format = $option["formato_price_mq"]}
                                            {/if}
                                        {/foreach}
                                    </div>
                                    <div class="div-formati-custom">
                                        <!--<div class="col-md-2">
                                            <button type="button" class="btn btn-primary" onclick="resetBtn();">
                                                Reset
                                            </button>
                                        </div>-->
                                        {if $is_custom}
                                            <a class="btn-position-custom" onclick="customPosition()">
                                                Riposiziona
                                            </a>
                                            <a class="btn-formato-custom" onclick="modalFormatoCustom({$id_custom_format}, {$pricemq_custom_format})">
                                                Formati custom
                                            </a>
                                            <div class="row">
                                                <input type="hidden" id="name_formato_custom" value="">
                                                <input type="hidden" id="id_formato_custom" value="">
                                                <input type="hidden" id="pricemq_formato_custom" value="">
                                                <input type="hidden" id="price_formato_custom" value="">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                <p class="total-price"><span class="ml-1" id="formato-selezionato"></span></p>
                                                </div>
                                            </div>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div id="baseOptions"></div>
                                <div id="configOptions"></div>
                            </div>
                        </div>
                        <div class="column-footer">
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <p><a href="#r" class="btn-riepilogo" onclick="riepilogo();">Riepilogo</a></p>
                                </div>
                                <div class="col-md-12">
                                    <p class="h3 total-price">Totale: <span id="total-price">{$product.price}</span></p>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="product-quantity" min="1" value="1">
                                        <div class="input-group-btn">
                                            <button type="button" id="btn-addtocart" class="btn btn-primary" onclick="addToCart();" >Aggiungi al carrello</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <p class="small">incl. IVA, costi di spedizione escl.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- formati custom -->

<div class="modal fade bd-example-modal-sm" id="modalformaticustom" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-config modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Formati Custom
                    <a type="button" data-dismiss="modal"><i class="material-icons">close</i></a>
                </h4>                
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="name_formato_custom" value="">
                    <input type="hidden" id="id_formato_custom" value="">
                    <input type="hidden" id="pricemq_formato_custom" value="">
                    <input type="hidden" id="price_formato_custom" value="">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="larghezza">Larghezza <small>Cm</small></label>
                            <input type="number" class="form-control" id="larghezza" value="50">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="altezza">Altezza <small>Cm</small></label>
                            <input type="number" class="form-control" id="altezza" value="120">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p class="total-price">Prezzo: <span id="total-price-custom-format"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-conferma-formato" onclick="addFormatoCustom()" data-dismiss="modal" class="btn btn-primary">Conferma</button>
            </div>
        </div>
    </div>
</div>

<!-- formati custom -->

<!-- base elements -->

<div class="modal fade bd-example-modal-sm" id="modalcarta" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-config modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" data-type="" data-id=""></div>
            <div class="modal-footer"><h4>Aggiunta: <span class="additional"></span></h4></div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm" id="modaltele" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-config modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" data-type="" data-id=""></div>
            <div class="modal-footer"><h4>Aggiunta: <span class="additional"></span></h4></div>
        </div>
    </div>
</div>

<!-- base elements -->

<!-- options elements -->

<div class="modal fade bd-example-modal-sm" id="modaltelaio" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-config modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span></span> <a type="button" data-dismiss="modal"><i class="material-icons">close</i></a></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm" id="modalsupporti" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-config modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span></span> <a type="button" data-dismiss="modal"><i class="material-icons">close</i></a></h4>
            </div>
            <div class="modal-body">
                <div class="box-configurations" data-price="0,00"><img src="{$ModulePath}views/img/ban.svg" />Senza supporto </div>
                <div class="box-configurations" data-price="30,00"><img src="{$ModulePath}views/img/supporti.svg" />Supporto alluminio </div>
                <div class="box-configurations" data-price="20,00"><img src="{$ModulePath}views/img/supporti.svg" />Supporto legno </div>
                <div class="box-configurations" data-price="10,00"><img src="{$ModulePath}views/img/supporti.svg" />Supporto PVC </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm" id="modalcornici" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-config modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span></span> <a type="button" data-dismiss="modal"><i class="material-icons">close</i></a></h4>
            </div>
            <div class="modal-body" data-type=""></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm" id="modalstampe" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-config modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span></span> <a type="button" data-dismiss="modal"><i class="material-icons">close</i></a></h4>
            </div>
            <div class="modal-body" data-type=""></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<!-- options elements -->

<!-- riepilogo -->

<div class="modal fade bd-example-modal-sm" id="riepilogo" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-config modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Riepilogo <a type="button" data-dismiss="modal"><i class="material-icons">close</i></a></h4>
            </div>
            <div class="modal-body">
                <table id="opzioniRiepilogo" class="table table-striped">
                    <thead>
                        <tr>
                        <th scope="col">Opzione</th>
                        <th scope="col">Prezzo</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="{$ModulePath}lib/pixie/pixie-scripts.min.js"></script>
<script>
{literal}

function aspectRatio(format) {
  var res = format.split(" ");
  var x1 = res[0];
  var y1 = res[2];
  var gcd = calc(x1,y1);
  var r1 = x1/gcd;
  var r2 = y1/gcd;
  var ratio = r1+":"+r2;

    console.log(ratio);
  return ratio;
}

function calc(n1,n2) {
  var num1,num2;
  if(n1 < n2){ 
      num1=n1;
      num2=n2;  
   }
   else{
      num1=n2;
      num2=n1;
    }
  var remain=num2%num1;
  while(remain>0){
      num2=num1;
      num1=remain;
      remain=num2%num1;
  }
  return num1;
}  

var pixie = null;
var ratios = "";
{/literal}
                    {foreach from=$ListaFormati item=option}
                        {if $option["formato_is_custom"] == 0}
                        {literal}
                            ratios += '{"ratio": "'+aspectRatio("{/literal}{$option["formato_small"]}{literal}")+'", "name":"{/literal}{$option["formato_small"]}{literal}"},';
                        {/literal}
                        {/if}
                     {/foreach}
                     {literal}

ratios += '{"ratio": "7:5", "name":"70 x 50"},';
ratios = "["+ratios.substring(0, ratios.length - 1)+"]"
function renderingPixie() {
  console.log("EW_customizations", EW_customizations);
  console.log("START", ratios);

    pixie = new Pixie({
        image: EW_IconsUrl + 'default.jpg',
        baseUrl: EW_ModuleUrl + "lib/pixie",
        textureSize: 10000,
        objectDefaults: {
            global: {
                fontFamily: 'Work Sans',
            }
        },
        ui: {
            visible: true,
            mode: 'inline',
            compact: false,
            showExportPanel: false,
            toolbar: {
                hideOpenButton: true,
                hideCloseButton: true,
                hideSaveButton: true,
            },
            nav: {
                position: 'top',
                replaceDefault: true,
                items: [                
                    {name: 'crop', icon: 'crop-custom', action: 'crop'},
                    {name: 'transform', icon: 'transform-custom', action: 'transform'},
                    {name: 'Filtri', icon: 'filter-custom', action: 'filter'},
                    //   {type: 'separator'},
                ]
            },
        },
        tools: {
            filter: {
                replaceDefault: true,
                items: ['grayscale'],
            },
            crop: {
                replaceDefaultPresets: true,
                hideCustomControls: false,
                defaultRatio: '1:1',
                cropZone: {
                  selectable: true,
                  lockMovementX: true,
                  lockMovementY: true,
                  lockScalingX: false,
                  lockScalingY: false,
                  lockUniScaling: false,
                  hasControls: true
                },
                presets: JSON.parse(ratios),
            }
        },
        onSave: function(data, name) {
           EW_final_image = data;
        }
    });

        

  console.log("add crop")
}


{/literal}
</script>
