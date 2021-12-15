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
                            <a href="{{route("site.menu-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Menü Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="update-menu" action="{{route('site.update-menu-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}

                            <input type="hidden" name="id" id="id" value="{{$menu['id']}}">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Başlık :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="title" id="title"
                                           value="{{$menu['title']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Başlık Yazısı">
                                    <span id="title_error"></span>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Link :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="link" id="link"
                                           value="{{$menu['link']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Açılacak Bağlantı">
                                    <span id="link_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Resim</label>
                                <div class="col-lg-8">
                                    <input type="file" name="image" id="image" class="form-control h-auto" data-popup="tooltip"
                                           title=""
                                           onchange="showImage('image','target','avatar_img')"
                                           placeholder="">
                                    <span id="image_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold"></label>
                                <div class="col-lg-8">
                                    <img id="target" style="display: none;">
                                    @if(!empty($menu['thumb']))
                                        <a href="{{url($menu['image'])}}" target="_blank"><img id="avatar_img"
                                                                                                 src="{{url($menu['thumb'])}}"></a>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Konum :</label>
                                <div class="col-lg-2">
                                    <select name="location" id="location" class="form-control" onchange="getOrder(1)">
                                        @foreach($locations as $location)
                                            <option value="{{$location}}" @if($menu['$location']==$location) selected @endif>{{$menu_locations[$location]}}</option>
                                        @endforeach
                                    </select>
                                    <span id="location_error"></span>
                                </div>
                                <div class="col-lg-9"></div>
                            </div>


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Sıra : </label>
                                <div class="col-lg-1">
                                    <select name="order" id="order" class="form-control" disabled></select>

                                </div>
                                <div class="col-lg-9"></div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" checked="true" name="status" id="status" value="13" data-switchery="true"
                                               style="display: none;">



                                    </label>
                                </div>
                            </div>
                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">MENÜ UNSURU GÜNCELLE
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
                            <div class="text-center">
                                <a href="#" onclick="createSubMenu()" class="btn btn-primary"> Yeni Alt Bağlantı Ekle</a>
                            </div>
                            <button type="button"  style="display: none" id="show-lg-modal" data-toggle="modal" data-target="#lg-modal">Large modal</button>
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>

                                    <th>Başlık</th>
                                    <th>URL</th>
                                    <th>Sıra</th>
                                    <th>Durum</th>
                                    <th class="text-center">İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($menu->sub_items()->get() as $submenu)
                                    <tr>

                                        <td>
                                            <b>{{$submenu['title']}}</b>
                                        </td>
                                        <td><a href="{{$submenu['link']}}" target="_blank">{{$submenu['link']}}</a> </td>
                                        <td>  {{$submenu['order']}}</td>
                                        <td>
                                            @if($submenu['status']==1)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Pasif</span>
                                            @endif
                                        </td>


                                        <td class="text-center">
                                            <div class="list-icons">

                                                <a href="#" onclick="updateSubMenu({{$submenu['id']}})"
                                                   class="btn btn-primary"><i class="fa fa-pencil"></i></a>

                                                <a href="#" onclick="deleteSubMenu({{$submenu['id']}})"
                                                   class="btn btn-danger"><i class="fa fa-close"></i></a>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                        <br><br>
                    </div>
                </div>
            </div>
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
        $(document).ready(function() {
            getOrder(1,{{$menu['order']}});
            init_DataTables();
        });
        //$menu['$location']
        function createSubMenu(){
            $('#show-lg-modal').click();
            $.get( "{{url('admin/site/menu/create-sub-menu-item/')}}/"+"{{$menu['id']}}", function( data ) {
                $( "#lg-modal-title" ).html('Yeni Alt Bağlantı Ekle');
                $( "#lg-modal-body" ).html( data );

            });
        }
        function updateSubMenu(id){
            $('#show-lg-modal').click();
            $.get( "{{url('admin/site/menu/update-sub-menu-item/')}}/"+id, function( data ) {
                $( "#lg-modal-title" ).html('Alt Bağlantı Güncelle');
                $( "#lg-modal-body" ).html( data );

            });
        }

        function deleteSubMenu(sub_id) {
            swal("Alt bağlantı silinecek, Emin misiniz?", {
                buttons: ["İptal", "Evet"],
                dangerMode: true,
            }).then((value) => {
                if (value) {

                    $.get("{{url('admin/site/menu/delete-sub-menu-item')}}/" + sub_id, function (data) {
                        swal("" + data);
                        setTimeout(function () {
                            window.open('{{route('site.update-menu',[$menu['id']])}}', '_self')

                        }, 2000);

                        //   console.log(user_id+":"+follower_id);


                    });


                }
            })
        }
        function getOrder(add,selected){
            var tail = "/"+add;

            if(selected>0){
                tail+="/"+selected;
            }

            $.get( "{{url('admin/site/menu/get-menu-count')}}/"+$('#location').val()+tail, function( data ) {
                $( "#order" ).prop('disabled',false);
                $( "#order" ).html( data );
                //alert( "Load was performed." );
            });
        }

        function showImage(img, t, hide_it,w=0,h=0) {
            $('#' + hide_it).hide();
            $('#' + img).show();
            var src = document.getElementById(img);
            var target = document.getElementById(t);
            var val = $('#' + img).val();
            var arr = val.split('\\');
            $('#'+img+'_error').html("");
            $('#'+t).show();


            $.get("{{url('/check_image')}}/" + arr[arr.length - 1], function (data) {
                //alert(data);
                if (data === 'ok') {
                    $('#'+img+'_error').html("");
                    $('#'+t).show();
                    var fr = new FileReader();
                    fr.onload = function (e) {

                        var image = new Image();
                        image.src = e.target.result;

                        image.onload = function (e) {

                            if(w>0 || h>0){
                                var error= false;
                                var txt = "";
                                if(w!=this.width){
                                    txt+="Dosya genişliği "+w+"px ";
                                    error=true;
                                }

                                if(h!=this.height){
                                    if(error) {
                                        txt+="ve ";
                                    }
                                    txt+="Dosya yüksekliği "+h+"px ";
                                    error=true;
                                }
                                if(error){
                                    $('#'+img+'_error').html('<span style="color: red">'+txt+' olmalıdır </span>');
                                    //swal("admin.image_wrong_format");
                                    $('#'+img).val('');
                                    $('#'+t ).hide();
                                    $('#'+hide_it ).hide();
                                    return  false;
                                }


                                // swal(this.width+"x"+this.height);
                                // $('#'+img+'_error').html("ölçü yanlş");
                                // $('#'+t).hide();
                                return false;
                            }
                            //  $('#imgresizepreview, #profilepicturepreview').attr('src', this.src);
                        };

                        target.src = this.result;
                    };
                    fr.readAsDataURL(src.files[0]);
                } else {
                    $('#'+img+'_error').html('<span style="color: red">Yanlış dosya biçimi</span>');
                    //swal("admin.image_wrong_format");
                    $('#'+img).val('');
                    $('#'+t ).hide();
                    $('#'+hide_it ).hide();


                }
            });


        }

        function validURL(str) {
            var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
                '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
            return !!pattern.test(str);
        }

        $('#update-menu').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;

            if ($('#title').val() == '') {
                $('#title_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#title_error').html('');
            }

            if ($('#location').val() == 0) {
                $('#location_error').html('<span style="color: red">Lütfen konum seçiniz</span>');
                error = true;
            } else {
                $('#location_error').html('');
            }

            if ($('#link').val() != '') {
                if(!validURL($('#link').val())){
                    $('#link_error').html('<span style="color: red">Geçersiz URL</span>');
                    $('#link').val('');
                    error = true;
                }else{
                    $('#link_error').html('');
                }
            }
            if(error){
                return false;
            }else{
                save(formData, '{{route('site.update-menu-post')}}', '', '','');
            }
        });
    </script>
@endsection
