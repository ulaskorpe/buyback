@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
@endsection
@section('main')
    <button type="button" style="display: none"  data-toggle="modal" data-target=".bs-example-modal-lg" id="modal_btn"></button>
    <div class="content">



        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-body">

                        <div class="text-center">
                            <a href="{{route("products.product-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Ürün Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="create-product" action="{{route('products.create-product-post')}}" method="post" enctype="multipart/form-data">
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
                                <label class="col-lg-2 col-form-label font-weight-semibold">Micro-ID :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="micro_id" id="micro_id"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           placeholder="MICRO-ID">
                                    <span id="micro_id_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Marka :</label>
                                <div class="col-lg-4">
                                    <select name="brand_id" id="brand_id" class="form-control" onchange="brandSelect()">
                                        <option value="0">Seçiniz</option>
                                        @foreach($brands as $brand)
                                            <option value="{{$brand['id']}}" @if($brand['id']==$p_brand_id) selected @endif>{{$brand['BrandName']}}</option>
                                        @endforeach
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
                                    <select name="model_id" id="model_id" class="form-control" disabled onchange="modelSelect()" >
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
                                    <select name="category_id" id="category_id" class="form-control" disabled >
                                        <option value="0">Seçiniz</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category['id']}}">{{$category['category_name']}}</option>
                                        @endforeach
                                    </select>
                                    <span id="category_id_error"></span>
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

                @if($p_model_id>0)
                $.get("{{url('data/get-models')}}/" + brand_id + "/{{$p_model_id}}", function (data) {


                    if (data == "none") {
                        swal("model bulunamadı");
//                        $('#model_result').html('<h2>Model Bulunamadı</h2>');
                        //  $('#model_id').hide();
                    } else {
                        $('#model_id').prop("disabled", false);
                        $('#model_id').html(data);
                    }
                });
                @else
                $.get("{{url('data/get-models')}}/" + brand_id, function (data) {


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
            @if($p_model_id>0)
            var model_id = {{$p_model_id}};
            @else
            var model_id = $('#model_id').val();
            @endif


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

        $(document).ready(function () {

            @if($p_brand_id>0)
                brandSelect();
                        @if($p_model_id>0)
                        modelSelect();
                        @endif
                @endif

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
                save(formData, '{{route('products.create-product-post')}}', '', '','');
            }
        });

        function addBrand(){
            $('#modal_btn').click();
            $.get("{{url('admin/products/add-brand')}}/0/" , function (data) {
                $('#lg-modal-title').html('Yeni Marka Ekle');
                $('#lg-modal-body').html(data);



            });
        }

        function addModel(){
            if($('#brand_id').val()==0){
                swal('Marka Seçiniz');
            }else {
                $('#modal_btn').click();
                $.get("{{url('admin/products/add-model')}}/"+$('#brand_id').val()  , function (data) {
                    $('#lg-modal-title').html('Yeni Model Ekle');
                    $('#lg-modal-body').html(data);

                });

            }

        }
    </script>
@endsection
