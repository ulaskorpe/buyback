
<link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">

<form id="create-buyback" action="{{route('buyback.create-buyBack')}}" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="hidden" name="model_id" id="m_id" value="{{$model_id}}">
    <input type="hidden" name="answers" id="answers_" value="{{$result}}">
    <input type="hidden" name="offered_price" id="offered_price" value="{{$price}}">
    <input type="hidden" name="imei" id="imei" value="{{$imei}}">
    <input type="hidden" name="color_id_" id="color_id_" value="{{$color_id}}">
    <input type="hidden" name="imei_id" id="imei_id" value="{{$imei_id}}">
    <div class="row">
    <div class="col-md-6 col-sm-6  form-group has-feedback">
        <input type="text" class="form-control has-feedback-left" name="name"  id="name" placeholder="Adınız">
        <span class="fa fa-user form-control-feedback left" aria-hidden="true"></span>
        <div id="name_error"></div>
    </div>
    <div class="col-md-6 col-sm-6  form-group has-feedback">
        <input type="text" class="form-control" name="surname"  id="surname" placeholder="Soyadınız">
        <span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
        <div id="surname_error"></div>
    </div>
    </div>
    <div class="row">
    <div class="col-md-6 col-sm-6  form-group has-feedback">
        <input type="text" class="form-control has-feedback-left" name="email"  id="email" placeholder="E-posta">
        <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
        <div id="email_error"></div>
    </div>
    <div class="col-md-6 col-sm-6  form-group has-feedback">
        <input type="text" class="form-control"  name="phone"  id="phone" placeholder="Phone">
        <span class="fa fa-phone form-control-feedback right" aria-hidden="true"></span>
        <div id="phone_error"></div>
    </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12  form-group has-feedback">
            <input type="number" class="form-control has-feedback-left" name="tckn"  id="tckn" maxlength="11" placeholder="TCKN">
            <span class="fa fa-tag form-control-feedback left" aria-hidden="true"></span>
            <div id="tckn_error"></div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12  form-group has-feedback">
            <input type="number" class="form-control has-feedback-left" name="iban"  id="iban" maxlength="11" placeholder="IBAN Numarası">
            <span class="form-control-feedback left" aria-hidden="true">TR</span>
            <div id="iban_error"></div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6  form-group has-feedback">
            <select name="city_id" id="city_id" class="form-control" onchange="selectTown();">
                <option value="0">Şehir Seçiniz</option>
                @foreach($cities as $city)
                    <option value="{{$city['id']}}">{{$city['name']}}</option>
                @endforeach
            </select>
            <div id="city_id_error"></div>
        </div>
        <div class="col-md-6 col-sm-6  form-group has-feedback">
            <select name="town_id" id="town_id" class="form-control" disabled onchange="selectDistrict();">
                <option value="0">İlçe Seçiniz</option>
            </select>
            <div id="town_id_error"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6  form-group has-feedback">
            <select name="district_id" id="district_id" disabled class="form-control" onchange="selectNeighborhood();">
                <option value="0">Mahalle Seçiniz</option>
            </select>
            <div id="district_id_error"></div>
        </div>
        <div class="col-md-6 col-sm-6  form-group has-feedback">
            <select name="neighborhood_id" id="neighborhood_id" class="form-control" disabled onchange="getPostalCode()">
                <option value="0">Bölge Seçiniz</option>
            </select>
            <div id="neighborhood_id_error"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-10 form-group has-feedback">

            <input type="text" class="form-control" name="address"  id="address" placeholder="Adres">

            <div id="address_error"></div>
        </div>
        <div class="col-2 form-group has-feedback">
            <h2  id="postalcode" >Posta Kodu</h2>


        </div>
    </div>
    <div class="row">

        <div class="col-md-12 col-sm-12 form-group has-feedback">
            <label class="col-lg-2">
                <input type="checkbox" class="js-switch" checked="true" name="terms_of_use" id="terms_of_use" value="13" data-switchery="true"
                       style="display: none;">
            </label>
            <label class="col-lg-10 col-form-label font-weight-semibold">Kullanım Koşullarını Kabul Ediyorum</label>

        </div>
        <div class="col-md-12 col-sm-12  form-group has-feedback">
            <label class="col-lg-2">
                <input type="checkbox" class="js-switch" checked="true" name="campaigns" id="campaigns" value="13" data-switchery="true"
                       style="display: none;">
            </label>
            <label class="col-lg-2 col-form-label font-weight-semibold">Kampanyalardan Haberdar Olmak İstiyorum</label>

        </div>


    </div>

    <div class="ln_solid"></div>
    <div class="form-group row">
        <div class="col-12 text-center">

            <button type="submit" class="btn btn-success">GÖNDER</button>
        </div>
    </div>
