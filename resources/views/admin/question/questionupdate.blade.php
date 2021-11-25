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
                    <form id="update-question" action="{{route('question.questionupdate-post')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="id" value="{{$question['id']}}" id="id">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Soru</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" name="question_value" id="question_value"
                                       value="{{$question['question']}}" data-popup="tooltip" data-trigger="focus"
                                >
                                <div id="question_value_error"></div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                            <div class="col-lg-8">

                                <label>
                                    <input type="checkbox" class="js-switch"  @if($question['status']==1) checked @endif  name="status" id="status" value="13" data-switchery="true"
                                           style="display: none;">
                                </label>


                            </div>
                        </div>
                        @php
                            $i=1;
                        @endphp
                        @foreach($answers as $answer)
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">{{$answer['count']}}.Yanıt</label>

                                <div class="col-lg-3">

                                    <input type="text" class="form-control has-feedback-left" name="answer{{$answer['id']}}" id="answer{{$answer['id']}}"
                                           value="{{$answer['answer']}}" data-popup="tooltip" data-trigger="focus" >

                                    <span class="form-control-feedback left" aria-hidden="true"> {{$answer['key']}}.</span>

                                    <div id="answer{{$answer['count']}}_error"></div>
                                </div>
                                <div class="col-lg-3">
                                    <input type="button" value="GÜNCELLE" onclick="updateAnswer('{{$answer['id']}}')" class="btn btn-primary">
                                </div>
                            </div>
                            @php
                                $i++;
                            @endphp
                        @endforeach


                        <input type="hidden" id="count" name="count" value="{{$i-1}}">
                        <!-- /touchspin spinners -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary font-weight-bold rounded-round">SORU GÜNCELLE
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


        function updateAnswer(answer_id){

            if ($('#answer'+answer_id).val() == '') {
                swal("Yanıt değeri giriniz" ,"","error");
                $('#answer'+answer_id).focus();
            }else{

               // swal($('#answer'+answer_id).val()+":"+answer_id);

            $.get( "{{url('admin/question/update-answer')}}/"+answer_id+"/"+$('#answer'+answer_id).val(), function( data ) {
                swal(data+"");
                setTimeout(function(){ location.reload();}, 1000);
            });
            }

        }


        $('#update-question').submit(function (e) {

            e.preventDefault();
            var formData = new FormData(this);
            var error = false;
            if ($('#question_value').val() == '') {
                $('#question_value_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#question_value_error').html('');

            }


            @for($j=1;$j<($i+1);$j++)
            if ($('#answer{{$j}}').val() == '') {
                $('#answer{{$j}}_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#answer{{$j}}_error').html('');

            }
            @endfor

            if(!error){

                save(formData, '{{route('question.questionupdate-post')}}', '', '', '');
            }
        });
    </script>
@endsection
