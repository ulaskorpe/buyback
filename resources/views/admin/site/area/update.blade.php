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
                            <a href="{{route("site.area-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Alan Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="update-area" action="{{route('site.update-area-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="id" id="id" value="{{$area['id']}}">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Alan KODU :</label>
                                <div class="col-lg-8"><b>{{$area['code']}}</b>


                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Alan Adı :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="title" id="title"
                                           value="{{$area['title']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Alan Adı">
                                    <span id="title_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Yazı-1 :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="txt_1" id="txt_1"
                                           value="{{$area['txt_1']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="txt_1_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Yazı-2 :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="txt_2" id="txt_2"
                                           value="{{$area['txt_2']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="txt_2_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Ürün ID :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="link" id="link"
                                           value="{{$area['micro_id']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Açılacak Bağlantı">
                                    <span id="link_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Boyut :</label>
                                <div class="col-lg-2">
                                    <select name="type" id="type" class="form-control">
                                        <option value="small" @if($area['type']=='small') selected @endif>Küçük</option>
                                        <option value="large" @if($area['type']=='large') selected @endif>Büyük</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Hizlama :</label>
                                <div class="col-lg-2">
                                    <select name="textStyle" id="textStyle" class="form-control">
                                        <option value="left" @if($area['textStyle']=='left') selected @endif>Sol</option>
                                        <option value="center" @if($area['textStyle']=='center') selected @endif>Orta</option>
                                        <option value="right" @if($area['textStyle']=='right') selected @endif>Sağ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Resim</label>
                                <div class="col-lg-8">
                                    <input type="file" name="area" id="area" class="form-control h-auto" data-popup="tooltip"
                                           title=""
                                           onchange="showImage('area','target','avatar_img')"
                                           placeholder="">
                                    <span id="area_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold"></label>
                                <div class="col-lg-8">


                                    <img id="target" style="display: none;">
                                    @if(!empty($area['thumb']))
                                        <a href="{{url($area['image'])}}" target="_blank"><img id="avatar_img"
                                                                                                 src="{{url($area['thumb'])}}"></a>
                                    @endif

                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" @if($area['status'] == 1) checked
                                               @endif name="status" id="status" value="13" data-switchery="true"
                                               style="display: none;">



                                    </label>
                                </div>
                            </div>
                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">ALAN GÜNCELLE
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
        $('#update-area').submit(function (e) {
            e.preventDefault();
            var micro_id_array =[@foreach($micro_id_array as $item) parseInt({{$item}}),@endforeach , -555];
            var formData = new FormData(this);
            var error = false;

            if ($('#title').val() == '') {
                $('#title_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#title_error').html('');
            }


            if ($('#link').val() != '') {


                if(!micro_id_array.includes(parseInt($('#link').val()))){
                    $('#link_error').html('<span style="color: red">Geçersiz ÜRÜN</span>');
                    $('#link').val('');
                    error = true;
                }else{
                    $('#link_error').html('');

                }
            }
//            if ($('#link').val() != '') {
//                if(!validURL($('#link').val())){
//                    $('#link_error').html('<span style="color: red">Geçersiz URL</span>');
//                    $('#link').val('');
//                    error = true;
//                }else{
//                    $('#link_error').html('');
//                }
//            }
            if(error){
                return false;
            }else{
                save(formData, '{{route('site.update-area-post')}}', '', '','');
            }
        });
    </script>
@endsection
