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
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#configurator">
            Configura il prodotto
        </button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="configurator" tabindex="-1" role="dialog" aria-labelledby="configuratorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <p class="modal-title" id="exampleModalLabel">Nome del prodotto</p>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="material-icons">close</i>
            </button>
        </div>
        <div class="modal-body p-0">
            <div class="row">
                <div class="col-md-9 border-right-dark" >
                    <div class="picture-container">

                        <pixie-editor></pixie-editor>


                    </div>
                </div>
                <div class="col-md-3">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <button class="btn-right-menu" type="button" data-toggle="collapse" aria-expanded="true" data-target="#collapseOne" aria-controls="collapseOne">
                                Tipologia prodotto
                            </button>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="pt-1 pb-1 border-bottom-dark">
                                    <span class="box-example"></span>
                                    <span class="box-example"></span>
                                    <span class="box-example"></span>
                                    <span class="box-example"></span>
                                    <span class="box-example"></span>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <button class="btn-right-menu" type="button" data-toggle="collapse" aria-expanded="false" data-target="#collapse2" aria-controls="collapse2">
                                Le mie foto
                            </button>
                            <div id="collapse2" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="pt-1 pb-1 border-bottom-dark">
                                    <span class="badge badge-danger"><i class="material-icons">remove</i></span>
                                    <span class="box-example"><i class="material-icons">add</i></span>
                                    <span class="box-example"></span>
                                    <span class="box-example"></span>
                                    <span class="box-example"></span>
                                    <span class="box-example"></span>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
</div>

<script src="{$ModulePath}lib/pixie/pixie-scripts.min.js"></script>

<script type="text/javascript">

var modulePath = "{$ModulePath}";
var defaultImage = modulePath + 'lib/pixie/assets/images/samples/sample2.jpg';
var defaultThumbnail = modulePath + 'lib/pixie/assets/images/samples/sample2_thumbnail.jpg';

{literal}

var pixie = new Pixie({
    image: modulePath + 'lib/pixie/assets/images/samples/sample2.jpg',
    ui: {
        visible: true,
        mode: 'inline',
        openImageDialog: false,
    },
    onLoad: function() {
        //can be called at any time to change editor mode
       // pixie.setConfig('ui.mode', 'inline');
    },
    openImageDialog: {
        show: false,
        replaceDefaultSampleImages: false,
        sampleImages: [
            {
                url: defaultImage,
                thumbnail: defaultThumbnail,
            }
        ]
    },
});

{/literal}
</script>
