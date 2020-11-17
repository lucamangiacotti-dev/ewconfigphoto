<div class="container-fluid">
    <div class="row">
        <div class="col-xl-4 col-lg-4">
            <p><i class="material-icons" style="font-size:64px">collections</i></p>
            <p>{l s="Abilita personalizzatore per questo prodotto" d='Modules.Elwood'}</p>
            <label id="is-customizable" for="is-customizable-input">
                <input 
                    data-toggle="switch" class="switch-input-lg"
                    data-inverse="true" type="checkbox"
                    name="is-customizable" id="is-customizable-input"
                    {if $isCustomizabled}
                    checked="checked">
                    {else}
                        >
                    {/if}
            </label>
            <br>
        </div>
        <div class="col-xl-4 col-lg-4">
        </div>
    </div>

    {if $idCategoryParent == $idCategoryOfElements}
        <div class="row" style="margin-top: 100px">
            <div class="col-xl-4 col-lg-4">
                <p><i class="material-icons" style="font-size:64px">layers</i></p>
                <p>{l s="Il prodotto è un elemento di un prodotto finale? " d='Modules.Elwood'}</p>
                <label id="is-element" for="is-element-input">
                    <input 
                        data-toggle="switch" class="switch-input-lg"
                        data-inverse="true" type="checkbox"
                        name="is-element" id="is-element-input"
                        {if $isElement}
                        checked="checked">
                        {else}
                            >
                        {/if}
                </label>
                <br>
            </div>
            <div class="col-xl-4 col-lg-4">
                <p><i class="material-icons" style="font-size:64px">filter_1</i></p>
                <p>{l s="E' un prodotto primario (base)?" d='Modules.Elwood'}</p>
                <label id="is-base-product" for="is-base-product-input">
                    <input 
                        data-toggle="switch" class="switch-input-lg"
                        data-inverse="true" type="checkbox"
                        name="is-base-product" id="is-base-product-input"
                        {if $isBaseProduct}
                        checked="checked">
                        {else}
                            >
                        {/if}
                </label>
                <br>
            </div>
        </div>
    {/if}
</div>