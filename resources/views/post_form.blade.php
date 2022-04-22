@extends('main_template')
@section('content')
    <div class="content" style="width: 90%;margin: 0px auto">
        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="row x_title">
                    <div class="col-md-6">
                        <h3>KREDİ KARTI FORM</h3>
                    </div>
                    <div class="col-md-6">

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
                                <form id="kk-form" action="https://garantili.com.tr/eticaretodeme/odeme" method="post" enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <input type="hidden" name="siparis_no" id="siparis_no" value="GRNTL202204181308314">
                                        <div class="col-md-6 col-sm-6  form-group has-feedback">
                                            <h3 for="">  Adı Soyadı </h3>

                                            <input type="text" maxlength="100" class="form-control" name="name_surname" id="name_surname" >
                                            <span id="name_surname_error"></span>
                                        </div>
                                        <div class="col-md-6 col-sm-6  form-group has-feedback">
                                            <h3 for="">KK Numarası</h3>
                                            <input type="number" maxlength="16" class="form-control" name="cc_no" id="cc_no" >
                                            <span id="cc_no_error"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4  form-group has-feedback">
                                            <h3 for="">  Ay/Yıl </h3>
                                            <table style="width: 100%">
                                                <tr>
                                                    <td><select name="expiryMM" id="expiryMM" class="form-control">
                                                            <option value="0">Ay</option>
                                                            @for($i=1;$i<13;$i++)
                                                                @php
                                                                    $m =($i<10)?"0".$i: $i;
                                                                @endphp
                                                                <option value="{{$m}}">{{$m}}</option>
                                                            @endfor
                                                        </select>
                                                    </td>
                                                    <td><select name="expiryYY" id="expiryYY" class="form-control">

                                                            @for($i=date('Y');$i<(date('Y'))+6;$i++)

                                                                <option value="{{$i}}">{{$i}}</option>
                                                            @endfor

                                                        </select></td>
                                                </tr>
                                            </table>



                                            <span id="expiry_error"></span>
                                        </div>
                                        <div class="col-md-2 col-sm-2  form-group has-feedback">
                                            <h3 for=""> CVC</h3>

                                            <input type="number" maxlength="3" class="form-control" name="cvc" id="cvc" >

                                            <span id="cvc_error"></span>
                                        </div>
                                        <div class="col-md-2 col-sm-2  form-group has-feedback">
                                            <input type="button" class="btn btn-primary" style="margin-top: 40px" onclick="gonder()" value="GÖNDER ">

                                        </div>


                                    </div>



                                </form>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="imei_id" id="imei_id">
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
    </div>
@endsection
@section('scripts')
    <script src="{{url("js/save.js")}}"></script>

    <script>


        function gonder(){
            var error = false;
            if ($('#name_surname').val() == '') {
                $('#name_surname_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {

                $('#name_surname_error').html('<span style="color: red"></span>');
            }

            if ($('#cc_no').val() == '') {
                $('#cc_no_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {

                $('#cc_no_error').html('<span style="color: red"></span>');
            }
            if ($('#expiryMM').val() == '0' ||  $('#expiryYY').val()=='0') {
                $('#expiry_error').html('<span style="color: red">Lütfen seçiniz</span>');
                error = true;
            } else {

                $('#expiry_error').html('<span style="color: red"></span>');
            }
            if ($('#cvc').val() == '') {
                $('#cvc_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {

                $('#cvc_error').html('<span style="color: red"></span>');
            }
            if(error){
                return false;
            }else{
                $('#kk-form').submit();
            }

        }



    </script>
@endsection
