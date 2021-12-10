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

                            </a>
                        </div>
                        <br><br>

                        <form id="password-update" action="{{route('password-update')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Yeni Şifre</label>
                                <div class="col-lg-8">
                                    <input type="password" class="form-control" name="pw" id="pw"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           >
                                    <div id="pw_error"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Yeni Şifre  (Yeniden)</label>
                                <div class="col-lg-8">
                                    <input type="password" class="form-control" name="pw2" id="pw2"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                    >
                                    <div id="pw2_error"></div>
                                </div>
                            </div>

                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">GÜNCELLE
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






        $('#password-update').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;
            if ($('#pw').val() == '') {
                $('#pw_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                if($('#pw').val().length < 6 ){
                    $('#pw_error').html('<span style="color: red">Şifreniz en az 6 karakter olmalıdır</span>');
                    error = true;
                }else{
                    $('#pw_error').html('');

                    if ($('#pw2').val() != $('#pw').val()) {
                        $('#pw2_error').html('<span style="color: red">Lütfen şifrenizi yeniden giriniz</span>');
                        error = true;
                    } else {
                        $('#pw2_error').html('');
                    }


                }

            }




            if(error){
                return false;
            }

            //   swal("ok");
            save(formData, '{{route('password-update')}}', '', '','');
        });
    </script>
@endsection


