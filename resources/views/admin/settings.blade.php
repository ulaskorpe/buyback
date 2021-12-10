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






        $('#profile-update').submit(function (e) {
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
            save(formData, '{{route('profile-update')}}', '', '','');
        });
    </script>
@endsection


