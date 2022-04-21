<div class="row">
    <div class="col-12">

        <form id="order-cancel-update" action="#" method="post"
              enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" id="id" name="id" value="{{$order['id']}}">
            <input type="hidden" id="return_id" name="return_id" value="{{$return['id']}}">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label font-weight-semibold">İade Kodu :</label>
                <div class="col-lg-3" id="customer_address">

                    {{$return['return_code']}}
                </div>

            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label font-weight-semibold">Gönderici :</label>
                <div class="col-lg-3" id="customer_address">

                    {{$return['name_surname']}}
                </div>

            </div>
            @if($order['customer_id']>0)
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label font-weight-semibold">Müşteri :</label>
                    <div class="col-lg-3" id="customer_address">
                            {{$order->customer()->first()->name}}
                            {{$order->customer()->first()->surname}}
                    </div>

                </div>

            @endif

            <div class="form-group row">
                <label class="col-lg-2 col-form-label font-weight-semibold">Kargo Şirketi :</label>
                <div class="col-lg-3">
                    <select name="cargo_company_id" id="cargo_company_id" class="form-control" >
                        @foreach($cargo_companies as $cc)
                            <option value="{{$cc['id']}}" @if($cc['id']==$return['cargo_company_id']) selected @endif>
                                {{$cc['name']}}

                            </option>
                        @endforeach

                    </select>
                </div>

            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label font-weight-semibold">Kargo Kodu :</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" name="cargo_code_return" id="cargo_code_return"
                           value="{{$return['cargo_code']}}" data-popup="tooltip" data-trigger="focus"
                           title="" placeholder="">
                    <span id="cargo_code_error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label font-weight-semibold">Gönderileceği Adres :</label>
                <div class="col-lg-4">
                    <select name="service_address_id_return" id="service_address_id_return"  class="form-control" onchange="serviceAddressReturn(this.value)">
                        <option value="0">Seçiniz</option>
                        @foreach($service_addresses as $address)
                            <option value="{{$address['id']}}"  @if($return['service_address_id']==$address['id']) selected @endif>{{$address['title']}}</option>
                        @endforeach
                    </select>
                    <span id="service_address_id_error"></span>
                </div>
            </div>

            <div class="row my-3"><div class="col-lg-2"></div>   <div class="col-lg-5" id="service_address_return"></div></div>




            <div class="row my-3"><div class="col-lg-2"></div>   <div class="col-lg-5" id="bank_detail"></div></div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label font-weight-semibold">Durum :</label>
                <div class="col-lg-4">
                    <select name="status_return" id="status_return"  class="form-control" >
                        <option value="{{\App\Enums\OrderReturnStatus::init}}" @if($return['status']==\App\Enums\OrderReturnStatus::init) selected @endif>İptal Edildi</option>
                        <option value="{{\App\Enums\OrderReturnStatus::on_cargo}}" @if($return['status']==\App\Enums\OrderReturnStatus::on_cargo) selected @endif>Kargoya verildi</option>
                        <option value="{{\App\Enums\OrderReturnStatus::received}}" @if($return['status']==\App\Enums\OrderReturnStatus::received) selected @endif>Tamamlandı</option>

                    </select>
                    <span id="service_address_id_error"></span>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">İPTAL GÜNCELLE
                    <i class="icon-paperplane ml-2"></i></button>
            </div>
        </form>
    </div>
</div>