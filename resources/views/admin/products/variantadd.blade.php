<form id="create-variant" action="{{route('products.add-variant-post')}}" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Varyant Grubu</label>
        <div class="col-lg-8"><h2>{{$group['group_name']}}</h2>
            <input type="hidden"  name="group_id" id="group_id"
                   value="{{$group['id']}}">
            <input type="hidden"  name="product_id" id="product_id"
                   value="{{$product_id}}">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Varyant Adı</label>
        <div class="col-lg-8">
            <input type="text" class="form-control" name="variant_name" id="variant_name"
                   value=""   placeholder="Varyant Adı">
            <span id="variantname_error"></span>
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
    <div class="form-group row">
        <label class="col-lg-2 col-form-label font-weight-semibold">Var / Yok</label>
        <div class="col-lg-8">
            <label>
                <input type="checkbox" class="js-switch"   name="binary" id="binary" value="13" data-switchery="true"
                       style="display: none;">



            </label>
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
        $("#binary")[0] && Array.prototype.slice.call(document.querySelectorAll("#binary")).forEach(function (e) {
            new Switchery(e, {color: "#26B99A"})
        })

    });





    $('#create-variant').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var error = false;

        if ($('#variant_name').val() == '') {
            $('#variantname_error').html('<span style="color: red">Lütfen giriniz</span>');
            error = true;
        } else {
            $('#variantname_error').html('');
        }

        //   console.log(error);
        if(error){
            return false;
        }else{
            //    swal("ok");
            save(formData, '{{route('products.add-variant-post')}}', '', '','');
        }
    });
</script>
