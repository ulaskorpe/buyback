@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">

@endsection
@section('main')
    <button type="button" style="display: none"  data-toggle="modal" data-target=".bs-example-modal-lg" id="modal_btn"></button>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">

                                <button type="button"
                                        class="btn btn-primary" onclick="add_address()">
                                    <b><i class="icon-plus-circle2"></i></b> Yeni Adres Ekle
                                </button>

                        </div>
                    </div>
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Başlık</th>
                            <th>Yetkili</th>
                            <th>Adres</th>
                            <th>E-posta</th>
                            <th>Telefon</th>
                            <th class="text-center">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($service_addresses as $address)
                            <tr>
                                <td>{{$address['title']}}</td>
                                <td>{{$address['name_surname']}}</td>
                                <td>{{$address['address']}}</td>
                                <td>
                                    {{$address->city()->first()->name}}
                                    @if($address['town_id']>0)
                                        &gt;{{$address->town()->first()->name}}
                                        @if($address['district_id']>0)<br>
                                        &gt;{{$address->district()->first()->name}}
                                        @if($address['neighborhood_id']>0)
                                            &gt;{{$address->neighborhood()->first()->name}}
                                        @endif
                                        @endif
                                    @endif

                                </td>
                                <td>
                                    {{$address['phone_number']}}
                                    @if(!empty($address['phone_number_2']))
                                        <hr>
                                        {{$address['phone_number_2']}}
                                    @endif

                                </td>
                                <td>

                                    <button class="btn btn-primary"  onclick="update_address({{$address['id']}})">GÜNCELLE</button>

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

    <script src="{{url("js/save.js")}}"></script>
    <script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>

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


        function add_address(){
            $('#modal_btn').click();
            $.get("{{route('add-service-address')}}" , function (data) {
                $('#lg-modal-title').html('Adres Ekle');
                $('#lg-modal-body').html(data);



            });
        }


        function update_address(address_id){
            $('#modal_btn').click();
            $.get("{{url('admin/update-service-address')}}/"+address_id , function (data) {
                $('#lg-modal-title').html('Adres Güncelle');
                $('#lg-modal-body').html(data);



            });
        }
    </script>

@endsection
