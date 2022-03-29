@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">

@endsection
@section('main')

    <div class="content">
        <button type="button" style="display: none"  data-toggle="modal" data-target=".bs-example-modal-lg" id="modal_btn"></button>
        <div class="x_panel">
            <div class="x_title text-center">

                <div class="text-center">
                    <a href="{{route("site.product-location")}}">
                        <button type="button"
                                class="btn btn-primary">
                            <b><i class="icon-ticket"></i></b> Ürün Listesine Git
                        </button>
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                    @if($selected==0)
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                               aria-controls="home" aria-selected="true">Ürün Bilgisi</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab"
                               aria-controls="home" aria-selected="false">Ürün Bilgisi</a>
                        </li>
                    @endif

                    @if($selected==1)
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                               aria-controls="profile" aria-selected="true">Ürün Resimleri</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                               aria-controls="profile" aria-selected="false">Ürün Resimleri</a>
                        </li>
                    @endif

                    @if($selected==2)
                        <li class="nav-item">
                            <a class="nav-link active" id="contact-tab" data-toggle="tab" href="#variant" role="tab"
                               aria-controls="contact" aria-selected="true">Ürün Varyantları  </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="variant-tab" data-toggle="tab" href="#variant" role="tab"
                               aria-controls="contact" aria-selected="false">Ürün Varyantları</a>
                        </li>
                    @endif

                        @if($selected==3)
                            <li class="nav-item">
                                <a class="nav-link active" id="stock-tab" data-toggle="tab" href="#stock" role="tab"
                                   aria-controls="contact" aria-selected="true">Stok Hareketleri</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" id="stock-tab" data-toggle="tab" href="#stock" role="tab"
                                   aria-controls="contact" aria-selected="false">Stok Hareketleri</a>
                            </li>
                        @endif


                        @if($selected==4)
                            <li class="nav-item">
                                <a class="nav-link active" id="location-tab" data-toggle="tab" href="#locations" role="tab"
                                   aria-controls="contact" aria-selected="true">Ürün Konumları</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" id="location-tab" data-toggle="tab" href="#locations" role="tab"
                                   aria-controls="contact" aria-selected="false">Ürün Konumları</a>
                            </li>
                        @endif
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade  @if($selected==0) active show @endif" id="home" role="tabpanel"
                         aria-labelledby="home-tab">
                        <form id="update-product" action="{{route('products.product-update-post')}}" method="post"
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" id="id" name="id" value="{{$product['id']}}">

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Başlık :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="title" id="title"
                                           value="{{$product['title']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Başlık Yazısı">
                                    <span id="title_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Micro-ID :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="micro_id" id="micro_id"
                                           value="{{$product['micro_id']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="MICRO-ID">
                                    <span id="micro_id_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Marka :</label>
                                <div class="col-lg-4">
                                    <select name="brand_id" id="brand_id" class="form-control" onchange="brandSelect()">
                                        <option value="0">Seçiniz</option>

                                        @if($p_brand_id>0)
                                            @foreach($brands as $brand)
                                                <option value="{{$brand['id']}}"
                                                        @if($brand['id']==$p_brand_id) selected @endif>{{$brand['BrandName']}}</option>
                                            @endforeach

                                        @else
                                            @foreach($brands as $brand)
                                                <option value="{{$brand['id']}}"
                                                        @if($brand['id']==$product['brand_id']) selected @endif>{{$brand['BrandName']}}</option>
                                            @endforeach

                                            @endif

                                    </select>
                                    <span id="brand_id_error"></span>
                                </div>
                                <div class="col-lg-1">

                                    <a href="#" class="btn btn-success" onclick="addBrand()"><i class="fa fa-plus-circle primary" style="font-size: 20px"></i></a>
                                    </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Model :</label>
                                <div class="col-lg-4">
                                    <select name="model_id" id="model_id" class="form-control" disabled>
                                        <option value="0">Seçiniz</option>
                                    </select>
                                    <span id="model_id_error"></span>
                                </div>
                                <div class="col-lg-1">
                                    <a href="#" class="btn btn-success" onclick="addModel()"><i class="fa fa-plus-circle primary" style="font-size: 20px"></i></a>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Kategori :</label>
                                <div class="col-lg-4">
                                    <select name="category_id" id="category_id" class="form-control">

                                        @foreach($categories as $category)
                                            <option value="{{$category['id']}}"
                                                    @if($category['id']==$product['category_id']) selected @endif>{{$category['category_name']}}</option>
                                        @endforeach
                                    </select>
                                    <span id="category_id_error"></span>
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Açıklama :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="description" id="description"
                                           value="{{$product['description']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="description_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Fiyat :</label>
                                <div class="col-lg-3">
                                    <input type="number" class="form-control" name="price" id="price"
                                           value="{{$product['price']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="price_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Fiyat (eski) :</label>
                                <div class="col-lg-3">
                                    <input type="number" class="form-control" name="price_ex" id="price_ex"
                                           value="{{$product['price_ex']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="price_ex_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" @if($product['status']==1) checked
                                               @endif name="status" id="status" value="13" data-switchery="true"
                                               style="display: none;">


                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Ürün Güncelleme Sayfasında
                                    kal</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" checked name="stay" id="stay"
                                               value="13" data-switchery="true"
                                               style="display: none;">


                                    </label>
                                </div>
                            </div>
                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">ÜRÜN
                                    GÜNCELLE
                                    <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade @if($selected==1) active show @endif" id="profile" role="tabpanel"
                         aria-labelledby="profile-tab">

                        <form id="add-image" action="{{route('products.add-product-image-post')}}" method="post"
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" id="product_id" name="product_id" value="{{$product['id']}}">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Resim</label>
                                <div class="col-lg-8">
                                    <input type="file" name="image" id="image" class="form-control h-auto"
                                           data-popup="tooltip"
                                           title=""
                                           onchange="showImage('image','target','avatar_img')"
                                           placeholder="">
                                    <span id="image_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold"></label>
                                <div class="col-lg-8">

                                    <img id="target" style="display: none;">
                                    <img id="avatar_img" style="display: none;">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Sıra</label>
                                <div class="col-lg-1">
                                    <select name="order" id="order" class="form-control">
                                        @for($i=1;$i<$image_count;$i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" checked="true" name="status"
                                               id="status" value="13" data-switchery="true"
                                               style="display: none;">


                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">İlk Resim</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" checked="true" name="first" id="first"
                                               value="13" data-switchery="true"
                                               style="display: none;">


                                    </label>
                                </div>
                            </div>
                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">RESİM EKLE
                                    <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>

                        <div class="row" style="margin-top: 50px">

                            @foreach($product->images()->get() as $image)
                                <div class="col-md-2 text-center">
                                    <table width="100%">
                                        <tr>
                                            <td height="100"><img src="{{url($image['thumb'])}}" alt=""></td>
                                        </tr>
                                        <tr>
                                            <td><select name="order_{{$image['id']}}" id="order_{{$image['id']}}"
                                                        onchange="changeOrder(this.value,{{$image['id']}})"
                                                        class="form-control">
                                                    @for($i=1;$i<$image_count-1;$i++)
                                                        <option value="{{$i}}"
                                                                @if($image['order']==$i) selected @endif>{{$i}}</option>
                                                    @endfor

                                                </select></td>
                                        </tr>
                                        <tr>
                                            <td>

                                                <label class="">
                                                    <div class="iradio_flat-green checked" style="position: relative;">
                                                        <input type="radio" class="flat"   @if($image['first']==1)) checked
                                                               @endif name="first"
                                                               style="position: absolute; opacity: 0;">
                                                        <ins class="iCheck-helper" onclick="changeFirst({{$image['id']}})"
                                                             style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                                    </div>

                                                </label>


                                            </td>
                                        </tr>
                                        <tr><td>
                                                <button class="btn btn-danger" onclick="deleteImage({{$image['id']}})"><i class="fa fa-close"></i></button>
                                            </td></tr>
                                    </table>


                                </div>
                            @endforeach

                        </div>

                    </div>
                    <div class="tab-pane fade @if($selected==2) active show @endif" id="variant" role="tabpanel"
                         aria-labelledby="variant-tab">
                        <div class="row">
                            <div class="col-12">
                        <form id="colors-form" action="{{route('products.color-product-post')}}" method="post"
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" id="product_id" name="product_id" value="{{$product['id']}}">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Mevcut Renkler :</label>
                                <div class="col-lg-8">
                                    <input type="hidden" name="color_list" id="color_list" value="{{$c_value}}">
                                    <table width="100%">
                                        <tr>

                                            @foreach($colors as $color)
                                                <td style="width:100px ">
                                                    {{$color['color_name']}}<br>
                                                    <label class="">
                                                        <div class="icheckbox_flat-green" style="position: relative;">
                                                            <input type="checkbox" id="c{{$color['id']}}"
                                                                   name="c{{$color['id']}}"
                                                                   @if(in_array($color['id'],$product_colors)) checked
                                                                   @endif class="flat"
                                                                   style="position: absolute; opacity: 0;">

                                                            <ins class="iCheck-helper"
                                                                 onclick="eklecikar('c{{$color['id']}}','color_list')"
                                                                 style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">

                                                            </ins>
                                                        </div>
                                                    </label>
                                                    <div
                                                        style="width: 100%;height: 50px; background: {{$color['color_code']}}"></div>
                                                </td>
                                            @endforeach
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-md-2"></div>
                            <div class="col-md-10">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">RENK
                                    GÜNCELLE
                                    </button>

                            </div></div>
                        </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <form id="memories-form" action="{{route('products.memory-product-post')}}" method="post"
                                      enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <input type="hidden" id="product_id" name="product_id" value="{{$product['id']}}">
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Mevcut Hafıza Tipleri :</label>
                                        <div class="col-lg-8">
                                            <input type="hidden" name="memory_list" id="memory_list" value="{{$m_value}}">
                                            <table width="100%">
                                                <tr>

                                                    @foreach($memories as $memory)
                                                        <td style="width:100px ">
                                                            {{$memory['memory_value']}} GB<br>
                                                            <label class="">
                                                                <div class="icheckbox_flat-green" style="position: relative;">
                                                                    <input type="checkbox" id="m{{$memory['id']}}"
                                                                           name="m{{$memory['id']}}"
                                                                           @if(in_array($memory['id'],$product_memories)) checked
                                                                           @endif class="flat"
                                                                           style="position: absolute; opacity: 0;">

                                                                    <ins class="iCheck-helper"
                                                                         onclick="eklecikar('m{{$memory['id']}}','memory_list')"
                                                                         style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">

                                                                    </ins>
                                                                </div>
                                                            </label>

                                                        </td>
                                                    @endforeach
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <div class="col-md-2"></div>
                                        <div class="col-md-10">
                                            <button type="submit" class="btn btn-primary font-weight-bold rounded-round">HAFIZA
                                                GÜNCELLE
                                            </button>

                                        </div></div>
                                </form>
                            </div>
                        </div>




                        <form id="product-values-form" action="{{route('products.product-variant-value-post')}}" method="post"
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" id="product_id" name="product_id" value="{{$product['id']}}">
                        <div class="row">

                                @foreach($variant_groups as $group)
                                <div class="col-4">
                                    <div class="x_panel">
                                    <div class="x_title"><h2>{{$group['group_name']}}</h2>
                                        <div class="text-right">
                                                <a href="#" class="btn btn-success" onclick="addVariant({{$group['id']}})">
                                                    <i class="fa fa-plus-circle primary" style="font-size: 20px"></i></a>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">

                                        @foreach($group->variants()->get() as $variant)
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label font-weight-semibold"><b>{{$variant['variant_name']}}</b></label>
                                                <div class="col-lg-6">

                                                    <select name="variant_value_{{$variant['id']}}" id="variant_value_{{$variant['id']}}" class="form-control">
                                                        <option value="0">Seçiniz</option>
                                                        @foreach($variant->values()->get() as $val)
                                                            <option value="{{$val['id']}}"  @if(in_array($val['id'],$variant_value_array)) selected @endif>{{$val['value']}}</option>
                                                        @endforeach
                                                    </select>


                                                    <span id="variant_value_{{$variant['id']}}_error"></span>
                                                </div>
                                                <div class="col-1">
                                                    @if($variant['binary']==0)
                                                    <a href="#" class="btn btn-success" onclick="addValue({{$variant['id']}})">
                                                        <i class="fa fa-plus-circle primary" style="font-size: 20px"></i></a>
                                                        @endif
                                                </div>
                                            </div>
                                        @endforeach


                                    </div>
                                    </div>
                                    </div>
                                @endforeach

                        </div>
                            <div class="row">
                                <div class="col-12 text-center">

                                    <button type="submit" class="btn btn-primary font-weight-bold rounded-round">DEĞERLERİ
                                        GÜNCELLE
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade @if($selected==3) active show @endif" id="stock" role="tabpanel"
                         aria-labelledby="stock-tab">


                        @include('admin.products.stock_movements')

                    </div>
                    <div class="tab-pane fade @if($selected==4) active show @endif" id="locations" role="tabpanel"
                         aria-labelledby="stock-tab">
                        @include('admin.products.product_locations')


                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
