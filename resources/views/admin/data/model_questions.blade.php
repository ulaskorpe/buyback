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
                    <div class="col-6 text-left" id="total_price"><h3>{{$model['max_price']}}</h3></div>

                    <input type="hidden" id="max_price" name="max_price" value="{{$model['max_price']}}">
                    <input type="hidden" id="min_price" name="min_price" value="{{$model['min_price']}}">
                </div>
            </td>
        </tr>
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

                    <input type="hidden" name="selected{{$item['order']}}"
                                                        id="selected{{$item['order']}}" value="0">

                    <input type="hidden" name="ischecked{{$item['order']}}"
                           id="ischecked{{$item['order']}}" value="0">
                    @foreach($item['answers'] as $answer)

                        <div class="row" style="margin-top: 15px">
                            <div class="col-md-1">


                                <input type="radio" name="{{$radio}}" id="{{$radio}}"
                                                         onclick="calculateMe('{{$item['order']}}','{{$answer['value']}}','{{$answer['answer_id']}}')"
                                                         value="{{$answer['value']}}"></div>
                            <div class="col-md-4 text-left">

                                {{$answer['key']}} - {{$answer['answer']}} </div>
                            <div class="col-md-2"><input type="hidden" class="form-control"
                                                         value="{{$answer['value']}}" name="{{$name}}"
                                                         id="{{$name}}"></div>


                        </div>


                    @endforeach
                </td>
            </tr>

        @endforeach
        <tr><td colspan="2">
                <div class="row" style="margin-top: 15px">
                    <div class="col-md-12 text-center">
                        <button class="btn btn-primary" id="calculate_btn" disabled >TEKLİF AL </button>
                        <button type="button"  style="display: none" id="show-lg-modal" data-toggle="modal" data-target="#lg-modal">Large modal</button>

                    </div>


                </div>


            </td></tr>
        </tbody>
    </table>
</div>
<script>
    function calculateMe(selected,value,answer_id) {
        var total_minus = 0;
        var max_price= parseFloat($('#max_price').val());
        var min_price= parseFloat($('#min_price').val());
        $('#selected'+selected).val(value);
        $('#ischecked'+selected).val(answer_id);
        var btn = 0;
        var result ="";
        @foreach($radio_array as $item)
            total_minus+= parseFloat($('#{{$item}}').val());
        @endforeach
        @for($i=1;$i<($count+1);$i++)
           if(($('#ischecked{{$i}}').val()!=0)){
               result+=$('#ischecked{{$i}}').val()+"@";
            btn++;
        }
        @endfor
console.log(btn);
            var total = (max_price-total_minus >= min_price)? (max_price-total_minus) : min_price;
            $('#total_price').html("<h3>"+total+" TL</h3>");
            if(btn=={{$count}}){

                $("#calculate_result").val(result);
                $("#calculate_btn").prop("disabled", false);
            }
         //   console.log(total_minus+"");
    }
    $('#calculate_btn').click(function (e) {
        e.preventDefault();
        $('#show-lg-modal').click();

        $.get( "{{url('admin/data/get-offer')}}/"+$('#model_id').val()+"/"+$('#calculate_result').val(), function( data ) {
            $( "#lg-modal-title" ).html('Teklif Sonucu'+$('#model_id').val());
            $( "#lg-modal-body" ).html( data );

        });
    });

</script>
