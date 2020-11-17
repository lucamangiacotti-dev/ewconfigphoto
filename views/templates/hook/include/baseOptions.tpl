
{* Inizio blocco opzioni *}

<div class="col-md-12">
    <a class="btn btn-primary" data-toggle="collapse" href="#baseInfo{$IdAttribute}_{$idParent}" role="button" aria-expanded="false" aria-controls="collapseExample">
        Show options
    </a>
    <button class="btn btn-danger float-right"
        onclick="configProductToJoin({$IdAttribute}, {$idParent}, 0, 0, 'removeBaseProduct')">
        Remove element
    </button>
    <div class="collapse mb-3" id="baseInfo{$IdAttribute}_{$idParent}">
        <div class="contianer-fluid card card-body">
            {assign var="Options" value=$product['options']}

            <div class="row">
                <input type="hidden" value="{$Options->id_jc}" id="opt_id_{$IdAttribute}{$idParent}" >
            </div>
            <div class="row">
                <div class="col-md-2">
                    <fieldset class="form-group">
                        <label class="form-control-label">Price</label>
                        <input type="text" value="{$Options->price}" id="opt_price_{$IdAttribute}{$idParent}"  class="form-control">
                    </fieldset>
                </div>

                <div class="col-md-2">
                    <fieldset class="form-group">
                        <label class="form-control-label">Discount</label>
                        <input type="text" value="{$Options->discount}" id="opt_discount_{$IdAttribute}{$idParent}"  class="form-control">
                    </fieldset>
                </div>

                <div class="col-md-2">
                    <fieldset class="form-group">
                        <label class="form-control-label">Discount Type</label>
                        <input type="text" value="{$Options->discount_type}" id="opt_discount_type_{$IdAttribute}{$idParent}"  class="form-control">
                    </fieldset>
                </div>

                <div class="col-md-2">
                    <fieldset class="form-group hide">
                        <label class="form-control-label">Quantity</label>
                        <input type="text" value="{$Options->quantity}" id="opt_quantity_{$IdAttribute}{$idParent}"  class="form-control">
                    </fieldset>
                </div>

                <div class="col-md-2">
                    <fieldset class="form-group">
                        <label class="form-control-label">Default</label><br>
                        <input type="checkbox" id="opt_isdefault_{$IdAttribute}{$idParent}" class="attribute_default_checkbox" value="{$Options->is_default}" {if $Options->is_default}checked="checked"{/if}>
                    </fieldset>
                    <fieldset class="form-group hide">
                        <label class="form-control-label">Exclusive</label><br>
                        <input type="checkbox" id="opt_isexclusive_{$IdAttribute}{$idParent}" class="attribute_default_checkbox" value="{$Options->is_exclusive}" {if $Options->is_exclusive}checked="checked"{/if}>
                    </fieldset>
                </div>

                <div class="col-md-2"><br>
                    <button class="btn btn-success float-right"
                        onclick="configProductToJoin({$IdAttribute}, {$idParent}, 0, 0, 'saveProduct')">
                        Save options
                    </button>
                </div>
            </div>
        </div>
    </div>
    <br><br>
</div>
{* Fine blocco opzioni *}