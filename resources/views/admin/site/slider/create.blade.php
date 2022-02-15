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
                            <a href="{{route("site.slider-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Slider Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="create-slider" action="{{route('site.create-slider-post')}}" method="post" enctype="multipart/form-data">
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
                                <label class="col-lg-2 col-form-label font-weight-semibold">Alt Başlık :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="subtitle" id="subtitle"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Açıklama Yazısı">
                                    <span id="subtitle_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">BTN başlık -1  :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="btn_1" id="btn_1"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="btn_1_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">BTN Başlık -2 :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="btn_2" id="btn_2"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="btn_2_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Link :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="link" id="link"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Açılacak Bağlantı">
                                    <span id="link_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Not :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="note" id="note"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Bağlantı notu">
                                    <span id="note_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Resim</label>
                                <div class="col-lg-8">
                                    <input type="file" name="slider" id="slider" class="form-control h-auto" data-popup="tooltip"
                                           title=""
                                           onchange="showImage('slider','target','avatar_img')"
                                           placeholder="">
                                    <span id="slider_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold"></label>
                                <div class="col-lg-8">

                                    <img id="target" style="display: none;max-width: 500px">
                                    <img id="avatar_img" style="display: none;">

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Arka Plan</label>
                                <div class="col-lg-8">
                                    <input type="file" name="bgimg" id="bgimg" class="form-control h-auto" data-popup="tooltip"
                                           title=""
                                           onchange="showImage('bgimg','targetbg','avatar_bg_img')"
                                           placeholder="">
                                    <span id="slider_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold"></label>
                                <div class="col-lg-8">

                                    <img id="targetbg" style="display: none;;max-width: 500px">
                                    <img id="avatar_bg_img" style="display: none;">

                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Sıra :</label>
                                <div class="col-lg-1">
                                    <select name="count" id="count" class="form-control">
                                        @for($i=$count;$i>0;$i--)
                                            <option value="{{$i}}">{{$i}}</option>
                                            @endfor

                                    </select>

                                </div>
                                <div class="col-lg-9"></div>
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
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">SLIDER EKLE
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
                                    txt+="Dosya genişliği "+w+"px ";
                                    error=true;
                                }

                                if(h!=this.height){
                                    if(error) {
                                        txt+="ve ";
                                    }
                                    txt+="Dosya yüksekliği "+h+"px ";
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
        $('#create-slider').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;

            if ($('#title').val() == '') {
                $('#title_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#title_error').html('');
            }

            if ($('#slider').val() == '') {
                $('#slider_error').html('<span style="color: red">Lütfen dosya seçiniz</span>');
                error = true;
            } else {
                $('#slider_error').html('');
            }

            if ($('#link').val() != '') {
                if(!validURL($('#link').val())){
                    $('#link_error').html('<span style="color: red">Geçersiz URL</span>');
                    $('#link').val('');
                    error = true;
                }else{
                    $('#link_error').html('');
                }
           }
             if(error){
                return false;
            }else{
                 save(formData, '{{route('site.create-slider-post')}}', '', '','');
            }
        });
    </script>
@endsection
