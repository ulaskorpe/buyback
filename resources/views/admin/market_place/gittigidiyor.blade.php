@extends('admin.main_layout')
@section('css_')

@endsection
@section('main')

    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="{{route("memory.memoryadd")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-usb-stick"></i></b> Yeni Hafıza Ekle
                                </button>
                            </a>
                        </div>
                    </div>
                    <table class="table datatable-button-html5-basic">
                        <thead>
                        <tr>

                            <th>Hafıza Boyutu</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Durum</th>
                            <th class="text-center">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>

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




    <script>
        $(document).ready(function () {
            init_DataTables();
        });
    </script>

@endsection
