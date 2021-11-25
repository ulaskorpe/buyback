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
                            <a href="{{route("question.question-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Soru Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>
                        <form id="create-question" action="{{route('question.questionadd-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Soru</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="question_value" id="question_value"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                    >
                                    <div id="question_value_error"></div>
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
                            @for($i=1;$i<3;$i++)
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label font-weight-semibold">{{$i}}.Yanıt</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" name="answer{{$i}}" id="answer{{$i}}"
                                               value="" data-popup="tooltip" data-trigger="focus" >
                                    </div>

                                </div>
                            @endfor

                            <div id="answers_div"></div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold"> </label>
                                <div class="col-lg-5">
                                    <input type="button" id="addAnswerBtn" class="btn btn-primary" value="Yanıt Ekle" onclick="addAnswerDiv()">

                                </div>
                            </div>
                            <input type="hidden" id="count" name="count" value="2">
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">SORU EKLE
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

        var say=3;

        function addAnswerDiv(){

            $('#answers_div').append('<div class="form-group row"><label class="col-lg-2 col-form-label font-weight-semibold">'+say+'.Yanıt</label><div class="col-lg-3"><input type="text" class="form-control" name="answer'+say+'" id="answer'+say+'" value=""></div></div>');
            $('#count').val(say);
            say++;
            if(say==6){
                $('#addAnswerBtn').hide();
            }
        }

        $('#create-question').submit(function (e) {

            e.preventDefault();
            var formData = new FormData(this);
            var error = false;
            if ($('#question_value').val() == '') {
                $('#question_value_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#question_value_error').html('');
                save(formData, '{{route('question.questionadd-post')}}', '', '', '');
            }


        });
    </script>
@endsection
