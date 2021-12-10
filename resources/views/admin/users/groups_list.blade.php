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
                            <a href="{{route("users.group-create")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="fa fa-plus-circle"></i></b> Yeni Grup Ekle
                                </button>
                            </a>
                        </div>
                    </div>
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>

                            <th>Grup Adı</th>
                            <th>Kullanıcılar</th>
                            <th>Geri Alımlar</th>
                            <th>Sistem</th>
                            <th>Durum</th>

                            <th class="text-center">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($groups as $group)
                            <tr>

                                <td>{{$group['name']}} </td>
                                <td>
                                    @if($group['users']==1)
                                        <i class="fa fa-check"></i>
                                    @else
                                        <i class="fa fa-close"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($group['buybacks']==1)
                                        <i class="fa fa-check"></i>
                                    @else
                                        <i class="fa fa-close"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($group['system']==1)
                                        <i class="fa fa-check"></i>
                                    @else
                                        <i class="fa fa-close"></i>
                                    @endif
                                </td>

                                <td>


                                    @if($group['status']==1)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Pasif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="list-icons">
                                        <a href="{{route("users.group-update",["id"=>$group['id']])}}"
                                           class="btn btn-primary">
                                            <i class="fa fa-pencil"></i> Güncelle</a>

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
