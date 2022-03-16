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

                    </div>
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Adı Soyadı</th>
                            <th>Beklenti</th>
                            <th>Departman</th>
                            <th>CV Dosyası</th>

                            <th class="text-center">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($hrs as $hr)
                            <tr>
                                <td>
                                        {{$hr['name']}}  {{$hr['surname']}}

                                </td>
                                <td width="30%">{{$hr['expectation']}}</td>



                                <td>{{$hr['department']}}</td>
                                <td>
                                    <a href="{{url($hr['cv_file'])}}" target="_blank"><i class="fa fa-file"></i></a>
                                </td>
                                <td class="text-center">
                                    <div class="list-icons">

                                        <a href="#" onclick="deleteHr({{$hr['id']}})"
                                           class="btn btn-danger"><i class="fa fa-close"></i> </a>
                                        <!--
                                        <a href="" class="list-icons-item text-violet-800"><i class="icon-eye"></i></a>
                                        -->
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


        function deleteHr(hr_id) {
            swal("Başvuru silinecek, Emin misiniz?", {
                buttons: ["İptal", "Evet"],
                dangerMode: true,
            }).then((value) => {
                if (value) {

                    $.get("{{url('admin/site/hr/delete')}}/" + hr_id, function (data) {
                        swal(data + "");
                        setTimeout(function () {
                            location.reload();
                        }, 1500)

                        //   console.log(user_id+":"+follower_id);


                    });


                }
            })
        }
    </script>

@endsection