@section('scripts')
    <script src="{{url("js/save.js")}}"></script>
    <script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
    <script src="{{url('vendors/iCheck/icheck.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
    <script src="{{url('vendors/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>

    <script>

        $(document).ready(function () {

            brandSelect();
            init_DataTables();


            if ($("input.flat")[0]) {
                $(document).ready(function () {
                    $('input.flat').iCheck({
                        checkboxClass: 'icheckbox_flat-green',
                        radioClass: 'iradio_flat-green'
                    });
                });
            }
        });

        function eklecikar(kutu, inputAlan) {

            if (!$('#' + kutu).is(':checked')) {
                document.getElementById(inputAlan).value += "@" + kutu;
            } else {
                document.getElementById(inputAlan).value = document.getElementById(inputAlan).value.replace("@" + kutu, "");
            }
        }

        function get_location_order(location_id) {
            //    console.log("{{url('admin/site/product/get-location-order')}}/{{$product['id']}}/"+location_id );

            $.get("{{url('admin/site/product/get-location-order')}}/{{$product['id']}}/"+location_id   , function (data) {
                if(data=='no'){
                    swal("Bu ürün ilgili alana konumlandırılmış");
                }else{
                    $('#location_order').prop('disabled',false);
                    $('#location_order').html(data);
                }

            });
        }

        function deleteLocation(location_id) {
            swal("Ürün konumdan silinecek, Emin misiniz?", {
                buttons: ["İptal", "Evet"],
                dangerMode: true,
            }).then((value) => {
                if (value) {

                    $.get("{{url('admin/site/product/delete-product-location')}}/" + location_id, function (data) {
                        swal(data + "");
                        setTimeout(function () {
                            window.open('{{url('admin/site/product/update/'.$product['id'].'/2')}}', '_self')
                        }, 1500)

                        //   console.log(user_id+":"+follower_id);


                    });


                }
            })
        }

        function showImage(img, t, hide_it, w = 0, h = 0) {
            $('#' + hide_it).hide();
            $('#' + img).show();
            var src = document.getElementById(img);
            var target = document.getElementById(t);
            var val = $('#' + img).val();
            var arr = val.split('\\');
            $('#' + img + '_error').html("");
            $('#' + t).show();


            $.get("{{url('/check_image')}}/" + arr[arr.length - 1], function (data) {
                //alert(data);
                if (data === 'ok') {
                    $('#' + img + '_error').html("");
                    $('#' + t).show();
                    var fr = new FileReader();
                    fr.onload = function (e) {

                        var image = new Image();
                        image.src = e.target.result;

                        image.onload = function (e) {

                            if (w > 0 || h > 0) {
                                var error = false;
                                var txt = "";
                                if (w != this.width) {
                                    txt += "Dosya genişliği " + w + "px ";
                                    error = true;
                                }

                                if (h != this.height) {
                                    if (error) {
                                        txt += "ve ";
                                    }
                                    txt += "Dosya yüksekliği " + h + "px ";
                                    error = true;
                                }
                                if (error) {
                                    $('#' + img + '_error').html('<span style="color: red">' + txt + ' olmalıdır </span>');
                                    //swal("admin.image_wrong_format");
                                    $('#' + img).val('');
                                    $('#' + t).hide();
                                    $('#' + hide_it).hide();
                                    return false;
                                }


                                // swal(this.width+"x"+this.height);
                                // $('#'+img+'_error').html("ölçü yanlş");
                                // $('#'+t).hide();
                                return false;
                            }
                            //  $('#imgresizepreview, #profilepicturepreview').attr('src', this.src);
                        };

                        target.src = this.result;
                    };
                    fr.readAsDataURL(src.files[0]);
                } else {
                    $('#' + img + '_error').html('<span style="color: red">Yanlış dosya biçimi</span>');
                    //swal("admin.image_wrong_format");
                    $('#' + img).val('');
                    $('#' + t).hide();
                    $('#' + hide_it).hide();


                }
            });


        }

        function brandSelect() {
            var brand_id = $('#brand_id').val();
            $('#questions_div').html('');
            if (brand_id > 0) {
                $('#model_id').show();
                $('#model_result').html('');

                @if($p_model_id>0)
                    console.log("{{url('data/get-models')}}/" + brand_id + "/{{$p_model_id}}");
                $.get("{{url('data/get-models')}}/" + brand_id + "/{{$p_model_id}}", function (data) {


                    if (data == "none") {
                        swal("model bulunamadı, lütfen ekleyin");
                        $('#model_id').html('<option value="0">Seçiniz</option>');
                        //  $('#model_id').hide();
                    } else {
                        $('#model_id').prop("disabled", false);
                        $('#model_id').html(data);
                    }
                });
                @else
                $.get("{{url('data/get-models')}}/" + brand_id + "/{{$product['model_id']}}", function (data) {


                    if (data == "none") {
                        swal("model bulunamadı, lütfen ekleyin");
                        $('#model_id').html('<option value="0">Seçiniz</option>');
                        //  $('#model_id').hide();
                    } else {
                        $('#model_id').prop("disabled", false);
                        $('#model_id').html(data);
                    }
                });
                @endif

            } else {
                $('#model_id').prop("disabled", true);
                $('#model_id').html('');
            }
        }

        function modelSelect() {

            var model_id = $('#model_id').val();
            if (model_id > 0) {

                $.get("{{url('get-category')}}/" + model_id, function (data) {
                    $('#category_id').prop('disabled',false);

                    $('#category_id').val(data);
                    //  $('#color_div').html('');

                });
            } else {
                $('#category_id').prop('disabled',true);
            }

        }

        function changeOrder(order, img_id) {
            $.get("{{url('admin/products/change-image-order')}}/" + order + "/" + img_id, function (data) {
                swal(data + "");
                setTimeout(function () {
                    window.open('{{url('admin/products/update/'.$product['id'].'/1')}}', '_self')
                }, 1500)

            });
        }

        function changeFirst(img_id) {
            $.get("{{url('admin/products/change-first')}}/"  + img_id, function (data) {
                swal(data + "");
                setTimeout(function () {
                    window.open('{{url('admin/products/update/'.$product['id'].'/1')}}', '_self')
                }, 1500)

            });
        }

        function changeLocationOrder(location_id, new_order){
            $.get("{{url('admin/products/change-product-location-order')}}/"  + location_id+"/"+new_order, function (data) {
                swal(data + "");
                setTimeout(function () {
                    window.open('{{url('admin/products/update/'.$product['id'].'/2')}}', '_self')
                }, 1500)

            });
        }

        function deleteImage(img_id) {
            swal("Resim silinecek, Emin misiniz?", {
                buttons: ["İptal", "Evet"],
                dangerMode: true,
            }).then((value) => {
                if (value) {

                    $.get("{{url('admin/products/delete-image')}}/" + img_id, function (data) {
                        swal(data + "");
                        setTimeout(function () {
                            window.open('{{url('admin/products/update/'.$product['id'].'/1')}}', '_self')
                        }, 1500)

                        //   console.log(user_id+":"+follower_id);


                    });


                }
            })
        }

        $('#update-product').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;

            if ($('#title').val() == '') {
                $('#title_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#title_error').html('');
            }
            if ($('#category_id').val() == 0) {
                $('#category_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
                error = true;
            } else {
                $('#category_id_error').html('');
            }
            if ($('#brand_id').val() == 0) {
                $('#brand_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
                error = true;
            } else {
                $('#brand_id_error').html('');
            }
            if ($('#model_id').val() == 0) {
                $('#model_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
                error = true;
            } else {
                $('#model_id_error').html('');
            }
            if ($('#price').val() == 0) {
                $('#price_error').html('<span style="color: red">Lütfen fiyat giriniz</span>');
                error = true;
            } else {
                $('#price_error').html('');
            }
            if (error) {
                return false;
            } else {
                save(formData, '{{route('products.product-update-post')}}', '', '', '');
            }
        });

        $('#add-image').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;

            if ($('#image').val() == '') {
                $('#image_error').html('<span style="color: red">Lütfen resim seçiniz</span>');
                error = true;
            } else {
                $('#image_error').html('');
            }


            if (error) {
                return false;
            } else {
                save(formData, '{{route('products.add-product-image-post')}}', '', '', '');
            }
        });

        $('#colors-form').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;




            if (error) {
                return false;
            } else {
                save(formData, '{{route('products.color-product-post')}}', '', '', '');
            }
        });

        $('#memories-form').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;

            if (error) {
                return false;
            } else {
                save(formData, '{{route('products.memory-product-post')}}', '', '', '');
            }
        });


        $('#product-values-form').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;




            if (error) {
                return false;
            } else {
                 save(formData, '{{route('products.product-variant-value-post')}}', '', '', '');
            }
        });

        function addBrand(){
            $('#modal_btn').click();
            $.get("{{url('admin/products/add-brand')}}/{{$product['id']}}/" , function (data) {
                $('#lg-modal-title').html('Yeni Marka Ekle');
                $('#lg-modal-body').html(data);



            });
        }

        function addModel(){
            if($('#brand_id').val()==0){
                swal('Marka Seçiniz');
            }else {
                $('#modal_btn').click();
                $.get("{{url('admin/products/add-model')}}/"+$('#brand_id').val()+"/{{$product['id']}}" , function (data) {
                    $('#lg-modal-title').html('Yeni Model Ekle');
                    $('#lg-modal-body').html(data);

                });

            }

        }

        function addVariant(group_id){

                $('#modal_btn').click();
                $.get("{{url('admin/products/add-variant')}}/"+group_id+"/{{$product['id']}}" , function (data) {
                    $('#lg-modal-title').html('Yeni Varyant Ekle');
                    $('#lg-modal-body').html(data);

                });



        }
        function updateStock(stock_id){

                $('#modal_btn').click();
                $.get("{{url('admin/products/update-stock')}}/"+stock_id , function (data) {
                    $('#lg-modal-title').html('Stok Hareketi Güncelle');
                    $('#lg-modal-body').html(data);

                });



        }

        function addValue(variant_id){

                $('#modal_btn').click();
                $.get("{{url('admin/products/add-variant-value')}}/"+variant_id+"/{{$product['id']}}" , function (data) {
                    $('#lg-modal-title').html('Yeni Varyant Değeri Ekle');
                    $('#lg-modal-body').html(data);

                });



        }


            $('#add-stock').submit(function (e) {
                e.preventDefault();

                var formData = new FormData(this);

                var error = false;

                if ($('#quantity').val() <= 0 ) {
                    $('#quantity_error').html('<span style="color: red">Miktar Pozitif Sayı olmalıdır</span>');
                    error = true;
                } else {
                    $('#quantity_error').html('');
                }


                if ($('#color_id').val() == 0 ) {
                    $('#color_id_error').html('<span style="color: red">Renk Seçiniz</span>');
                    error = true;
                } else {
                    $('#color_id_error').html('');
                }


                if ($('#memory_id').val() <= 0 ) {
                    $('#memory_id_error').html('<span style="color: red">Hafıza Seçiniz</span>');
                    error = true;
                } else {
                    $('#memory_id_error').html('');
                }

                if (error) {
                    return false;
                } else {
                    //swal("ok"+count);'/check-stock/{product_id}/{color_id}/{memory_id}/{qty}/{stock_id}'


if(formData.get('status')=='out'){
    console.log("{{url('admin/products/check-stock')}}/{{$product['id']}}/"+$('#color_id').val()+"/"+$('#memory_id').val()+"/"+$('#quantity').val());
                    $.get("{{url('admin/products/check-stock')}}/{{$product['id']}}/"+$('#color_id').val()+"/"+$('#memory_id').val()+"/"+$('#quantity').val() , function (data) {
                        if(data=='ok'){
                          save(formData, '{{route('products.product-stock-movement')}}', '', '', '');
                        }else{
                            swal(""+data);
                            console.log("{{url('admin/products/check-stock')}}/{{$product['id']}}/"+$('#color_id').val()+"/"+$('#memory_id').val()+"/"+$('#quantity').val());
                        }
                    });
}else{
      save(formData, '{{route('products.product-stock-movement')}}', '', '', '');
}

                }
            });
