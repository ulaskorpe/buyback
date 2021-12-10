@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet">

@endsection

@section('main')

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="{{route("users.user-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Kullanıcı Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="create-user" action="{{route('users.user-create-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Adı Soyadı</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           title="Adı Soyadı" placeholder="Adı Soyadı">
                                    <div id="name_error"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Eposta</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="email" id="email"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           title="Eposta" placeholder="Eposta">
                                    <div id="email_error"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Telefon</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="phone" id="phone"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           title="Telefon" placeholder="Telefon">
                                    <div id="phone_error"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Şifre :</label>
                                <div class="col-lg-2">
                                    <input type="text" id="pw" name="pw" class="form-control" value="" disabled>
                                    <input type="hidden" class="form-control" name="password" id="password"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           title="Adı Soyadı" placeholder="Adı Soyadı">
                                    <div id="password_error"></div>
                                </div>
                                <div class="col-lg-2"><input  type="button" class="btn btn-success" value="Şifre Üret" onclick="generatePw()"></div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Kullanıcı Grubu</label>
                                <div class="col-lg-2">
                                    <select name="group_id" id="group_id" class="form-control">
                                        <option value="0">Grupsuz</option>
                                        @foreach($groups as $group)
                                            <option value="{{$group['id']}}">{{$group['name']}}</option>
                                        @endforeach
                                    </select>
                                    <div id="name_error"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Süper Admin :</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" name="sudo" id="sudo" value="13" data-switchery="true"
                                               style="display: none;">
                                    </label>
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
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">KULLANICI EKLE
                                    <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /content area -->


@endsection
@section('scripts')
    <script src="{{url("js/save.js")}}"></script>
    <script src="{{url("js/jscolor.min.js")}}"></script>
    <script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
    <script src="{{url('vendors/moment/min/moment.min.js')}}"></script>

    <script>


        function generatePw(){
            $.get("{{url('/admin/users/generate-pw')}}/" , function (data) {

                    $('#pw').val(data);
                    $('#password').val(data);
            });
        }

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

        $('#create-user').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;
            if ($('#name').val() == '') {
                $('#name_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#name_error').html('');
            }

            if ($('#email').val() == '') {
                $('#email_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {

                $.get("{{url('/admin/users/check-email')}}/"+$('#email').val() , function (data) {

                    if(data=='ok'){
                        $('#email_error').html('');
                    }else{
                        error = true;
                        $('#email').val('');
                        $('#email_error').html('<span style="color: red">'+data+'</span>');
                    }


                });


            }

            if ($('#password').val() == '') {
                $('#password_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#password_error').html('');
            }


            if(error){
                return false;
            }

            //   swal("ok");
            save(formData, '{{route('users.user-create-post')}}', '', '','');
        });
    </script>
@endsection


