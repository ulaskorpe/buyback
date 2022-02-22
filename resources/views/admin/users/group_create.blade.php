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
                            <a href="{{route("users.user-groups")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Grup Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="create-group" action="{{route('users.group-create-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Grup Adı</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           title="Grup Adı" placeholder="Grup Adı">
                                    <div id="name_error"></div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Geri Alım :</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" name="buybacks" id="buybacks" value="13" data-switchery="true"
                                               style="display: none;">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Kullanıcılar</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" name="users" id="users" value="13" data-switchery="true"
                                               style="display: none;">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Sistem</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch"   name="system" id="system" value="13" data-switchery="true"
                                               style="display: none;">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Site</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch"   name="site" id="site" value="13" data-switchery="true"
                                               style="display: none;">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Müşteriler</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch"   name="customers" id="customers" value="13" data-switchery="true"
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
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">GRUP EKLE
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




        $('#create-group').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;
            if ($('#name').val() == '') {
                $('#name_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#name_error').html('');
            }



            if(error){
                return false;
            }

            //   swal("ok");
            save(formData, '{{route('users.group-create-post')}}', '', '','');
        });
    </script>
@endsection


