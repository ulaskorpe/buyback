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
                            <a href="{{route("site.article-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Yazı Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="update-article" action="{{route('site.update-article-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" id="id" name="id" value="{{$article['id']}}">

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Başlık :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="title" id="title"
                                           value="{{$article['title']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Başlık Yazısı">
                                    <span id="title_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Açıklama :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="prologue" id="prologue"
                                           value="{{$article['prologue']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Açıklama Yazısı">
                                    <span id="prologue_error"></span>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" @if($article['status']==1) checked="true" @endif name="status" id="status" value="13" data-switchery="true"
                                               style="display: none;">



                                    </label>
                                </div>
                            </div>
                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">Yazı GÜNCELLE
                                    <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <a href="#" onclick="createPart()" class="btn btn-primary">Yazıya Bölüm Ekle</a>
                        </div>
                    </div>
                    <button type="button"  style="display: none" id="show-lg-modal" data-toggle="modal" data-target="#lg-modal">Large modal</button>
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>

                            <th>Başlık</th>
                            <th>Resim</th>
                            <th>Sıra</th>
                            <th>Durum</th>
                            <th class="text-center">İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($article->parts()->get() as $part)
                        <tr>
                            <td>{{$part['title']}}</td>
                            <td>
                                @if(!empty($part['thumb']))
                                    <img src="{{url($part['thumb'])}}">
                                @endif

                            </td>
                            <td>{{$part['count']}}</td>
                            <td>   @if($part['status']==1)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Pasif</span>
                                @endif</td>
                            <td>

                                <a href="#" onclick="updatePart({{$part['id']}})"
                                   class="btn btn-primary"><i class="fa fa-pencil"></i></a>

                                <a href="#" onclick="deletePart({{$part['id']}})"
                                   class="btn btn-danger"><i class="fa fa-close"></i></a>
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
    <script src="{{url("js/save.js")}}"></script>
    <script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
    <script>

        $(document).ready(function () {
            init_DataTables();
        });

        function createPart(){
            $('#show-lg-modal').click();
            $.get( "{{url('admin/site/articles/create-part/')}}/"+"{{$article['id']}}", function( data ) {
                $( "#lg-modal-title" ).html('Yeni Bölüm Ekle');
                $( "#lg-modal-body" ).html( data );

            });
        }

        function updatePart(part_id){
            $('#show-lg-modal').click();
            $.get( "{{url('admin/site/articles/update-part/')}}/"+part_id, function( data ) {
                $( "#lg-modal-title" ).html('Bölüm Güncelle');
                $( "#lg-modal-body" ).html( data );

            });
        }

        function deletePart(part_id) {
            swal("Paragraf yazıdan silinecek, Emin misiniz?", {
                buttons: ["İptal", "Evet"],
                dangerMode: true,
            }).then((value) => {
                if (value) {

                    $.get("{{url('admin/site/articles/delete-part')}}/" + part_id, function (data) {
                        swal("" + data);
                        setTimeout(function () {
                            window.open('{{route('site.update-article',[$article['id']])}}', '_self')

                        }, 2000);

                        //   console.log(user_id+":"+follower_id);


                    });


                }
            })
        }

        $('#update-article').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;

            if ($('#title').val() == '') {
                $('#title_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#title_error').html('');
            }


            if(error){
                return false;
            }else{
                save(formData, '{{route('site.update-article-post')}}', '', '','');
            }
        });
    </script>
@endsection
