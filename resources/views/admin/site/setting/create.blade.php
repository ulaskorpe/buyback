@extends('admin.main_layout')
@section('css_')


@endsection

@section('main')

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="{{route("site.site-settings")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Ayarlar Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="create-setting" action="{{route('site.create-setting-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Ayar Adı</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="setting_name" id="setting_name"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           title="Ayar Adı" placeholder="Ayar Adı">
                                    <div id="setting_name_error"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Açıklama</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="description" id="description"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           title="Açıklama" placeholder="Açıklama">
                                    <div id="description_error"></div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Değer</label>
                                <div class="col-lg-3">
                                    <input type="number" class="form-control" name="value" id="value"
                                           value="0" data-popup="tooltip" data-trigger="focus"
                                           title="Değer" placeholder="Değer">
                                    <div id="value_error"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Kodu</label>
                                <div class="col-lg-3"><b>{{$code}}</b>
                                    <input type="hidden" class="form-control" name="code" id="code"
                                           value="{{$code}}" data-popup="tooltip" data-trigger="focus"
                                           title="code" placeholder="code">

                                </div>
                            </div>
                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">AYAR EKLE
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


    <script>




        $('#create-setting').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;
            if ($('#setting_name').val() == '') {
                $('#setting_name_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#setting_name_error').html('');
            }

            if ($('#value').val() == '') {
                $('#value_error').html('<span style="color: red">Lütfen değer giriniz</span>');
                error = true;
            } else {
                $('#value_error').html('');
            }



            if(error){
                return false;
            }

            //   swal("ok");
            save(formData, '{{route('site.create-setting-post')}}', '', '','');
        });
    </script>
@endsection


