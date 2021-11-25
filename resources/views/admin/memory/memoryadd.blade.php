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
                            <a href="{{route("memory.memorylist")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Hafıza Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>
                        <form id="create-memory" action="#" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Hafıza (GB)</label>
                                <div class="col-lg-3">
                                    <input type="number" class="form-control" name="memory_value" id="memory_value"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                    >
                                    <div id="memory_value_error"></div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                                <div class="col-lg-10">

                                    <label>
                                        <input type="checkbox" class="js-switch" checked="true" name="status" id="status" value="13" data-switchery="true"
                                               style="display: none;">
                                    </label>
                                </div>
                            </div>
                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">HAFIZA EKLE
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

        $('#create-memory').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            var error = false;
            if ($('#memory_value').val() == '') {
                $('#memory_value_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $.get("{{url('admin/memory/check-memory')}}/" + $('#memory_value').val(), function (data) {

                    if(data!="ok"){
                        $('#memory_value').val('');
                        $('#memory_value_error').html('<span style="color: red">Bu hafıza boyutu tanımlanmış</span>');
                        error = true;
                    }else{

                        $('#memory_value_error').html('');
                        save(formData, '{{route('memory.memoryadd-post')}}', '', '','');

                    }

                });
            }




        });
    </script>
@endsection
