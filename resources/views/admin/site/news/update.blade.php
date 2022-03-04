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
                            <a href="{{route("site.news-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> HABER Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="update-news" action="{{route('site.update-news-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="id" id="id" value="{{$news['id']}}">

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Başlık :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="title" id="title" maxlength="200"
                                           value="{{$news['title']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Başlık Yazısı">
                                    <span id="title_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Önyazı :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="description" id="description" maxlength="200"
                                           value="{{$news['description']}}"  >
                                    <span id="description_error"></span>
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">İçerik :</label>
                                <div class="col-lg-8">
                                    <textarea name="content" id="content" class="form-control">{{$news['content']}}</textarea>
                                    <span id="content_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Resim</label>
                                <div class="col-lg-8">
                                    <input type="file" name="image" id="image" class="form-control h-auto" data-popup="tooltip"
                                           title=""
                                           onchange="showImage('image','target','avatar_img')"
                                           placeholder="">
                                    <span id="image_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold"></label>
                                <div class="col-lg-8">


                                    <img id="target" style="display: none;max-width: 500px">
                                    @if(!empty($news['thumb']))
                                        <a href="{{url($news['image'])}}" target="_blank"><img id="avatar_img"
                                                                                                 src="{{url($news['thumb'])}}"></a>
                                        @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Tarih</label>
                                <div class="col-md-3 col-sm-3 ">
                                    <input id="birthday" class="date-picker form-control"
                                           name="date" id="date" value="{{$news['date']}}"
                                           placeholder="dd-mm-yyyy" type="text" required="required"
                                           onfocus="this.type='date'" onmouseover="this.type='date'"
                                           onclick="this.type='date'" onblur="this.type='text'" onmouseout="timeFunctionLong(this)">
                                    <script>
                                        function timeFunctionLong(input) {
                                            setTimeout(function() {
                                                input.type = 'text';
                                            }, 60000);
                                        }
                                    </script>
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" @if($news['status']==1) checked @endif name="status" id="status" value="13" data-switchery="true"
                                               style="display: none;">



                                    </label>
                                </div>
                            </div>
                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">HABER GÜNCELLE
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

        $('#update-news').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;

            if ($('#title').val() == '') {
                $('#title_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#title_error').html('');
            }

            if ($('#description').val() == '') {
                $('#description_error').html('<span style="color: red">Lütfen dosya seçiniz</span>');
                error = true;
            } else {
                $('#description_error').html('');
            }

            if ($('#content').val() == '') {
                $('#content_error').html('<span style="color: red">Lütfen dosya seçiniz</span>');
                error = true;
            } else {
                $('#content_error').html('');
            }
            if(error){
                return false;
            }else{
                save(formData, '{{route('site.update-news-post')}}', '', '','');
            }
        });
    </script>
@endsection
