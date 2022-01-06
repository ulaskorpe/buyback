
<div class="row">
    <div class="col-12">
        <form id="add-stock"  action="{{route('products.product-stock-movement')}}" method="post"
              enctype="multipart/form-data">
            {{csrf_field()}}

            <input type="hidden"   name="count" value="0">
            <input type="hidden" id="product_id" name="product_id" value="{{$product['id']}}">
            <div class="form-group row">

                <div class="col-lg-2">
                    <label class="col-form-label font-weight-bold">Renk :</label>
                    <select name="color_id" id="color_id" class="form-control">
                        <option value="0">Seçiniz</option>
                        @foreach($stock_colors as $color)
                            <option value="{{$color['id']}}">{{$color['color_name']}}</option>
                        @endforeach
                    </select>
                    <span id="color_id_error"></span>
                </div>
                <div class="col-lg-2">
                    <label class="col-form-label font-weight-bold">Hafıza :</label>
                    <select name="memory_id" id="memory_id" class="form-control">
                        <option value="0">Seçiniz</option>
                        @foreach($stock_memories as $memory)
                            <option value="{{$memory['id']}}">{{$memory['memory_value']}} GB</option>
                        @endforeach
                    </select>
                    <span id="memory_id_error"></span>
                </div>
                <div class="col-lg-2">
                    <label class="col-form-label font-weight-bold">Miktar :</label>
                    <input type="number" class="form-control" name="quantity" id="quantity" value="0">
                    <span id="quantity_error"></span>
                </div>
                <div class="col-lg-2">
                    <label class="col-form-label font-weight-bold">Hareket :</label>
                    <select name="status" id="status" class="form-control">
                        <option value="in">GİRİŞ</option>
                        <option value="out">ÇIKIŞ</option>

                    </select>
                    <span id="status_error"></span>
                </div>
                <div class="col-lg-2">

                    <button type="submit" class="btn btn-primary font-weight-bold rounded-round" style="margin-top: 33px">EKLE</button>

                </div>
            </div>

        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" style="font-size: 18px">
    <thead>
    <tr>
        <th>Renk</th>
        <th>Hafıza</th>
        <th>Miktar</th>
        <th>Durum</th>
        <th class="text-center">İşlemler</th>
    </tr>
    </thead>
    <tbody>
    @foreach($stock_movements as $movement)
        <tr>
            <td>
                {{$movement->color()->first()->color_name}}
                <div style="width: 30px;height: 30px;background:{{$movement->color()->first()->color_code}};float: right "></div>
            </td>
            <td>
            {{$movement->memory()->first()->memory_value}} GB
            </td>
            <td>
                {{$movement['quantity']}}
            </td>
            <td>
                @if($movement['status']=='in')
                    <h2 style="color: green">GİRİŞ</h2>
                @else
                    <h2 style="color: red">ÇIKIŞ</h2>
                    @endif

            </td>
            <td class="text-center">

                    <button class="btn btn-primary font-weight-bold rounded-round" onclick="updateStock({{$movement['id']}})" >GÜNCELLE</button>

            </td>

        </tr>

    @endforeach
    </tbody>
</table>

    </div>
</div>
