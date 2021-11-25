@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
@endsection
@section('main')
<div class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">

                    <div class="text-center">
                        <a href="{{route("model.model-list")}}">
                            <button type="button"
                                    class="btn btn-primary">
                                <b><i class="icon-ticket"></i></b> Model Listesine Git
                            </button>
                        </a>
                    </div>
                    <br><br>

                    <form id="create-model" action="{{route('model.model-add-post')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Marka</label>
                            <div class="col-lg-8">
                                <select name="brand_id" id="brand_id" class="form-control">
                                    <option value="0">Seçiniz</option>
                                    @foreach($brands as $brand)
                                        <option value="{{$brand['id']}}">{{$brand['BrandName']}}</option>
                                    @endforeach
                                </select>
                                <span id="brand_id_error"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Kategori</label>
                            <div class="col-lg-8">
                                <select name="cat_id" id="cat_id" class="form-control">
                                    <option value="0">Seçiniz</option>
                                    @foreach($categories as $cat)
                                        <option value="{{$cat['id']}}">{{$cat['category_name']}}</option>
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
                        <div class="form-group row">
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
                                       onchange="showImage('model_img','target','avatar_img')"
                                       placeholder="">
                                <span id="model_img_error"></span>
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
                                    <input type="checkbox" class="js-switch" checked="true" name="status" id="status" value="13" data-switchery="true"
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{url("js/save.js")}}"></script>
<script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
<script>
    function showImage(img, t, hide_it,w=0,h=0) {
        $('#' + hide_it).hide();
        $('#' + img).show();
        var src = document.getElementById(img);
        var target = document.getElementById(t);
        var val = $('#' + img).val();
        var arr = val.split('\\');
        $('#'+img+'_error').html("");
        $('#'+t).show();


        $.get("{{url('/check_image')}}/" + arr[arr.length - 1], function (data) {
            //alert(data);
            if (data === 'ok') {
                $('#'+img+'_error').html("");
                $('#'+t).show();
                var fr = new FileReader();
                fr.onload = function (e) {

                    var image = new Image();
                    image.src = e.target.result;

                    image.onload = function (e) {

                        if(w>0 || h>0){
                            var error= false;
                            var txt = "";
                            if(w!=this.width){
                                txt+="Dosya genişliği "+w+"";
                                error=true;
                            }

                            if(h!=this.height){
                                txt+="Dosya yüksekliği "+h+"";
                                error=true;
                            }
                            if(error){
                                $('#'+img+'_error').html('<span style="color: red">'+txt+' olmalıdır </span>');
                                //swal("admin.image_wrong_format");
                                $('#'+img).val('');
                                $('#'+t ).hide();
                                $('#'+hide_it ).hide();
                                return  false;
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
                $('#'+img+'_error').html('<span style="color: red">Yanlış dosya biçimi</span>');
                //swal("admin.image_wrong_format");
                $('#'+img).val('');
                $('#'+t ).hide();
                $('#'+hide_it ).hide();


            }
        });


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


        if ($('#memory_id').val() == '0') {
            $('#memory_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
            error = true;
        } else {
            $('#memory_id_error').html('');
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
            save(formData, '{{route('model.model-add-post')}}', '', '','');
        }
    });
</script>
@endsection