//////////////////////locations

        function get_location_order(location_id) {
            //    console.log("{{url('admin/site/product/get-location-order')}}/{{$product['id']}}/"+location_id );

            $.get("{{url('admin/site/product/get-location-order')}}/{{$product['id']}}/"+location_id   , function (data) {
                if(data=='no'){
                    swal("Bu ürün ilgili alana konumlandırılmış");
                }else{
                    $('#location_order').prop('disabled',false);
                    $('#location_order').html(data);
                }

            });
        }


        function deleteLocation(location_id) {
            swal("Ürün konumdan silinecek, Emin misiniz?", {
                buttons: ["İptal", "Evet"],
                dangerMode: true,
            }).then((value) => {
                if (value) {

                    $.get("{{url('admin/site/product/delete-product-location')}}/" + location_id, function (data) {
                        swal(data + "");
                        setTimeout(function () {
                            window.open('{{url('admin/products/update/'.$product['id'].'/4')}}', '_self')
                        }, 1500)

                        //   console.log(user_id+":"+follower_id);


                    });


                }
            })
        }



        function changeLocationOrder(location_id, new_order){
            $.get("{{url('admin/site/product/change-product-location-order')}}/"  + location_id+"/"+new_order, function (data) {
                swal(data + "");
                setTimeout(function () {
                    window.open('{{url('admin/products/update/'.$product['id'].'/4')}}', '_self')
                }, 1500)

            });
        }



        $('#add-location').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;

            if ($('#location_id').val() == 0) {
                $('#location_id_error').html('<span style="color: red">Lütfen konum seçiniz</span>');
                error = true;
            } else {
                $('#location_id_error').html('');
            }


            if (error) {
                return false;
            } else {
                save(formData, '{{route('site.add-product-location-post')}}', '', '', '');
            }
        });

    </script>
@endsection