</form>
<script src="{{url('vendors/jquery/dist/jquery.min.js')}}"></script>
<script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
<script>


    $(document).ready(function () {
        $(".js-switch")[0] && Array.prototype.slice.call(document.querySelectorAll(".js-switch")).forEach(function (e) {
            new Switchery(e, {color: "#26B99A"})
        })

    });
    $('#create-buyback').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var error = false;

        if ($('#name').val() == '') {
            $('#name_error').html('<span style="color: red">Lütfen adınızı giriniz</span>');
            error = true;
        } else {
            $('#name_error').html('');
        }
        if ($('#surname').val() == '') {
            $('#surname_error').html('<span style="color: red">Lütfen soyadınızı giriniz</span>');
            error = true;
        } else {
            $('#surname_error').html('');
        }


        if ($('#email').val() == '') {
            $('#email_error').html('<span style="color: red">Lütfen Eposta giriniz</span>');
            error = true;
        } else {

            $.get("{{url('data/check-email')}}/" + $('#email').val(), function (data) {
                if(data!='ok'){

                    $('#email_error').html('<span style="color: red">Geçersiz Eposta</span>');
                    $('#email').val('');
                    error = true;
                }else{
                    $('#email_error').html('');
                }
            });



        }
        if ($('#phone').val() == '') {
            $('#phone_error').html('<span style="color: red">Lütfen Telefon numaranızı giriniz</span>');
            error = true;
        } else {

           $.get("{{url('data/check-phone')}}/" + $('#phone').val(), function (data) {
                if(data != 'ok'){
                    $('#phone_error').html('<span style="color: red">Geçersiz Telefon numarası</span>');
                    $('#phone').val('');
                    error = true;
                }else{
                    $('#phone_error').html('');
                }
            });



        }

        if ($('#tckn').val() == '') {
            $('#tckn_error').html('<span style="color: red">Lütfen TCKN giriniz</span>');
            error = true;
        } else {

            $.get("{{url('data/check-tckn')}}/" + $('#tckn').val(), function (data) {

                if(data != 'ok'){
                    $('#tckn_error').html('<span style="color: red">Geçersiz TCKN numarası</span>');
                    $('#tckn').val('');
                    error = true;
                }else{
                    $('#tckn_error').html('');
                }
            });



        }
        if ($('#iban').val() == '') {
            $('#iban_error').html('<span style="color: red">Lütfen IBAN numaranızı giriniz</span>');
            error = true;
        } else {

            $.get("{{url('data/check-iban')}}/" + $('#iban').val(), function (data) {
                if(data != 'ok'){
                    $('#iban_error').html('<span style="color: red">Geçersiz IBAN numarası</span>');
                    $('#iban').val('');
                    error = true;
                }else{
                    $('#iban_error').html('');
                }
            });



        }

        if ($('#city_id').val() == '0') {
            $('#city_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
            error = true;
        } else {
            $('#city_id_error').html('');
        }

        if ($('#town_id').val() == '0') {
            $('#town_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
            error = true;
        } else {
            $('#town_id_error').html('');
        }
        if ($('#district_id').val() == '0') {
            $('#district_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
            error = true;
        } else {
            $('#district_id_error').html('');
        }

        if ($('#neighborhood_id').val() == '0') {
            $('#neighborhood_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
            error = true;
        } else {
            $('#neighborhood_id_error').html('');
        }

        if ($('#address').val() == '') {
            $('#address_error').html('<span style="color: red">Lütfen adresinizi giriniz</span>');
            error = true;
        } else {
            $('#address_error').html('');
        }

        if(error){
            return false;
        }else{

            if(!$('#terms_of_use').is(':checked')){
                swal("HATA","Sözleşmeyi kabul ettiğinizi belirtmelisiniz","error");
            }else{
                save(formData, '{{route('create-buyBack-post')}}', '', '','');
            }


        }
    });

</script>
