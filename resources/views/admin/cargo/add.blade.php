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
                            <a href="{{route("cargo.cargo-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Şirket Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="create-cargo" action="#" method="post"
                              enctype="multipart/form-data">
                            {{csrf_field()}}

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Şirket Adı</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           title="Marka tam Adı" placeholder="Şirket Adı">
                                    <span id="name_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Yetkili</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="person" id="person"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           title="" placeholder="Yetkili Kişi">
                                    <span id="person_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Logo</label>
                                <div class="col-lg-8">
                                    <input type="file" name="logo" id="logo" class="form-control h-auto"
                                           data-popup="tooltip"
                                           title=""
                                           onchange="showImage('logo','target','avatar_img')"
                                           placeholder="">
                                    <span id="logo_error"></span>
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
                                <label class="col-lg-2 col-form-label font-weight-semibold">E-Posta</label>
                                <div class="col-lg-8">
                                    <input type="text" name="email" id="email" class="form-control h-auto">
                                    <span class="form-text text-muted" id="email_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Telefon</label>
                                <div class="col-lg-8">
                                    <input type="text" name="phone_number" id="phone_number" class="form-control h-auto">
                                    <span class="form-text text-muted" id="phone_number_error"></span>
                                </div>
                            </div>



                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">ŞİRKET EKLE
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
    <script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>

    <script>


        function showImage(img, t, hide_it, w = 0, h = 0) {
            $('#' + hide_it).hide();
            $('#' + img).show();
            var src = document.getElementById(img);
            var target = document.getElementById(t);
            var val = $('#' + img).val();
            var arr = val.split('\\');
            $('#' + img + '_error').html("");
            $('#' + t).show();


            $.get("{{url('/check_image')}}/" + arr[arr.length - 1], function (data) {
                //alert(data);
                if (data === 'ok') {
                    $('#' + img + '_error').html("");
                    $('#' + t).show();
                    var fr = new FileReader();
                    fr.onload = function (e) {

                        var image = new Image();
                        image.src = e.target.result;

                        image.onload = function (e) {

                            if (w > 0 || h > 0) {
                                var error = false;
                                var txt = "";
                                if (w != this.width) {
                                    txt += "Dosya genişliği " + w + "";
                                    error = true;
                                }

                                if (h != this.height) {
                                    txt += "Dosya yüksekliği " + h + "";
                                    error = true;
                                }
                                if (error) {
                                    $('#' + img + '_error').html('<span style="color: red">' + txt + ' olmalıdır </span>');
                                    //swal("admin.image_wrong_format");
                                    $('#' + img).val('');
                                    $('#' + t).hide();
                                    $('#' + hide_it).hide();
                                    return false;
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
                    $('#' + img + '_error').html('<span style="color: red">Yanlış dosya biçimi</span>');
                    //swal("admin.image_wrong_format");
                    $('#' + img).val('');
                    $('#' + t).hide();
                    $('#' + hide_it).hide();


                }
            });


        }

        function ValidateEmail()
        {
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('#email').val()))
            {
                return (true)
            }

            return (false)
        }

        $('#create-cargo').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;
            if ($('#name').val() == '') {
                $('#name_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#name_error').html('');
            }

            if ($('#email').val() != '') {
                if (!ValidateEmail()) {
                    $('#email').val('');
                    $('#email_error').html('<span style="color: red">Geçersiz Eposta</span>');
                    error = true;
                } else {
                    $('#email_error').html('');
                }
            } else {
                $('#email_error').html('');
            }


            if (error) {
                return false;
            }

            //   swal("ok");
            save(formData, '{{route('cargo.cargo-add-post')}}', '', 'btn-1', '');
        });
    </script>
@endsection
