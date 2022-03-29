<div class="card">
<div class="card-header">
<button class="btn btn-primary"  onclick="cartSwitch()" id="cart_header">Sepetteki Ürünler </button>
 <button class="btn btn-primary" disabled onclick="cartSwitch()" id="order_header">Alışveriş Geçmişi </button>

</div>
    <div class="card-body">
        <div id="cart_items_table" style="display: none">
<div class="row">
    <div class="col-12">

        <table id="datatable-responsive-4" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Ürün</th>
                <th>Durum</th>
                <th>Tutar</th>

                <th class="text-center">İşlemler</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cart_items as $cart_item)
                <tr>
                    <td>
                        {{$cart_item->product()->first()->title}}<br>
                        {{$cart_item->product()->first()->brand()->first()->BrandName}}
                        &gt;
                        {{$cart_item->product()->first()->model()->first()->Modelname}}
                        @if($cart_item['memory_id']>0)
                            <br>
                            {{$cart_item->memory()->first()->memory_value}} GB
                        @endif
                        @if($cart_item['color_id']>0)
                            <br>
                            {{$cart_item->color()->first()->color_name}}
                        @endif
                    </td>
                    <td>{{$status_array[$cart_item['status']]}}</td>
                    <td>
                        @if($cart_item->product()->first()->price > $cart_item->product()->first()->price_ex)
                            {{$cart_item->product()->first()->price_ex}} x {{ $cart_item['quantity']}} =
                {{$cart_item->product()->first()->price_ex * $cart_item['quantity']}}
                            @else
                            {{$cart_item->product()->first()->price}} x {{ $cart_item['quantity']}} =
                            {{$cart_item->product()->first()->price * $cart_item['quantity']}}
                        @endif
                    </td>
                    <td>

                        <button class="btn btn-danger"  onclick="delete_cart_item({{$cart_item['id']}})"><i class="fa fa-close"></i></button>

                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>



    </div>
</div>
        </div>

        <div id="orders_table">
            <div class="row">
    <div class="col-12">
        <p class="h2">Alışveriş Geçmişi</p>
        <table id="datatable-responsive-3" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Sipariş Kodu</th>
                <th>Müşteri</th>
                <th>Ürünler</th>

                <th>Tutar / Durum</th>

                <th>Tarih</th>
                <th class="text-center">İşlemler</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>
                        {{$order['order_code']}}
                    </td>
                    <td>
                        {{$order->customer()->first()->name}} {{$order->customer()->first()->surname}}

                    </td>
                    <td>  @php
                            $amount = 0;
                        @endphp
                        <table width="100%">

                            @foreach($order->cart_items()->get() as $item)
                                <tr>
                                    <td  >
                                        {{$item->product()->first()->title}}
                                        @if($item['color_id']>0)
                                            <br> {{$item->color()->first()->color_name}}
                                        @endif
                                        @if($item['memory_id']>0)
                                            <br> {{$item->memory()->first()->memory_value}} GB
                                        @endif
                                    </td>
                                    <td>
                                        {{$item->product()->first()->price}}TL x  {{$item['quantity']}}
                                    </td>

                                </tr>
                                @php
                                    $amount += $item->product()->first()->price * $item['quantity'];
                                @endphp

                            @endforeach
                        </table>

                    </td>

                    <td>
                        @if($amount != $order['amount'])
                            {{$order['amount']}}TL <br>
                            ({{$amount}}TL)
                        @else
                            {{$amount}}TL
                        @endif
                        <hr>
                        @if($order['order_method'] > 0)
                            <b>Havale</b><br>
                            {{$order->order_method()->first()->bank_name}}/{{$order->order_method()->first()->branch}}<br>

                            {{$order->order_method()->first()->name_surname}}
                            {{$order->order_method()->first()->iban}}
                        @else
                            KrediKartı Ödemesi
                        @endif

                        <h2>{{$order_status[$order['status']]}}</h2>

                    </td>

                    <td>{{\Carbon\Carbon::parse($order['created_at'])->format('d.m.Y H:i')}}</td>
                    <td>
                        <button class="btn btn-primary" onclick="window.open('{{route('customer.order-update',$order['id'])}}','_blank')">GÜNCELLE</button>

                    </td>

                </tr>
            @endforeach

            </tbody>
        </table>



    </div>
</div>
        </div>
    </div>

</div>

