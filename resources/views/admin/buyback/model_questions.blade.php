<form id="update-answers" action="{{route('buyback.buybackupdateanswers-post')}}" method="post"
      enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="hidden" name="buyback_id" id="buyback_id" value="{{$buyback['id']}}">
    <div class="table">
        <table class="table text-center table-lg">
            @php

                $radio_array=[];
                    $count = 0;
            @endphp
            <tbody>
            <tr>
                <td colspan="2">
                    <div class="row">
                        <div class="col-6 text-left"><h3>{{$model['Modelname']}}</h3></div>

                        <div class="col-3 text-left" id="offer_price"><h3>Önerilen : {{$buyback['offer_price']}} TL</h3>
                        </div>
                        <div class="col-3 text-left" id="total_price"><h3></h3></div>
                        <input type="hidden" name="price" id="price" value="">
                        <input type="hidden" name="offered_price" id="price" value="{{$buyback['offer_price']}}">
                        <input type="hidden" id="max_price" name="max_price" value="{{$model['max_price']}}">
                        <input type="hidden" id="min_price" name="min_price" value="{{$model['min_price']}}">
                        <input type="hidden" id="model_id" name="model_id" value="{{$model['id']}}">
                    </div>
                </td>
            </tr>
            @php
                $result="";

            $price = $model['max_price'];
            @endphp
            @foreach($array as $item)
                @php

                        $name= "order".$item['order'];
                        $radio= "radio".$item['order'];
                        $radio_array[]="selected".$item['order'];
                        $count++;


                @endphp
                <tr>
                    <td colspan="2">
                        <div class="row">
                            <div class="col-12 text-left"> {{$item['order']}} - {{$item['question']}}    </div>


                        </div>


                    </td>
                </tr>

                <tr>
                    <td colspan="2">

                        @foreach($item['answers'] as $answer)

                            <div class="row" style="margin-top: 15px">
                                <div class="col-md-1">
                                    @if(in_array($answer['answer_id'],$model_answer_array))
                                        @php
                                            $price = ($price >= $model['min_price']) ? ( $price - $answer['value']) : $price;

                                           // echo $price;
                                           $result.=$answer['answer_id']."@";
                                        @endphp


                                        <input type="hidden" name="selected{{$item['order']}}"
                                               id="selected{{$item['order']}}" value="{{$answer['value']}}">

                                        <input type="hidden" name="ischecked{{$item['order']}}"
                                               id="ischecked{{$item['order']}}" value="{{$answer['answer_id']}}">

                                        <input type="radio" name="{{$radio}}" id="{{$radio}}" checked
                                               onclick="calculateMe('{{$item['order']}}','{{$answer['value']}}','{{$answer['answer_id']}}')"
                                               value="{{$answer['value']}}">
                                    @else
                                        <input type="radio" name="{{$radio}}" id="{{$radio}}"
                                               onclick="calculateMe('{{$item['order']}}','{{$answer['value']}}','{{$answer['answer_id']}}')"
                                               value="{{$answer['value']}}">
                                    @endif


                                </div>
                                <div class="col-md-4 text-left">

                                    {{$answer['key']}} - {{$answer['answer']}}
                                    @if($answer['value']>0)
                                    (-{{$answer['value']}}TL)
                                        @endif
                                </div>
                                <div class="col-md-2"><input type="hidden" class="form-control"
                                                             value="{{$answer['value']}}" name="{{$name}}"
                                                             id="{{$name}}"></div>


                            </div>


                        @endforeach
                    </td>
                </tr>

            @endforeach
            <tr>
                <td colspan="2" style="font-size: 25px">Önerilen Fiyatı Kullan : <input type="checkbox"
                                                                                        class="js-switch" checked="true"
                                                                                        name="keep_offer_price"
                                                                                        id="keep_offer_price" value="13"
                                                                                        data-switchery="true"
                                                                                        style="display: none;"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="row" style="margin-top: 15px">
                        <div class="col-md-12 text-center">
                            <input type="hidden" id="calculate_result" name="calculate_result" value="{{$result}}">
                            <input type="submit" class="btn btn-primary" value="GÜNCELLE">

                        </div>


                    </div>


                </td>
            </tr>

            </tbody>
        </table>


    </div>
</form>

<script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
<script>
    $(document).ready(function () {

        $(".js-switch")[0] && Array.prototype.slice.call(document.querySelectorAll(".js-switch")).forEach(function (e) {
            new Switchery(e, {color: "#26B99A"})
        })

        @if($price < $model['min_price'])

        $('#price').val({{$model['min_price']}});

        $('#total_price').html("<h3>Hesaplanan :" + {{$model['min_price']}} + "TL</h3>");
            @else

            $('#price').val({{$price}});

        $('#total_price').html("<h3>Hesaplanan :" + {{$price}} + "TL</h3>");
            @endif


    });


    function calculateMe(selected, value, answer_id) {
        var total_minus = 0;
        var max_price = parseFloat($('#max_price').val());
        var min_price = parseFloat($('#min_price').val());
        $('#selected' + selected).val(value);
        $('#ischecked' + selected).val(answer_id);
        var btn = 0;
        var result = "";
        @foreach($radio_array as $item)
            total_minus += parseFloat($('#{{$item}}').val());
        @endforeach
        @for($i=1;$i<($count+1);$i++)
        //console.log({{$i}});
        if (($('#ischecked{{$i}}').val() != 0)) {

            //console.log('#ischecked{{$i}}');
            result += $('#ischecked{{$i}}').val() + "@";
            btn++;
        }
        @endfor
        //  console.log(result+":"+{{$count}});
        var total = (max_price - total_minus >= min_price) ? (max_price - total_minus) : min_price;
        $('#total_price').html("<h3>Hesaplanan :" + total + " TL</h3>");
        if (btn == {{$count}}) {

            $("#calculate_result").val(result);
            $("#price").val(total);
            $("#calculate_btn").prop("disabled", false);
        }
        //   console.log(total_minus+"");
    }


    $('#update-answers').submit(function (e) {

        e.preventDefault();

        var formData = new FormData(this);
        var error = false;


        if (error) {
            return false;
        }

        //   swal("ok");
        save(formData, '{{route('buyback.buybackupdateanswers-post')}}', '', 'btn-1', '');
    });
</script>

