<form id="create-variant-value" action="{{route('products.add-variant-value-post')}}" method="post" enctype="multipart/form-data">
    {{csrf_field()}}

    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Varyant </label>
        <div class="col-lg-8"><h2>{{$variant['variant_name']}}</h2>
            <input type="hidden"  name="variant_id" id="variant_id"
                   value="{{$variant['id']}}">
            <input type="hidden"  name="product_id" id="product_id"
                   value="{{$product_id}}">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Değer</label>
        <div class="col-lg-8">
            <input type="text" class="form-control" name="value" id="value"
                   value=""   placeholder="Değer">
            <span id="value_error"></span>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Sıra :</label>
        <div class="col-lg-2">
            <select name="order" id="order" class="form-control">
                @for($i=1;$i<$count;$i++)
                    <option value="{{$i}}">{{$i}}</option>
                @endfor
            </select>
        </div>
    </div>

    <br>
    <!-- /touchspin spinners -->
    <div class="text-center">
        <button type="submit" class="btn btn-primary font-weight-bold rounded-round">Varyant EKLE
            <i class="icon-paperplane ml-2"></i></button>
    </div>
</form>
<script src="{{url("js/save.js")}}"></script>
<script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
<script>

    $(document).ready(function () {


    });





    $('#create-variant-value').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var error = false;

        if ($('#value').val() == '') {
            $('#value_error').html('<span style="color: red">Lütfen değer giriniz</span>');
            error = true;
        } else {
            $('#value_error').html('');
        }

        //   console.log(error);
        if(error){
            return false;
        }else{
            //    swal("ok");
            save(formData, '{{route('products.add-variant-value-post')}}', '', '','');
        }
    });
</script>
