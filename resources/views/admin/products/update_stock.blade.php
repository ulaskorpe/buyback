<form id="update-stock" action="{{route('model.model-add-post')}}" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="hidden" id="stock_id" name="stock_id" value="{{$movement['id']}}">
    <input type="hidden" id="product_id" name="product_id" value="{{$movement['product_id']}}">

    <div class="form-group row">
        <div class="col-2">
        <label class="col-form-label font-weight-bold">Renk :</label>
        </div>
        <div class="col-4">
        <select name="color_id_update" id="color_id_update" class="form-control">

            @foreach($stock_colors as $color)
                <option value="{{$color['id']}}" @if($movement['color_id']==$color['id']) selected @endif>{{$color['color_name']}}</option>
            @endforeach
        </select>
        <span id="color_id_update_error"></span>
        </div>
    </div>


    <div class="form-group row">
        <div class="col-2">
            <label class="col-form-label font-weight-bold">Hafıza :</label>
        </div>
        <div class="col-4">
            <select name="memory_id_update" id="memory_id_update" class="form-control">

                @foreach($stock_memories as $memory)
                    <option value="{{$memory['id']}}" @if($movement['memory_id']==$memory['id']) selected @endif>{{$memory['memory_value']}} GB</option>
                @endforeach
            </select>
            <span id="memory_id_update_error"></span>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-2">
            <label class="col-form-label font-weight-bold">Miktar :</label>
        </div>
        <div class="col-4">
            <input type="number" class="form-control" name="quantity_update" id="quantity_update" value="{{$movement['quantity']}}">
            <span id="quantity_update_error"></span>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-2">
            <label class="col-form-label font-weight-bold">Hareket :</label>
        </div>
        <div class="col-4">
            <select name="status_update" id="status_update" class="form-control">
                <option value="in" @if($movement['status']=='in') selected @endif>GİRİŞ</option>
                <option value="out" @if($movement['status']=='out') selected @endif>ÇIKIŞ</option>

            </select>
        </div>
    </div>


    <div class="form-group row">
        <div class="col-2">

        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-primary font-weight-bold rounded-round">GÜNCELLE</button>
        </div>

    </div>
</form>

<script>

    $(document).ready(function () {

    });
    $('#update-stock').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        var error = false;

        if ($('#quantity_update').val() <= 0 ) {
            $('#quantity_update_error').html('<span style="color: red">Miktar Pozitif Sayı olmalıdır</span>');
            error = true;
        } else {
            $('#quantity_update_error').html('');
        }




        if (error) {
            return false;
        } else {
            //swal("ok"+count);'/check-stock/{product_id}/{color_id}/{memory_id}/{qty}/{stock_id}'


            if(formData.get('status_update')=='out'){
            //    console.log("{{url('admin/products/check-stock')}}/{{$movement['product_id']}}/"+$('#color_id_update').val()+"/"+$('#memory_id_update').val()+"/"+$('#quantity_update').val());
                $.get("{{url('admin/products/check-stock')}}/{{$movement['product_id']}}/"+$('#color_id_update').val()+"/"+$('#memory_id_update').val()+"/"+$('#quantity_update').val()+"/{{$movement['id']}}" , function (data) {
                    if(data=='ok'){
                        save(formData, '{{route('products.product-stock-movement-update')}}', '', '', '');
                    }else{
                        swal(""+data);
                        console.log("{{url('admin/products/check-stock')}}/{{$movement['product_id']}}/"+$('#color_id_update').val()+"/"+$('#memory_id').val()+"/"+$('#quantity').val());
                    }
                });
            }else{
                save(formData, '{{route('products.product-stock-movement-update')}}', '', '', '');
            }

        }
    });

</script>
