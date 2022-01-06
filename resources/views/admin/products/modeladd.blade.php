<form id="create-model" action="{{route('model.model-add-post')}}" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="hidden" id="product_id" name="product_id" value="{{$product_id}}">
    <input type="hidden" id="brand_id" name="brand_id" value="{{$brand_id}}">
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Marka</label>
        <div class="col-lg-8">
            <input type="hidden" name="brand_id" id="brand_id" value="{{$brand['id']}}">
         <h2> {{$brand['BrandName']}}</h2>
            <span id="brand_id_error"></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Kategori</label>
        <div class="col-lg-8">
            <select name="cat_id" id="cat_id" class="form-control" onchange="checkType()">
                <option value="0">Seçiniz</option>
                @foreach($categories as $cat)
                    <option value="{{$cat['id']}}"> {{$cat['category_name']}}</option>
                @endforeach
            </select>
            <span id="cat_id_error"></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Model Adı</label>
        <div class="col-lg-8">
            <input type="text" class="form-control" name="modelname" id="modelname"
                   value="" data-popup="tooltip" data-trigger="focus"
                   title="Marka tam Adı" placeholder="Model Adı">
            <span id="modelname_error"></span>
        </div>
    </div>
    <div class="form-group row" id="memory_div">
        <label class="col-lg-2 col-form-label font-weight-semibold">Hafıza</label>
        <div class="col-lg-8">
            <select name="memory_id" id="memory_id" class="form-control">
                <option value="0">Seçiniz</option>
                @foreach($memories as $memory)
                    <option value="{{$memory['id']}}">{{$memory['memory_value']}}GB</option>
                @endforeach
            </select>
            <span id="memory_id_error"></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Resim</label>
        <div class="col-lg-8">
            <input type="file" name="model_img" id="model_img" class="form-control h-auto" data-popup="tooltip"
                   title=""
                   onchange="showImage('model_img','target_model','avatar_img_model')"
                   placeholder="">
            <span id="model_img_error"></span>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold"></label>
        <div class="col-lg-8">

            <img id="target_model" style="display: none;">
            <img id="avatar_img_model" style="display: none;">

        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">SEO Title</label>
        <div class="col-lg-8">
            <input type="text" name="seotitle" id="seotitle" class="form-control h-auto"  >
            <span class="form-text text-muted" id="seotitle_error"></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">SEO Desc</label>
        <div class="col-lg-8">
            <input type="text" name="seodesc" id="seodesc" class="form-control h-auto"  >
            <span class="form-text text-muted" id="seodesc_error"></span>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">En Düşük Fiyat</label>
        <div class="col-lg-8">
            <input type="number" name="min_price" id="min_price" class="form-control h-auto" maxlength="10" >
            <span class="form-text text-muted" id="min_price_error"></span>

        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">En Yüksek Fiyat</label>
        <div class="col-lg-8">
            <input type="number" name="max_price" id="max_price" class="form-control h-auto"  maxlength="10" >
            <span class="form-text text-muted" id="max_price_error"></span>

        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
        <div class="col-lg-8">
            <label>
                <input type="checkbox" class="js-switch" checked="true" name="status2" id="status2" value="13" data-switchery="true"
                       style="display: none;">



            </label>
        </div>
    </div>
    <br>
    <!-- /touchspin spinners -->
    <div class="text-center">
        <button type="submit" class="btn btn-primary font-weight-bold rounded-round">MODEL EKLE
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

    });


    function checkType(){
        if($('#cat_id').val()==1){
            $('#memory_div').hide();
        }else{
            $('#memory_div').show();
        }
    }

    function validURL(str) {
        var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
            '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
        return !!pattern.test(str);
    }
    $('#create-model').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var error = false;

        if ($('#brand_id').val() == '0') {
            $('#brand_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
            error = true;
        } else {
            $('#brand_id_error').html('');
        }
        if ($('#cat_id').val() == '0') {
            $('#cat_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
            error = true;
        } else {
            $('#cat_id_error').html('');
        }

        if ($('#modelname').val() == '') {
            $('#modelname_error').html('<span style="color: red">Lütfen giriniz</span>');
            error = true;
        } else {
            $('#modelname_error').html('');
        }

        if($('#cat_id').val()>1){
            if ($('#memory_id').val() == '0') {
                $('#memory_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
                error = true;
            } else {
                $('#memory_id_error').html('');
            }


        }

        if ($('#min_price').val() == '') {
            $('#min_price_error').html('<span style="color: red">Lütfen giriniz</span>');
            error = true;
        } else {
            $('#min_price_error').html('');
        }

        if ($('#max_price').val() == '') {
            $('#max_price_error').html('<span style="color: red">Lütfen giriniz</span>');
            error = true;
        } else {
            $('#max_price_error').html('');
        }

        //    console.log(parseInt($('#min_price').val()));
        //  console.log(parseInt($('#max_price').val()));
        if(parseInt($('#min_price').val()) >parseInt($('#max_price').val())){
            /// swal("ok");
            $('#min_price').val('');
            $('#min_price_error').html('<span style="color: red">En düşük fiyat '+$('#max_price').val()+' den küçük olmalıdır</span>');
            error = true;
        }

        //   console.log(error);
        if(error){
            return false;
        }else{
            //    swal("ok");
            save(formData, '{{route('products.add-product-model-post')}}', '', '','');
        }
    });
</script>
