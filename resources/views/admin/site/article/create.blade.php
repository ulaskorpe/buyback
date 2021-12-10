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
                            <a href="{{route("site.article-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Yazı Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="create-article" action="{{route('site.create-article-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Başlık :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="title" id="title"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Başlık Yazısı">
                                    <span id="title_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Açıklama :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="prologue" id="prologue"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Açıklama Yazısı">
                                    <span id="prologue_error"></span>
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
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">Yazı EKLE
                                    <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{url("js/save.js")}}"></script>
    <script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
    <script>

        $('#create-article').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;

            if ($('#title').val() == '') {
                $('#title_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#title_error').html('');
            }


            if(error){
                return false;
            }else{
                save(formData, '{{route('site.create-article-post')}}', '', '','');
            }
        });
    </script>
@endsection
