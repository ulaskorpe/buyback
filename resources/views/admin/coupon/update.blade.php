@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">

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
                            <a href="{{route("customer.coupon-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Kupon Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="update-coupon" action="{{route('customer.couponUpdate-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" id="id" name="id" value="{{$coupon['id']}}">
                            <div class="form-group row"><h3>{{$coupon['code']}}</h3></div>
                            <div class="form-group row">

                                <div class="col-lg-2">
                                    <label class="col-form-label font-weight-bold">Tutar</label>
                                    <input type="number" class="form-control" name="amount" id="amount"
                                           value="{{$coupon['amount']}}" data-popup="tooltip" data-trigger="focus"
                                    >
                                    <div id="amount_error"></div>
                                </div>

                                <div class="col-lg-2">
                                    <label class="col-form-label font-weight-bold">Yüzde</label>
                                    <select name="percentage" id="percentage" class="form-control">

                                        @for($i=0;$i<101;$i++)
                                            <option value="{{$i}}" @if($i==$coupon['percentage']) selected @endif>%{{$i}}</option>
                                        @endfor
                                    </select>
                                    <div id="percentage_error"></div>
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-lg-2">
                                    <label class="col-form-label font-weight-bold">Kullanım Sayısı :  ({{$coupon['used']}})</label>
                                    <input type="number" class="form-control" name="usage" id="usage"
                                           value="{{$coupon['usage']}}" data-popup="tooltip" data-trigger="focus"
                                    >
                                    <div id="usage_error"></div>


                                </div>


                                <div class="col-lg-2">
                                    <label class="col-form-label font-weight-bold">Son Kullanım Tarihi</label>
                                    <input type="date" class="form-control" name="expires_at" id="expires_at"
                                           value="{{\Carbon\Carbon::parse($coupon['expires_at'])->format('Y-m-d')}}"  >
                                    <div id="expires_at_error"></div>


                                </div>


                            </div>



                            <div class="form-group row">
                                <label class="col-lg-1 col-form-label font-weight-bold">Durum</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" @if($coupon['is_active']) checked="true"@endif name="status" id="status" value="13" data-switchery="true"
                                               style="display: none;">
                                    </label>
                                </div>
                            </div>
                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">KUPON GÜNCELLE
                                    <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card my-2">
                    <div class="card-body">
                        <div class="text-center">
                            <h3>Kullanıldığı Siparişler</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 px-5">

                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Sipariş Kodu</th>
                                    <th>Sipariş Tutarı</th>
                                    <th>İndirim Tutarı</th>
                                    <th>Kullanım/Kalan</th>
                                    <th>Durum</th>
                                    <th>Son Kullanım</th>
                                    <th class="text-center">İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($coupon->orders()->get() as $order)
                                    <tr>

                                        <td>{{$order['order_code']}}</td>
                                        <td>{{$order['amount']}}</td>
                                        <td>{{$order['discount']}}</td>
                                        <td>{{$coupon['percentage']}}</td>
                                        <td>{{$coupon['used']}} / {{$coupon['usage']}}</td>
                                        <td>
                                            @if($coupon['is_active']==1)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Pasif</span>
                                            @endif
                                        </td>
                                        <td> {{ \Carbon\Carbon::parse($coupon['expires_at'])->format('d-m-Y')}}</td>
                                        <td class="text-center">
                                            <div class="list-icons">

                                                <a href="{{route("customer.order-update",[ $order['id']])}}"
                                                   class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Kullanıldığı Siparişler"><i class="fa fa-eye"></i></a>



                                            </div>
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
    </div>
    <!-- /content area -->


@endsection
@section('scripts')
    <script src="{{url("js/save.js")}}"></script>

    <script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
    <script src="{{url('vendors/moment/min/moment.min.js')}}"></script>

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

        $('#update-coupon').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;
            if ($('#percentage').val() == 0 && $('#amount').val()==0) {
                $('#percentage_error').html('<span style="color: red">Yüzde ya da tutar belirtiniz</span>');
                $('#amount_error').html('<span style="color: red">Yüzde ya da tutar belirtiniz</span>');
                error = true;
            } else {
                $('#percentage_error').html('<span style="coupon: red"></span>');
                $('#amount_error').html('<span style="coupon: red"></span>');
            }

            if ($('#usage').val()<1) {
                $('#usage_error').html('<span style="color: red">En az 1 olmalı</span>');
                error = true;
            } else {
                $('#usage_error').html('');
            }



            if(error){
                return false;
            }

            //   swal("ok");
            save(formData, '{{route('customer.couponUpdate-post')}}', '', '','');
        });
    </script>
@endsection


