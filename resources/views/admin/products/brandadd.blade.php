<link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
<form id="create-brand" action="#" method="post"
      enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="hidden" id="product_id" name="product_id" value="{{$product_id}}">
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Marka Adı</label>
        <div class="col-lg-8">
            <input type="text" class="form-control" name="brandname" id="brandname"
                   value="" data-popup="tooltip" data-trigger="focus"
                   title="Marka tam Adı" placeholder="Marka Adı">
            <span id="brandname_error"></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Resim</label>
        <div class="col-lg-8">
            <input type="file" name="brand_img" id="brand_img" class="form-control h-auto"
                   data-popup="tooltip"
                   title=""
                   onchange="showImage('brand_img','target_brand','avatar_img_brand')"
                   placeholder="">
            <span id="brand_img_error"></span>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold"></label>
        <div class="col-lg-8">

            <img id="target_brand" style="display: none;">
            <img id="avatar_img_brand" style="display: none;">

        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">İcon Path</label>
        <div class="col-lg-8">
            <input type="text" name="iconpath" id="iconpath" class="form-control h-auto">
            <span class="form-text text-muted" id="iconpath_error"></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">SEO Title</label>
        <div class="col-lg-8">
            <input type="text" name="seotitle" id="seotitle" class="form-control h-auto">
            <span class="form-text text-muted" id="seotitle_error"></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">SEO Desc</label>
        <div class="col-lg-8">
            <input type="text" name="seodesc" id="seodesc" class="form-control h-auto">
            <span class="form-text text-muted" id="seodesc_error"></span>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Açıklama</label>
        <div class="col-lg-8">

            <textarea name="description" id="description" class="form-control"> </textarea>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
        <div class="col-lg-8">
            <label>
                <input type="checkbox" class="js-switch" checked="true" name="status1" id="status1" value="13" data-switchery="true"
                       style="display: none;">
            </label>
        </div>
    </div>
    <br>
    <!-- /touchspin spinners -->
    <div class="text-center">
        <button type="submit" class="btn btn-primary font-weight-bold rounded-round">MARKA EKLE
            <i class="icon-paperplane ml-2"></i></button>
    </div>
</form>
<script src="{{url("js/save.js")}}"></script>
<script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>


    <script src="{{url("js/save.js")}}"></script>
    <script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>

    <script>

        $(document).ready(function () {
            $("#status1")[0] && Array.prototype.slice.call(document.querySelectorAll("#status1")).forEach(function (e) {
                new Switchery(e, {color: "#26B99A"})
            })

        });


        function validURL(str) {
            var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
            return !!pattern.test(str);
        }

        $('#create-brand').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;
            if ($('#brandname').val() == '') {
                $('#brandname_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#brandname_error').html('');
            }

            if ($('#iconpath').val() != '') {
                if (!validURL($('#iconpath').val())) {
                    $('#iconpath').val('');
                    $('#iconpath_error').html('<span style="color: red">Geçersiz URL</span>');
                    error = true;
                } else {
                    $('#iconpath_error').html('');
                }
            } else {
                $('#iconpath_error').html('');
            }


            if (error) {
                return false;
            }


          var result =  save(formData, '{{route('products.add-product-brand-post')}}', '', 'btn-1', '');
            $('#brand_id').val(result);


        });
    </script>

