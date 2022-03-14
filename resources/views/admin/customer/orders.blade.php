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

                        </div>
                    </div>
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
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
                                        <button class="btn btn-primary" onclick="window.open('{{route('customer.order-update',$order['id'])}}','_self')">GÜNCELLE</button>

                                    </td>

                                </tr>
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
