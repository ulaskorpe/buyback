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
                            <a href="{{route("brand.brandlist")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Marka Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="create-brand" action="#" method="post"
                              enctype="multipart/form-data">
                            {{csrf_field()}}

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
                                           onchange="showImage('brand_img','target','avatar_img')"
                                           placeholder="">
                                    <span id="brand_img_error"></span>
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
                                        <input type="checkbox" class="js-switch" checked="true" name="status" id="status" value="13" data-switchery="true"
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

            //   swal("ok");
            save(formData, '{{route('brand.brandadd-post')}}', '', 'btn-1', '');
        });
    </script>
@endsection
