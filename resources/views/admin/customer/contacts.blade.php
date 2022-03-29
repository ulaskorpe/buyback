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
                            <button type="button" style="display: none"  data-toggle="modal" data-target=".bs-example-modal-lg" id="modal_btn"></button>
                        </div>
                    </div>
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Adı Soyadı</th>
                            <th>Müşteri</th>
                            <th>Konu</th>
                            <th>Tarih</th>
                            <th class="text-center">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($contacts as $contact)
                            <tr>
                                <td>
                                    {{$contact['name']}}    {{$contact['surname']}}<br>
                                    {{$contact['email']}}<br>
                                    {{$contact['phone_number']}}
                                </td>
                                <td>
                                    @if($contact['customer_id']>0)
                                        {{$contact->customer()->first()->name}}
                                        {{$contact->customer()->first()->surname}}
                                        {{$contact->customer()->first()->email}}
                                    @endif
                                </td>
                                <td>
                                    {{$contact['subject']}}
                                </td>



                                <td>{{\Carbon\Carbon::parse($contact['created_at'])->format('d.m.Y H:i')}}</td>
                                <td>
                                    <button class="btn btn-primary"  onclick="viewContact({{$contact['id']}})">Görüntüle</button>
                                    <button class="btn btn-danger" onclick="deletecontact({{$contact['id']}})"><i class="fa fa-close"></i></button>

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


        function deletecontact(guid) {
            swal("İleti silinecek, Emin misiniz?", {
                buttons: ["İptal", "Evet"],
                dangerMode: true,
            }).then((value) => {
                if (value) {

                    $.get("{{url('admin/customers/delete-contact')}}/" + guid, function (data) {
                        swal("" + data);
                        setTimeout(function () {
                            location.reload()

                        }, 2000);

                        //   console.log(user_id+":"+follower_id);


                    });


                }
            })
        }
        function viewContact(id){
            $('#modal_btn').click();
            $.get("{{url('admin/customers/contact-detail')}}/"+id , function (data) {
                $('#lg-modal-title').html('İleti Detay');
                $('#lg-modal-body').html(data);



            });
        }

    </script>

@endsection
