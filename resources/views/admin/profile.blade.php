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

                        <form id="profile-update" action="{{route('profile-update')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Adı Soyadı</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="{{$user['name']}}" data-popup="tooltip" data-trigger="focus"
                                           title="Adı Soyadı" placeholder="Adı Soyadı">
                                    <div id="name_error"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Eposta</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="email" id="email"
                                           value="{{$user['email']}}" data-popup="tooltip" data-trigger="focus"
                                           title="Eposta" placeholder="Eposta">
                                    <div id="email_error"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Telefon</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="phone" id="phone"
                                           value="{{$user['phone']}}" data-popup="tooltip" data-trigger="focus"
                                           title="Telefon" placeholder="Telefon">
                                    <div id="phone_error"></div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Kullanıcı Grubu</label>
                                <div class="col-lg-2">
                                    @if($user['group_id']>0)

                                    {{$user->group()->first()->name}}
                                    @else
                                        -
                                        @endif
                                    <div id="name_error"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Süper Admin :</label>
                                <div class="col-lg-8">
                                    @if($user['sudo']==1)
                                        <i class="fa fa-check"></i>
                                    @else
                                        <i class="fa fa-close"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                                <div class="col-lg-8">
                                    @if($user['status']==1)
                                        <i class="fa fa-check"></i>
                                    @else
                                        <i class="fa fa-close"></i>
                                    @endif
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

                $.get("{{url('/admin/users/check-email')}}/"+$('#email').val()+"/{{$user['id']}}" , function (data) {

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
            save(formData, '{{route('profile-update')}}', '', '','');
        });
    </script>
@endsection


