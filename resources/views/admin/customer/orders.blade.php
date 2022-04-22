@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">

@endsection
@section('main')

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="row">
                                <div class="col-10"></div>
                                <div class="col-2">
                                    <select name="type" id="type" class="form-control" onchange="window.open('{{url('/admin/customers/orders')}}/'+this.value,'_self')">
                                        <option value="x">Tüm Siparişler</option>
                                        <option value="{{\App\Enums\CartItemStatus::init}}" @if($type==\App\Enums\CartItemStatus::init) selected @endif>Sepetteki Siparişler</option>
                                        <option value="{{\App\Enums\CartItemStatus::paid}}" @if($type==\App\Enums\CartItemStatus::paid) selected @endif>Ödenmiş Siparişler</option>
                                        <option value="{{\App\Enums\CartItemStatus::sent}}" @if($type==\App\Enums\CartItemStatus::sent) selected @endif>Gönderilmiş Siparişler</option>
                                        <option value="{{\App\Enums\CartItemStatus::canceled}}" @if($type==\App\Enums\CartItemStatus::canceled) selected @endif>İptal Edilmiş Siparişler</option>
                                        <option value="{{\App\Enums\CartItemStatus::completed}}" @if($type==\App\Enums\CartItemStatus::completed) selected @endif>Tamamlanmış Siparişler</option>
                                        <option value="{{\App\Enums\CartItemStatus::returned}}" @if($type==\App\Enums\CartItemStatus::returned) selected @endif>Yeniden Gönderilmiş Siparişler</option>

                                    </select>


                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Sipariş Kodu</th>
                            <th>Müşteri/Ziyaretçi</th>
                            <th>Ürünler</th>

                            <th>Tutar / Durum</th>

                            <th>Tarih</th>
                            <th class="text-center">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)

                                @if($order['customer_id']>0)
                                    @if($order->cart_items()->count()>0)
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

                                                    @php
                                                        $price =($item->product()->first()->price>$item->product()->first()->price_ex) ?$item->product()->first()->price:$item->product()->first()->price_ex;
                                                    @endphp
                                                    <tr>
                                                        <td  >
                                                            {{$item->product()->first()->title}}

                                                        </td>
                                                        <td>
                                                            {{$price}}TL x  {{$item['quantity']}}
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


                                                    @if(!empty($order['receipt']))
                                                        <hr>
                                                        <a href="{{url($order['receipt'])}}" target="_blank"><b>Dekont</b></a>
                                                    @endif
                                            @else
                                                KrediKartı Ödemesi
                                            @endif

                                            <h2>{{$order_status[$order['status']]}}</h2>

                                        </td>

                                        <td>{{\Carbon\Carbon::parse($order['created_at'])->format('d.m.Y H:i')}}</td>
                                        <td>
                                            <button class="btn btn-primary" onclick="window.open('{{route('customer.order-update',$order['id'])}}','_self')">GÜNCELLE</button>

                                        </td>

                                    </tr>
                                    @endif
                                @else

                                    @if($order->guest_cart_items()->count()>0)
                                    <tr>
                                        <td>
                                            {{$order['order_code']}}
                                        </td>
                                        <td>

                                        @if(!empty($order->guest()->first()->id))
                                            {{$order->guest()->first()->guid}}
                                            @endif

                                        </td>
                                        <td>  @php
                                                $amount = 0;
                                            @endphp
                                            <table width="100%">

                                                @foreach($order->guest_cart_items()->get() as $item)
                                                    @php
                                                        $price =($item->product()->first()->price>$item->product()->first()->price_ex) ?$item->product()->first()->price:$item->product()->first()->price_ex;
                                                    @endphp
                                                    <tr>
                                                        <td  >
                                                            {{$item->product()->first()->title}}

                                                        </td>
                                                        <td>
                                                            {{$price}}TL
                                                        </td>

                                                    </tr>
                                                    @php
                                                        $amount += $item->product()->first()->price ;
                                                    @endphp

                                                @endforeach
                                            </table>

                                        </td>

                                        <td>
                                            @if($amount != $order['amount'])
                                                {{$order['amount']}}TL <br>
                                                ({{$amount}}TL)
                                                @if($order['banka_id']>0)
                                                    <br>
                                                    {{$order->banka()->first()->bank_name}}<br>
                                                    {{$order['amount']/$order['taksit']}} x    {{$order['taksit']}}
                                                    @endif


                                            @else
                                                {{$amount}}TL
                                            @endif
                                            <hr>
                                            @if($order['order_method'] > 0)
                                                <b>Havale</b><br>
                                                {{$order->order_method()->first()->bank_name}}/{{$order->order_method()->first()->branch}}<br>

                                                {{$order->order_method()->first()->name_surname}}
                                                {{$order->order_method()->first()->iban}}

                                                @if(!empty($order['receipt']))
                                                        <hr>
                                                        <a href="{{url($order['receipt'])}}" target="_blank"><b>Dekont</b></a>
                                                    @endif

                                            @else
                                                KrediKartı Ödemesi
                                            @endif

                                            <h2>{{$order_status[$order['status']]}}</h2>

                                        </td>

                                        <td>{{\Carbon\Carbon::parse($order['created_at'])->format('d.m.Y H:i')}}</td>
                                        <td>
                                            <button class="btn btn-primary" onclick="window.open('{{route('customer.order-update',$order['id'])}}','_self')">GÜNCELLE</button>

                                        </td>

                                    </tr>
                                        @endif
                                @endif

                            @endforeach

                        </tbody>
                    </table>
                </div>
                <!-- /basic datatable -->
            </div>
            <!-- Inline form modal -->
        </div>
    </div>
    <!-- /content area -->
@endsection
@section('scripts')


    <script src="{{url('vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
    <script src="{{url('vendors/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>

    <script>
        $(document).ready(function () {
            init_DataTables();
        });
    </script>

@endsection
