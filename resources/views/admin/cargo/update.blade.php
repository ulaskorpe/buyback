@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
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
                            <a href="{{route("cargo.cargo-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Şirket Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="update-cargo" action="#" method="post"
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" value="{{$cargo['id']}}" name="id" id="id">

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Şirket Adı</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name" id="name"
                                           value="{{$cargo['name']}}" data-popup="tooltip" data-trigger="focus"
                                           title=" " placeholder="Şirket Adı">
                                    <span id="name_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Yetkili</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="person" id="person"
                                           value="{{$cargo['person']}}" data-popup="tooltip" data-trigger="focus"
                                           title="" placeholder="Yetkili Kişi">
                                    <span id="person_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Logo</label>
                                <div class="col-lg-8">
                                    <input type="file" name="logo" id="logo" class="form-control h-auto"
                                           data-popup="tooltip"
                                           title=""
                                           onchange="showImage('logo','target','avatar_img')"
                                           placeholder="">
                                    <span id="logo_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold"></label>
                                <div class="col-lg-8">

                                    <img id="target" style="display: none;">

                                    @if(!empty($cargo['logo']))
                                        <img id="avatar_img" src="{{url($cargo['logo'])}}">
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">E-Posta</label>
                                <div class="col-lg-8">
                                    <input type="text" name="email" id="email" value="{{$cargo['email']}}" class="form-control h-auto">
                                    <span class="form-text text-muted" id="email_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Telefon</label>
                                <div class="col-lg-8">
                                    <input type="text" name="phone_number" value="{{$cargo['phone_number']}}" id="phone_number" class="form-control h-auto">
                                    <span class="form-text text-muted" id="phone_number_error"></span>
                                </div>
                            </div>



                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">ŞİRKET GÜNCELLE
                                    <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <button type="button" style="display: none"  data-toggle="modal" data-target=".bs-example-modal-lg" id="modal_btn"></button>
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">

                                <button type="button"
                                        class="btn btn-primary" onclick="add_branch()">
                                    <b><i class="icon-ticket"></i></b> Yeni Şube Ekle
                                </button>

                        </div>
                        <br><br>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Başlık</th>
                                    <th>Kişi</th>
                                    <th>Adres</th>
                                    <th>Konum</th>
                                    <th>Telefon</th>
                                    <th class="text-center">İşlemler</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cargo->branches()->get() as $branch)
                                    <tr>
                                        <td>{{$branch['title']}}</td>
                                        <td>{{$branch['person']}}</td>
                                        <td>{{$branch['address']}}</td>
                                        <td>
                                            {{$branch->city()->first()->name}}
                                            @if($branch['town_id']>0)
                                                &gt;{{$branch->town()->first()->name}}
                                                @if($branch['district_id']>0)<br>
                                                &gt;{{$branch->district()->first()->name}}
                                                @if($branch['neighborhood_id']>0)
                                                    &gt;{{$branch->neighborhood()->first()->name}}
                                                @endif
                                                @endif
                                            @endif

                                        </td>
                                        <td>
                                            {{$branch['phone_number']}}
                                            @if(!empty($branch['phone_number_2']))
                                                <hr>
                                                {{$branch['phone_number_2']}}
                                            @endif

                                        </td>
                                        <td>

                                            <button class="btn btn-primary"  onclick="update_branch({{$branch['id']}})">GÜNCELLE</button>

                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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

        function add_branch(){
            $('#modal_btn').click();
            $.get("{{route('cargo.cargo-add-branch',$cargo['id'])}}" , function (data) {
                $('#lg-modal-title').html('Adres Ekle');
                $('#lg-modal-body').html(data);



            });
        }


        function update_branch(branch_id){
            $('#modal_btn').click();
            $.get("{{url('admin/cargo/update-branch')}}/"+branch_id , function (data) {
                $('#lg-modal-title').html('Adres Güncelle');
                $('#lg-modal-body').html(data);



            });
        }
        function showImage(img, t, hide_it, w = 0, h = 0) {
            $('#' + hide_it).hide();
            $('#' + img).show();
            var src = document.getElementById(img);
            var target = document.getElementById(t);
            var val = $('#' + img).val();
            var arr = val.split('\\');
            $('#' + img + '_error').html("");
            $('#' + t).show();


            $.get("{{url('/check_image')}}/" + arr[arr.length - 1], function (data) {
                //alert(data);
                if (data === 'ok') {
                    $('#' + img + '_error').html("");
                    $('#' + t).show();
                    var fr = new FileReader();
                    fr.onload = function (e) {

                        var image = new Image();
                        image.src = e.target.result;

                        image.onload = function (e) {

                            if (w > 0 || h > 0) {
                                var error = false;
                                var txt = "";
                                if (w != this.width) {
                                    txt += "Dosya genişliği " + w + "";
                                    error = true;
                                }

                                if (h != this.height) {
                                    txt += "Dosya yüksekliği " + h + "";
                                    error = true;
                                }
                                if (error) {
                                    $('#' + img + '_error').html('<span style="color: red">' + txt + ' olmalıdır </span>');
                                    //swal("admin.image_wrong_format");
                                    $('#' + img).val('');
                                    $('#' + t).hide();
                                    $('#' + hide_it).hide();
                                    return false;
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
                    $('#' + img + '_error').html('<span style="color: red">Yanlış dosya biçimi</span>');
                    //swal("admin.image_wrong_format");
                    $('#' + img).val('');
                    $('#' + t).hide();
                    $('#' + hide_it).hide();


                }
            });


        }

        function ValidateEmail()
        {
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test($('#email').val()))
            {
                return (true)
            }

            return (false)
        }

        $('#update-cargo').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;
            if ($('#name').val() == '') {
                $('#name_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#name_error').html('');
            }

            if ($('#email').val() != '') {
                if (!ValidateEmail()) {
                    $('#email').val('');
                    $('#email_error').html('<span style="color: red">Geçersiz Eposta</span>');
                    error = true;
                } else {
                    $('#email_error').html('');
                }
            } else {
                $('#email_error').html('');
            }


            if (error) {
                return false;
            }

            //   swal("ok");
            save(formData, '{{route('cargo.cargo-update-post')}}', '', 'btn-1', '');
        });
    </script>
@endsection
