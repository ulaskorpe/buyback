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
                        <a href="{{route("model.model-add")}}"  class="btn btn-primary">Yeni Model Ekle</a>
                    </div>
                </div>
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Marka</th>
                        <th>Model Adı</th>
                        <th>Hafıza</th>
                        <th>Resim</th>
                        <th>Kategori</th>
                        <th>Durum</th>
                        <th class="text-center">İşlemler</th>
                        <th class="text-center">Atama</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($models as $model)
                        <tr>

                            <td>{{$model->brand()->first()->BrandName}}</td>
                            <td>{{$model['Modelname']}}</td>
                            <td>{{$model->memory()->first()->memory_value}}GB</td>
                            <td>

                                @if(!empty($model['Imagethumb']))
                                    <img src="{{url($model['Imagethumb'])}}">
                                @endif
                            </td>
                            <td>{{$model->category()->first()->category_name}}</td>
                            <td>  @if($model['Status']==1)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Pasif</span>
                                @endif</td>
                            <td class="text-center">
                                <div class="list-icons">


                                    <a href="{{route("model.model-update",["id"=>$model['id']])}}"
                                       class="btn btn-primary"><i class="fa fa-pencil"></i> Güncelle</a>
                                    <!--
                                    <a href="" class="list-icons-item text-violet-800"><i class="icon-eye"></i></a>
                                    -->
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="list-icons ">
                                    <div class="dropdown">
                                        <a href="{{route("model.model-questions",["model_id"=>$model['id']])}}"
                                           class="btn btn-secondary"> SORULAR ve YANITLARI <i class="fa fa-question-circle"></i></a>

                                    </div>
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
    </script>

@endsection
