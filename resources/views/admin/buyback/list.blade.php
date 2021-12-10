@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
@endsection
@section('main')
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <button type="button"  style="display: none" id="show-lg-modal" data-toggle="modal" data-target="#lg-modal">Large modal</button>
                        </div>
                    </div>
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Model Adı</th>
                            <th>Kullanıcı</th>
                            <th>Önerilen Tutar</th>

                            <th>Durum</th>
                            <th class="text-center">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($buybacks as $buyback)
                            <tr>
                                <td width="20%">{{$buyback->model()->first()->Modelname}}
                                @if($buyback['color_id'])
                                      ( {{$buyback->color()->first()->color_name}} )
                                    @endif

                                </td>
                                <td>
                                    <a href="{{route('buyback.buyback-list',$buyback->buybackuser()->first()->id)}}" style="font-size: 25px">
                                   {{$buyback->buybackuser()->first()->name}}
                                   {{$buyback->buybackuser()->first()->surname}}
                                    </a>

                                    <a href="#" onclick="userDetail({{$buyback['buyback_user_id']}})"
                                       class="btn btn-secondary"><i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                                <td>{{$buyback['offer_price']}} TL</td>


                                <td>
                                    {{$bb_status_array[$buyback['status']]}}
                                </td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <a href="{{route('buyback.buyback-update',$buyback['id'])}}"
                                           class="btn btn-primary">Güncelle <i class="fa fa-pencil"></i>
                                        </a>


                                    </div>
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

        function userDetail(user_id){
            $('#show-lg-modal').click();
            $.get( "{{url('admin/buyback/user-info')}}/"+user_id, function( data ) {
                $( "#lg-modal-title" ).html('Kullanıcı Detay');
                $( "#lg-modal-body" ).html( data );

            });
        }
    </script>

@endsection
