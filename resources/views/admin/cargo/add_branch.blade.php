@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
@endsection
<form id="create-branch" action="{{route('cargo.cargo-add-branch-post')}}" method="post"
      enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold"><h2>{{$company['name']}}</h2></label>
        <div class="col-lg-8">
            <input type="hidden" class="form-control" name="company_id" id="company_id"
                   value="{{$company['id']}}">

        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Şube Adı:</label>
        <div class="col-lg-8">
            <input type="text" class="form-control" name="title" id="title"
                   value="" data-popup="tooltip" data-trigger="focus"
            >
            <span id="title_error"></span>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Kişi:</label>
        <div class="col-lg-8">
            <input type="text" class="form-control" name="person" id="person"
                   value="" data-popup="tooltip" data-trigger="focus"
            >
            <span id="person_error"></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Adres:</label>
        <div class="col-lg-8">
            <input type="text" class="form-control" name="address" id="address"
                   value="" data-popup="tooltip" data-trigger="focus"
            >
            <span id="address_error"></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Şehir:</label>
        <div class="col-lg-8">
            <select name="city_id" id="city_id" class="form-control" onchange="selectTown();">
                <option value="0">Şehir Seçiniz</option>
                @foreach($cities as $city)
                    <option value="{{$city['id']}}">{{$city['name']}}</option>
                @endforeach
            </select>
            <div id="city_id_error"></div>

        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">İlçe:</label>
        <div class="col-lg-8">
            <select name="town_id" id="town_id" class="form-control" disabled onchange="selectDistrict();">
                <option value="0">İlçe Seçiniz</option>
            </select>
            <div id="town_id_error"></div>

        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Bölge:</label>
        <div class="col-lg-8">
            <select name="district_id" id="district_id" disabled class="form-control" onchange="selectNeighborhood();">
                <option value="0">Mahalle Seçiniz</option>
            </select>
            <div id="district_id_error"></div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Mahalle / Köy:</label>
        <div class="col-lg-8">
            <select name="neighborhood_id" id="neighborhood_id" class="form-control" disabled onchange="getPostalCode()">
                <option value="0">Bölge Seçiniz</option>
            </select>
            <div id="neighborhood_id_error"></div>

        </div>
        <div class="col-2 form-group has-feedback">
            <h2  id="postalcode" >Posta Kodu</h2>


        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Telefon:</label>
        <div class="col-lg-8">
            <input type="text" class="form-control" name="phone_number" id="phone_number"
                   value="" data-popup="tooltip" data-trigger="focus"
            >

        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Telefon 2:</label>
        <div class="col-lg-8">
            <input type="text" class="form-control" name="phone_number_2" id="phone_number_2"
                   value="" data-popup="tooltip" data-trigger="focus"
            >

        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Aktif</label>
        <div class="col-lg-8">
            <label>
                <input type="checkbox" class="js-switch"  checked name="status2" id="status2" value="13" data-switchery="true"
                       style="display: none;">


            </label>
        </div>
    </div>
    <br>
    <!-- /touchspin spinners -->
    <div class="text-center">
        <button type="submit" class="btn btn-primary font-weight-bold rounded-round">Şube Ekle
            <i class="icon-paperplane ml-2"></i></button>
    </div>
</form>

<script src="{{url("js/save.js")}}"></script>

<script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
<script>

    $(document).ready(function () {
        $("#status2")[0] && Array.prototype.slice.call(document.querySelectorAll("#status2")).forEach(function (e) {
            new Switchery(e, {color: "#26B99A"})
        })
        $('#town_id').prop('disabled', false);




    });


    function getPostalCode() {
        $.get("{{url('data/get-postalcode')}}/" + $('#neighborhood_id').val(), function (data) {
            $('#postalcode').html(data);
        });

    }
    function selectTown() {
        $.get("{{url('data/get-towns')}}/" + $('#city_id').val() , function (data) {
            $('#town_id').html(data);

        });
        $('#district_id').html('<option>Mahalle Seçiniz</option>');
        $('#neighborhood_id').html('<option>Bölge Seçiniz</option>');
        $('#district_id').prop('disabled', true);
        $('#neighborhood_id').prop('disabled', true);
        $('#town_id').prop('disabled', false);
    }

    function selectDistrict( ) {

        $.get("{{url('data/get-districts')}}/" + $('#town_id').val()  , function (data) {
            $('#district_id').html(data);

        });
        $('#neighborhood_id').html('<option>Bölge Seçiniz</option>');
        $('#neighborhood_id').prop('disabled', true);
        $('#district_id').prop('disabled', false);
    }

    function selectNeighborhood() {
        $.get("{{url('data/get-neighborhoods')}}/" + $('#district_id').val()  , function (data) {
            $('#neighborhood_id').html(data);
        });
        $('#neighborhood_id').prop('disabled', false);
    }

    $('#create-branch').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var error = false;

        if ($('#title').val() == '') {
            $('#title_error').html('<span style="color: red">Lütfen şube adı giriniz</span>');
            error = true;
        } else {
            $('#title_error').html('');
        }
        if ($('#address').val() == '') {
            $('#address_error').html('<span style="color: red">Lütfen adres giriniz</span>');
            error = true;
        } else {
            $('#address_error').html('');
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


        if(error){
            return false;
        }else{

            save(formData, '{{route('cargo.cargo-add-branch-post')}}', '', '','');



        }
    });
</script>
