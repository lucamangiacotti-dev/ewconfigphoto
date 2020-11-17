
{* Inizio blocco opzioni *}

<div class="col-md-12 p-0">
    <div class="contianer-fluid card card-body">
        {assign var="Options" value=$product['options']}

        <div class="row">
            <input type="hidden" value="{$Options->id_jc}" id="opt_id_{$IdAttribute}{$IdBaseProduct}{$idChild}" >
        </div>
        <div class="row">
            <div class="col-md-2">
                <fieldset class="form-group">
                    <label class="form-control-label">Price</label>
                    <input type="text" value="{$Options->price}" id="opt_price_{$IdAttribute}{$IdBaseProduct}{$idChild}"  class="form-control">
                </fieldset>
            </div>

            <div class="col-md-2">
                <fieldset class="form-group">
                    <label class="form-control-label">Discount</label>
                    <input type="text" value="{$Options->discount}" id="opt_discount_{$IdAttribute}{$IdBaseProduct}{$idChild}"  class="form-control">
                </fieldset>
            </div>

            <div class="col-md-2">
                <fieldset class="form-group">
                    <label class="form-control-label">Discount Type</label>
                    <input type="text" value="{$Options->discount_type}" id="opt_discount_type_{$IdAttribute}{$IdBaseProduct}{$idChild}"  class="form-control">
                </fieldset>
            </div>

            <div class="col-md-2">
                <fieldset class="form-group hide">
                    <label class="form-control-label">Quantity</label>
                    <input type="text" value="{$Options->quantity}" id="opt_quantity_{$IdAttribute}{$IdBaseProduct}{$idChild}"  class="form-control">
                </fieldset>
            </div>

            <div class="col-md-2">
                <fieldset class="form-group">
                    <label class="form-control-label">Default</label><br>
                    <input type="checkbox" id="opt_isdefault_{$IdAttribute}{$IdBaseProduct}{$idChild}" class="attribute_default_checkbox" value="{$Options->is_default}" {if $Options->is_default}checked="checked"{/if}>
                </fieldset>
                <fieldset class="form-group hide">
                    <label class="form-control-label">Exclusive</label><br>
                    <input type="checkbox" id="opt_isexclusive_{$IdAttribute}{$IdBaseProduct}{$idChild}" class="attribute_default_checkbox" value="{$Options->is_exclusive}" {if $Options->is_exclusive}checked="checked"{/if}>
                </fieldset>
            </div>

            <div class="col-md-2"><br>
                <button class="btn btn-success float-right"
                    onclick="configProductToJoin({$IdAttribute}, {$idParent}, {$idChild}, 0, 'saveProduct')">
                    Save options
                </button>
            </div>
        </div>
    </div>
</div>
{* Fine blocco opzioni *}