
{if $IsCustomizabled == true}

{$IdAttribute}

<div class="container-fluid" style="margin:30px 0px 50px 0px;">
    <div class="row">
        <div class="col-md-3">
            <label>E' un formato personalizzato?</label>
            <select id="is_formato_custom_{$IdAttribute}"  class="custom-select custom-select">
                <option value="0">NO</option>   
                <option value="1">SI</option>          
            </select>
            <br><br>
            <button type="submit" class="btn btn-primary uppercase" onclick="saveFormatoCustom('{$IdAttribute}')">Salva impostazione</button>
        </div>
        <div class="col-md-4">
            <label>Prezzo al metro quadrato <small>(Iva escl.)</small></label>
            <div class="input-group money-type">
                <div class="input-group-prepend">
                    <span class="input-group-text"> €</span>
                </div>
                <input type="text" id="price_mq_formato_custom_{$IdAttribute}" class="attribute_unity form-control" value="{$PriceMqFormatoCustom}">
                <div class="input-group-append">
                    <span class="input-group-text">/ 1 m²</span>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="alert alert-warning mt-3 mb-3" role="alert">
                Specificare sempre questa opzione. Se la scelta è SI inserire un prezzo al metro quadrato. 
            </div>
        </div>
    </div>
    <hr>
</div>


<div class="container-fluid" style="margin:20px 0px 100px 0px;">
    <div class="row">
        <div class="col-md-12">
            <div class="panel-heading">
                <h3>Configurazione elementi</h3>

                <input type="hidden" value="" name="" id="">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <ul class="horizontal-tabs">
                {foreach $ListOfBasicProducts as $el}
                    <li class="tab-item"> 
                        <a onclick="openTab(event, 'tab{$IdAttribute}_{$el['id']}');" class="tab-link text-uppercase">{$el['name']}</a>
                    </li>
                {/foreach}
            </ul>
            <div class="horizontal-tabs-content">

                {foreach $ListOfBasicProducts as $baseProduct}
                    {assign var="IdBaseProduct" value=$baseProduct['id']}

                    <div id="tab{$IdAttribute}_{$IdBaseProduct}" class="tabcontent">

                        {* controllo se è presente un record nella tabella join combinations per questo prodotto *}
                        {if $baseProduct['is_checked'] == false}
                            <div class="col-md-12">
                                <a class="btn btn-outline-secondary" href="&combid={$IdAttribute}"
                                    onclick="configProductToJoin({$IdAttribute}, {$baseProduct['id']}, 0, 0, 'addProduct')">
                                    <i class="material-icons">launch</i> Attiva prodotto</a>

                                <div class="alert alert-warning mt-3 mb-3" role="alert">
                                    Attiva almeno un prodotto base per procedere alle combinazioni
                                </div>
                            </div>
                        {else}
                            {* blocco opzioni *}
                            {include file='module:ewphotocustomizer/views/templates/hook/include/baseOptions.tpl' product=$baseProduct idParent=$IdBaseProduct idChild=0 } 
                            <hr>

                            {* lista combinazioni base *}
                            <div class="col-md-12 mt-3">
                            {foreach $baseProduct['listaComboProdottiBase'] as $combo}

                                <a class="btn btn-outline-secondary" data-toggle="collapse" href="#combo{$IdAttribute}{$IdBaseProduct}{$combo['id']}" 
                                    style="width:90%"  role="button" aria-expanded="false" aria-controls="collapseExample">
                                    {$combo['name']}
                                </a>

                                {if $combo['is_checked'] == false}
                                    <button class="btn btn-success float-right ml-2" id="addCombo{$IdAttribute}{$IdBaseProduct}{$combo['id']}"
                                        onclick="configProductToJoin({$IdAttribute}, {$baseProduct['id']}, {$combo['id']}, 0, 'addCombo')"">
                                        <i class="material-icons">power_settings_new</i>
                                    </button>
                                {else}
                                    <button class="btn btn-danger float-right ml-2" id="delCombo{$IdAttribute}{$IdBaseProduct}{$combo['id']}"
                                        onclick="configProductToJoin({$IdAttribute}, {$baseProduct['id']}, {$combo['id']}, 0, 'removeComboProduct')"">
                                        <i class="material-icons">clear</i>
                                    </button>
                                {/if}

                                <div class="collapse mt-1 {if $combo['is_checked'] == false}hide{/if}" id="combo{$IdAttribute}{$IdBaseProduct}{$combo['id']}">
                                    {include file='module:ewphotocustomizer/views/templates/hook/include/comboOptions.tpl' product=$combo idParent=$IdBaseProduct idChild=$combo['id'] }
                                    <hr>
                                    <div class="col-md-12 mt-3">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="material-icons">search</i></span>
                                            </div>
                                            <select class="form-control js-combo-select-ajax" id="search_{$IdAttribute}{$IdBaseProduct}{$combo['id']}"
                                                    data-idattr="{$IdAttribute}"
                                                    data-idbase="{$IdBaseProduct}"
                                                    data-idcombo="{$combo['id']}"
                                            ></select>
                                            
                                            <div class="input-group-append">
                                                <button class="input-group-text" onclick="addNewElement({$IdAttribute}, {$IdBaseProduct}, {$combo['id']})">
                                                    Inserisci <i class="material-icons">add</i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        {foreach $combo['combinationList'] as $combination}
                                            <h3 class="mt-4">{$combination['name']}</h3>
                                            {include file='module:ewphotocustomizer/views/templates/hook/include/combinationOptions.tpl' product=$combination idParent=$combo['id'] idChild=$combination['id_child'] idCombo={$combination['id']}}
                                        {/foreach}
                                    </div>
                                </div>
                                <br><br>
                            {/foreach}
                            </div>
                        {/if}
                        
                    </div>
                {/foreach}
            </div>
        </div>
    </div>
</div>

<script>
    var EW_BaseUrl = '{$BaseUrl}';
    var EW_IdProduct = '{$IdProduct}';

    $(document).ready(function(){
        $('#is_formato_custom_{$IdAttribute} option[value={$IsFormatoCustom}]').attr('selected','selected');
    });
</script>

{/if}

