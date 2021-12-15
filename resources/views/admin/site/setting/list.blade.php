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
                            <a href="{{route("site.create-setting")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="fa fa-plus-circle"></i></b> Yeni Ayar Ekle
                                </button>
                            </a>
                        </div>
                    </div>
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Ayar Adı</th>
                            <th>Değeri</th>
                            <th>Kodu</th>
                            <th class="text-center">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($settings as $setting)
                            <tr>
                                <td width="20%">
                                   {{$setting['setting_name']}}

                                </td>
                                <td>
                                    {{$setting['value']}}
                                </td>
                                <td>  {{$setting['code']}}</td>



                                <td class="text-center">
                                    <div class="list-icons">
                                        <a href="{{route('site.update-setting',$setting['id'])}}"
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
