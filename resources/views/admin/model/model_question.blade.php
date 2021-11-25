@extends('admin.main_layout')
@section('css_')
    <style>
        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #ddd;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2
        }
    </style>
@endsection
@section('main')
<div class="content">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <a href="{{route("model.model-list")}}">
                        <button type="button"
                                class="btn btn-primary">
                            <b><i class="icon-plus-circle2"></i></b> MODEL LİSTESİNE GİT
                        </button>
                    </a>
                </div>
            </div>
            <div class="table">
                <table class="table table-hover table-striped table-bordered table-lg text-center">
                    <thead>
                    <tr>
                        <th class="text-center text-teal-800 font-weight-bold"><i class="icon-price-tags2"></i>
                            CİHAZ MODEL ADI
                        </th>
                        <th class="text-center text-violet-800 font-weight-bold">
                            <h6 class="font-weight-semibold my-1"><i
                                    class="icon-circle2"></i> {{$model['Modelname']}}</h6>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($array as $item)
                        @php
                            $name= "order".$item['order'];
                        @endphp
                        <tr>
                            <td colspan="2">
                                <div class="row">
                                    <div class="col-3"> {{$item['order']}} - {{$item['question']}}</div>
                                    <div class="col-3">
                                        <select class="form-control" style="width: 100px" name="{{$name}}"
                                                id="{{$name}}" onchange="reOrderQuestions('{{$item['model_question_id']}}',this.value)">
                                            @for($i=1;$i<$last;$i++)
                                                <option value="{{$i}}"
                                                        @if($i==$item['order']) selected @endif >{{$i}}</option>
                                            @endfor

                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" class="btn btn-danger rounded-round  float-right"
                                                onclick="deleteQuestion('{{$item['model_question_id']}}')"><i
                                                class="fa fa-remove"></i>
                                        </button>
                                    </div>

                                </div>


                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                @foreach($item['answers'] as $answer)
                                    @php
                                        $name= "value".$answer['model_answer_id'];
                                    @endphp
                                    <div class="row" style="margin-top: 15px">
                                        <div class="col-md-2">{{$answer['count']}} - {{$answer['answer']}} </div>
                                        <div class="col-md-2"><input type="number" class="form-control"
                                                                     value="{{$answer['value']}}" name="{{$name}}"
                                                                     id="{{$name}}"></div>
                                        <div class="col-md-2">
                                            <button class="btn btn-primary"
                                                    onclick="valueUpdate({{$answer['model_answer_id']}})">GÜNCELLE
                                            </button>
                                        </div>

                                    </div>


                                @endforeach
                            </td>
                        </tr>

                    @endforeach

                    </tbody>
                </table>
            </div>




            <div style="background-color: #fff0f0; padding: 50px 0px 50px 0px" class="justify-content-center">
                <div class="row">

                    <div class="col-1"></div>
                    <div class="col-3">
                        <label for="">Soru Seçiniz</label>
                        <select name="other_question_id" id="other_question_id" class="form-control">
                            <option value="0">Seçiniz</option>
                            @foreach($other_questions as $question)
                                <option value="{{$question['id']}}">{{$question['question']}}</option>
                            @endforeach


                        </select>
                    </div>
                    <div class="col-1">
                        <label for="">Sıra Seçiniz</label>
                        <select name="q_count" id="q_count" class="form-control">

                            @for($i=$last;$i>0;$i--)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor

                        </select>

                    </div>
                    <div class="col-3" style="padding-top: 30px">

                        <button type="button" onclick="addQuestion()" class="btn btn-primary font-weight-bold rounded-round">SORU EKLE<i
                                class="icon-paperplane ml-2"></i></button>
                    </div>

                </div>
            </div>


        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        function reOrderQuestions(model_question_id,count){

            //swal(""+model_question_id+":"+count)
            $.get("{{url('admin/model/reorder_model_question')}}/" + model_question_id + "/" + count, function (data) {
                swal("" + data);
                setTimeout(function () {
                    window.open('{{route('model.model-questions',[$model['id']])}}', '_self')
                }, 1000);

            });

        }

        function addQuestion(){
            var q_id = $('#other_question_id').val();
            if(q_id==0){
                swal("Lütfen soru seçiniz");

                $('#other_question_id').focus();
            }else{
                $.get("{{url('admin/model/add_model_question')}}/" + q_id + "/{{$model['id']}}/" + $('#q_count').val(), function (data) {
                    swal("" + data);
                    setTimeout(function () {
                        window.open('{{route('model.model-questions',[$model['id']])}}', '_self')
                    }, 1000);

                });
            }
        }

        function valueUpdate(val) {

            $.get("{{url('admin/model/update_model_answer')}}/" + val + "/" + $('#value' + val).val(), function (data) {
                swal("" + data);
                setTimeout(function () {
                    window.open('{{route('model.model-questions',[$model['id']])}}', '_self')
                }, 1000);

            });

            //swal(""+$('#value'+val).val());
        }


        function deleteQuestion(model_question_id) {
            swal("Soru modelden silinecek, Emin misiniz?", {
                buttons: ["İptal", "Evet"],
                dangerMode: true,
            }).then((value) => {
                if (value) {

                    $.get("{{url('admin/model/delete_model_question')}}/" + model_question_id, function (data) {
                        swal("" + data);
                        setTimeout(function () {
                            window.open('{{route('model.model-questions',[$model['id']])}}', '_self')

                        }, 2000);

                        //   console.log(user_id+":"+follower_id);


                    });


                }
            })
        }
    </script>
@endsection
