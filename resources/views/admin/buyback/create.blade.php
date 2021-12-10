@extends('admin.main_layout');

@section('css_')

    <style>
        .main_div {
            min-height: 1000px;
            height: auto;

        }
    </style>
@endsection
@section('main')

    <div class="main_div">
        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="row x_title">
                    <div class="col-md-6">
                        <h3>BUYBACK<small> Hesaplama Modülü</small></h3>
                    </div>
                    <div class="col-md-6">
                        <div id="reportrange" class="pull-right"
                             style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                            <i class="fa fa-calendar"></i>
                            <span>{{date('d-M-Y')}}</span> <b class="caret"></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="x_panel">
                            <div class="x_title">

                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br>
                                <form class="form-label-left input_mask">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6  form-group has-feedback">
                                            <h2 for="">Marka Seçiniz </h2>
                                            <select name="brand_id" id="brand_id" class="form-control"
                                                    onchange="brandSelect()">
                                                <option value="0">Seçiniz</option>
                                                @foreach($brands as  $brand)
                                                    <option value="{{$brand['id']}}">{{$brand['BrandName']}}</option>
                                                @endforeach

                                            </select>


                                        </div>
                                        <div class="col-md-6 col-sm-6  form-group has-feedback">
                                            <h2 for="">Model Seçiniz </h2>
                                            <select name="model_id" id="model_id" disabled class="form-control"
                                                    onchange="modelSelect()">
                                                <option value="0">Seçiniz</option>

                                            </select>
                                            <span id="model_result"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6  form-group has-feedback">
                                            <h2 for="">IMEI: </h2>
                                            <input type="text" class="form-control" name="imei" id="imei" disabled>

                                        </div>
                                        <div class="col-md-2 col-sm-2  ">
                                            <input type="button" class="btn btn-primary" style="margin-top: 40px"
                                                   disabled
                                                   id="imei_btn" onclick="imeiCheck()" value="SORGULA">

                                        </div>
                                        <div class="col-md-3 col-sm-3 text-left">
                                            <h2 for="">RENK: </h2>
                                            <select name="color_id" id="color_id" disabled class="form-control">
                                                <option value="0">Seçiniz</option>
                                            </select>
                                            <span id="color_id_error"></span>

                                        </div>
                                    </div>

                                    <div class="form-group row" id="questions_div">

                                    </div>

                                </form>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="calculate_result" id="calculate_result">
                    <div class="col-md-6 ">

                        <div class="x_panel">
                            <div class="x_title">

                                <div class="clearfix"></div>
                            </div>
                            <div id="buyer_info">

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <br/>
    </div>
@endsection

@section('scripts')
    <script src="{{url("js/save.js")}}"></script>
    <script src="{{url('js/address.js')}}"></script>
    <script>


        function rset(){
            $('#imei').val('');
            $('#color_id').val(0);
            $('#questions_div').html('');
        }

        function brandSelect() {
            var brand_id = $('#brand_id').val();
            $('#questions_div').html('');
            if (brand_id > 0) {
                $('#model_id').show();
                $('#model_result').html('');
                $.get("{{url('data/get-models')}}/" + brand_id, function (data) {
                    //console.log("{{url('admin/brand/get-models')}}/"+brand_id);
                    if (data == "none") {
                        $('#model_result').html('<h2>Model Bulunamadı</h2>');
                        $('#model_id').hide();
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

                    $('#color_id').prop("disabled", false);
                    $('#imei').prop("disabled", false);
                    $('#imei_btn').prop("disabled", false);
                    $('#color_id').html(data);

                });
            } else {
                $('#color_id').html('');
                $('#color_id').prop("disabled", true);
                $('#imei').prop("disabled", true);
                $('#imei_btn').prop("disabled", true);
                $('#questions_div').html('');
            }

        }

        function imeiCheck() {

            var imei = $('#imei').val();
            if (imei == '') {
                swal("Lütfen IMEI Numarası giriniz", "", "error");
                $('#imei').focus();
            } else {

                $.get("{{url('data/check-imei')}}/" + $('#model_id').val() + "/" + imei, function (data) {

                    if (data == "ok") {
                        $.get("{{url('data/get-questions')}}/" + $('#model_id').val(), function (data) {
                            $('#questions_div').html(data);

                        });
                    } else {
                        swal("Geçersiz IMEI", "", "error");
                        $('#imei').val('');
                        $('#imei').focus();
                    }
                });

            }
        }


        function get_buyer_info() {
            $.get("{{url('data/get-buyer-info')}}/" + $('#model_id').val() + "/" + $('#calculate_result').val() + "/" + $('#price').val() + "/" + $('#imei').val() + "/" + $('#color_id').val(), function (data) {
                $('#buyer_info').html(data);
            });
        }




    </script>
@endsection
