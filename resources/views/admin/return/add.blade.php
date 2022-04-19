@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/mjolnic-bootstrap-returnpicker/dist/css/bootstrap-returnpicker.min.css')}}" rel="stylesheet">

@endsection

@section('main')

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="{{route("return.return-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Sorun Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="create-return" action="{{route('return.returnAdd-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Sorun Tanımı</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="description" id="description"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           title="Renk Adı" placeholder="">
                                    <div id="description_error"></div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Sıra</label>
                                <div class="col-lg-2">
                                    <select name="order" id="order" class="form-control">
                                        @for($i=$count+1;$i>0;$i--)
                                            <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                    </select>

                                    <span class="form-text text-muted"></span>
                                    <span id="sample_error"></span>
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
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">SORUN EKLE
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
    <script src="{{url("js/jsreturn.min.js")}}"></script>
    <script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
    <script src="{{url('vendors/moment/min/moment.min.js')}}"></script>

    <script src="{{url('vendors/mjolnic-bootstrap-returnpicker/dist/js/bootstrap-returnpicker.min.js')}}"></script>
    <script>


        $('#create-return').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;
            if ($('#description').val() == '') {
                $('#description_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#description_error').html('');
            }




            if(error){
                return false;
            }

            //   swal("ok");
            save(formData, '{{route('return.returnAdd-post')}}', '', '','');
        });
    </script>
@endsection


