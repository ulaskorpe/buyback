@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
@endsection
@section('main')
    <div class="content">



        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-body">

                        <div class="text-center">
                            <a href="{{route("site.product-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Ürün Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="create-product" action="{{route('site.create-product-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Başlık :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="title" id="title"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Başlık Yazısı">
                                    <span id="title_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Kategori :</label>
                                <div class="col-lg-4">
                                    <select name="category_id" id="category_id" class="form-control" >
                                        <option value="0">Seçiniz</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category['id']}}">{{$category['category_name']}}</option>
                                        @endforeach
                                    </select>
                                    <span id="category_id_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Marka :</label>
                                <div class="col-lg-4">
                                    <select name="brand_id" id="brand_id" class="form-control" onchange="brandSelect()">
                                        <option value="0">Seçiniz</option>
                                        @foreach($brands as $brand)
                                            <option value="{{$brand['id']}}">{{$brand['BrandName']}}</option>
                                        @endforeach
                                    </select>
                                    <span id="brand_id_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Model :</label>
                                <div class="col-lg-4">
                                    <select name="model_id" id="model_id" class="form-control" disabled  >
                                        <option value="0">Seçiniz</option>
                                    </select>
                                    <span id="model_id_error"></span>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Mevcut Renkler :</label>
                                <div class="col-lg-8">
                                    <input type="hidden" name="color_list" id="color_list">
                                  <table width="100%"><tr>

                                   @foreach($colors as $color)
                                            <td style="width:100px ">
                                                {{$color['color_name']}}<br>
                                        <label class="">
                                            <div class="icheckbox_flat-green" style="position: relative;">
                                                <input type="checkbox"  id="c{{$color['id']}}" name="c{{$color['id']}}"  class="flat"  style="position: absolute; opacity: 0;" >

                                                <ins class="iCheck-helper" onclick="eklecikar('c{{$color['id']}}','color_list')" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">

                                                </ins>
                                            </div>
                                        </label>
                                                <div style="width: 100%;height: 50px; background: {{$color['color_code']}}"></div>
                                            </td>
                                    @endforeach <td>&nbsp;</td>
                                      </tr></table>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Açıklama :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="description" id="description"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="description_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Fiyat :</label>
                                <div class="col-lg-3">
                                    <input type="number" class="form-control" name="price" id="price"
                                           value="0" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="price_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Fiyat (eski) :</label>
                                <div class="col-lg-3">
                                    <input type="number" class="form-control" name="price_ex" id="price_ex"
                                           value="0" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="price_ex_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Stok Miktarı :</label>
                                <div class="col-lg-3">
                                    <input type="number" class="form-control" name="quantity" id="quantity"
                                           value="0" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="quantity_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" checked="true" name="status" id="status" value="13" data-switchery="true"
                                               style="display: none;">



                                    </label>
                                </div>
                            </div>
                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">ÜRÜN EKLE
                                    <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
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

    <script>
        function eklecikar(kutu,inputAlan){

            if(!$('#'+kutu).is(':checked')){
                document.getElementById(inputAlan).value+="@"+kutu;
            }else{
                document.getElementById(inputAlan).value=document.getElementById(inputAlan).value.replace("@"+kutu,"");
            }
        }

        /////hepsini seç fx


        function brandSelect() {
            var brand_id = $('#brand_id').val();
            $('#questions_div').html('');
            if (brand_id > 0) {
                $('#model_id').show();
                $('#model_result').html('');
                $.get("{{url('data/get-models')}}/" + brand_id, function (data) {


                    if (data == "none") {
                        swal("model bulunamadı");
//                        $('#model_result').html('<h2>Model Bulunamadı</h2>');
                      //  $('#model_id').hide();
                    } else {
                        $('#model_id').prop("disabled", false);
                        $('#model_id').html(data);
                    }
                });
            } else {
                $('#model_id').prop("disabled", true);
                $('#model_id').html('');
            }
        }

        function modelSelect() {

            var model_id = $('#model_id').val();
            if (model_id > 0) {
                $('#model_result').html('');
                $.get("{{url('data/get-colors')}}/" + model_id, function (data) {

                    $('#color_div').html('');

                });
            } else {

                $('#color_div').html('');
            }

        }

        $(document).ready(function () {
            if ($("input.flat")[0]) {
                $(document).ready(function () {
                    $('input.flat').iCheck({
                        checkboxClass: 'icheckbox_flat-green',
                        radioClass: 'iradio_flat-green'
                    });
                });
            }
        });
        $('#create-product').submit(function (e) {
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
            if(error){
                return false;
            }else{
                save(formData, '{{route('site.create-product-post')}}', '', '','');
            }
        });
    </script>
@endsection
