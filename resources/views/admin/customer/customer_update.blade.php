@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-bs/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css')}}" rel="stylesheet">

@endsection
@section('main')
    <button type="button" style="display: none"  data-toggle="modal" data-target=".bs-example-modal-lg" id="modal_btn"></button>
    <div class="content">
        <button type="button" style="display: none"  data-toggle="modal" data-target=".bs-example-modal-lg" id="modal_btn"></button>
        <div class="x_panel">
            <div class="x_title text-center">

                <div class="text-center">
                    <a href="{{route("customer.customer-list")}}">
                        <button type="button"
                                class="btn btn-primary">
                            <b><i class="icon-ticket"></i></b> Müşteri Listesine Git
                        </button>
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                    @if($selected==0)
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#profile" role="tab"
                               aria-controls="home" aria-selected="true">Müşteri Bilgisi</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="home-tab" data-toggle="tab" href="#profile" role="tab"
                               aria-controls="home" aria-selected="false">Müşteri Bilgisi</a>
                        </li>
                    @endif

                    @if($selected==1)
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#addresses" role="tab"
                               aria-controls="profile" aria-selected="true">Adresleri</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#addresses" role="tab"
                               aria-controls="profile" aria-selected="false">Adresleri</a>
                        </li>
                    @endif

                        @if($selected==2)
                            <li class="nav-item">
                                <a class="nav-link active" id="contact-tab" data-toggle="tab" href="#order_cart" role="tab"
                                   aria-controls="contact" aria-selected="true">Sepeti ve Sipariş Geçmişi  </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" id="variant-tab" data-toggle="tab" href="#order_cart" role="tab"
                                   aria-controls="contact" aria-selected="false">Sepeti ve Sipariş Geçmişi</a>
                            </li>
                        @endif




                    @if($selected==3)
                        <li class="nav-item">
                            <a class="nav-link active" id="stock-tab" data-toggle="tab" href="#messages" role="tab"
                               aria-controls="contact" aria-selected="true">Mesajları</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="stock-tab" data-toggle="tab" href="#messages" role="tab"
                               aria-controls="contact" aria-selected="false">Mesajları</a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade  @if($selected==0) active show @endif" id="profile" role="tabpanel"
                         aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-12 mx-5">
                                <form id="update-customer" action="#" method="post"
                                      enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <input type="hidden" id="id" name="id" value="{{$customer['id']}}">
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Müşteri ID :</label>
                                        <div class="col-lg-8">
                                             {{$customer['customer_id']}}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Adı :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="name" id="name"
                                                   value="{{$customer['name']}}" data-popup="tooltip" data-trigger="focus"
                                                   title="" placeholder="">
                                            <span id="name_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Soyadı :</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="surname" id="surname"
                                                   value="{{$customer['surname']}}" data-popup="tooltip" data-trigger="focus"
                                                   title="" placeholder="">
                                            <span id="surname_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Cinsiyet :</label>
                                        <div class="col-lg-2">
                                            <select name="gender" id="gender" class="form-control">
                                                <option value="0" @if($customer['gender']==0) selected @endif>Belirtilmedi</option>
                                                <option value="1" @if($customer['gender']==1) selected @endif>Erkek</option>
                                                <option value="2" @if($customer['gender']==2) selected @endif>Kadın</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Resim</label>
                                        <div class="col-lg-8">
                                            <input type="file" name="avatar" id="avatar" class="form-control h-auto"
                                                   data-popup="tooltip"
                                                   title=""
                                                   onchange="showImage('avatar','target','avatar_img')"
                                                   placeholder="">
                                            <span id="avatar_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold"></label>
                                        <div class="col-lg-8">

                                            <img id="target" style="display: none;">
                                            @if(!empty($customer['avatar']))
                                                <img id="avatar_img" src="{{url($customer['avatar'])}}">
                                            @endif

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold"></label>
                                        <div class="col-lg-8">

                                            <img id="target" style="display: none;">
                                            <img id="avatar_img" style="display: none;">

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">E-Posta</label>
                                        <div class="col-lg-8">
                                            <input type="text" name="email" id="email" class="form-control h-auto" value="{{$customer['email']}}">
                                            <span class="form-text text-muted" id="email_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Durum</label>
                                        <div class="col-lg-3">
                                            <select name="status" id="status" class="form-control">
                                                <option value="0" @if($customer['status']==0) selected @endif>Giriş</option>
                                                <option value="1" @if($customer['status']==1) selected @endif>Aktif</option>
                                                <option value="2" @if($customer['status']==2) selected @endif>İnaktif</option>
                                                <option value="3" @if($customer['status']==3) selected @endif>Engelli</option>

                                            </select>

                                        </div>
                                    </div>


                                    <!-- /touchspin spinners -->
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary font-weight-bold rounded-round">MÜŞTERİ GÜNCELLE
                                            <i class="icon-paperplane ml-2"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-12 mx-5">
                            <form id="update-password" action="#" method="post"
                                  enctype="multipart/form-data">
                                {{csrf_field()}}
                                <input type="hidden" id="id" name="id" value="{{$customer['id']}}">
                                <div class="x-panel-header"><h2>Şifre Güncelle</h2></div>
                                <div class="form-group row">
                                    <label class="col-2 col-form-label font-weight-semibold">Şifre :</label>
                                    <div class="col-2">
                                        <input type="text" id="pw" name="pw" class="form-control" value="" disabled>
                                        <input type="hidden" class="form-control" name="password" id="password"
                                               value="" data-popup="tooltip" data-trigger="focus"
                                               title="Adı Soyadı" placeholder="Adı Soyadı">
                                        <div id="password_error"></div>
                                    </div>
                                    <div class="col-2"><input  type="button" class="btn btn-success" value="Şifre Üret" onclick="generatePw()"></div>

                                    <div class="col-2">

                                        <button type="submit" class="btn btn-primary font-weight-bold rounded-round">ŞİFRE GÜNCELLE
                                             </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div>
                    <div class="tab-pane fade @if($selected==1) active show @endif" id="addresses" role="tabpanel"
                         aria-labelledby="profile-tab">

                        <div class="row">
                            <div class="col-12">
                                <table id="datatable-responsive-1" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
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
                                    @foreach($addresses as $address)
                                        <tr>
                                            <td>{{$address['title']}}</td>
                                            <td>{{$address['name_surname']}}</td>
                                            <td>{{$address['address']}}</td>
                                            <td>
                                                {{$address->city()->first()->name}}
                                                @if($address['town_id']>0)
                                                    &gt;{{$address->town()->first()->name}}
                                                    @if($address['district_id']>0)<br>
                                                    &gt;{{$address->district()->first()->name}}
                                                    @if($address['neighborhood_id']>0)
                                                        &gt;{{$address->neighborhood()->first()->name}}
                                                    @endif
                                                    @endif
                                                @endif

                                            </td>
                                            <td>
                                                {{$address['phone_number']}}
                                                @if(!empty($address['phone_number_2']))
                                                    <hr>
                                                    {{$address['phone_number_2']}}
                                                @endif

                                            </td>
                                            <td>

                                                <button class="btn btn-primary"  onclick="update_address({{$address['id']}})">GÜNCELLE</button>

                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                    <div class="tab-pane fade @if($selected==2) active show @endif" id="order_cart" role="tabpanel"
                         aria-labelledby="variant-tab">

                        <div class="row">
                            <div class="col-12">
                                <p class="h2">Sepetindeki Ürünler</p>
                                <table id="datatable-responsive-2" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Ürün</th>
                                        <th>Durum</th>
                                        <th>Sipariş</th>

                                        <th class="text-center">İşlemler</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($cart_items as $cart_item)
                                        <tr>
                                            <td>
                                                {{$cart_item->product()->first()->title}}<br>
                                                {{$cart_item->product()->first()->brand()->first()->BrandName}}
                                                &gt;
                                                {{$cart_item->product()->first()->model()->first()->Modelname}}
                                                @if($cart_item['memory_id']>0)
                                                    <br>
                                                    {{$cart_item->memory()->first()->memory_value}} GB
                                                @endif
                                                @if($cart_item['color_id']>0)
                                                    <br>
                                                    {{$cart_item->color()->first()->color_name}}
                                                @endif
                                            </td>
                                            <td>{{$status_array[$cart_item['status']]}}</td>
                                            <td>
                                                @if($cart_item['order_id']>0)
                                                    {{$cart_item['order_id']}}

                                                @endif
                                            </td>
                                            <td>

                                                <button class="btn btn-primary"  onclick="update_address({{$cart_item['id']}})">GÜNCELLE</button>

                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>



                            </div>
                        </div>


                        <div class="row">
                            <div class="col-12">
                                <p class="h2">Alışveriş Geçmişi</p>
                                <table id="datatable-responsive-2" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Ürün</th>
                                        <th>Durum</th>
                                        <th>Sipariş</th>

                                        <th class="text-center">İşlemler</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($cart_items as $cart_item)
                                        <tr>
                                            <td>
                                                {{$cart_item->product()->first()->title}}<br>
                                                {{$cart_item->product()->first()->brand()->first()->BrandName}}
                                                &gt;
                                                {{$cart_item->product()->first()->model()->first()->Modelname}}
                                                @if($cart_item['memory_id']>0)
                                                    <br>
                                                    {{$cart_item->memory()->first()->memory_value}} GB
                                                    @endif
                                                @if($cart_item['color_id']>0)
                                                    <br>
                                                    {{$cart_item->color()->first()->color_name}}
                                                @endif
                                            </td>
                                            <td>{{$status_array[$cart_item['status']]}}</td>
                                            <td>
                                                @if($cart_item['order_id']>0)
                                                {{$cart_item['order_id']}}

                                                    @endif
                                            </td>
                                            <td>

                                                <button class="btn btn-primary"  onclick="update_address({{$cart_item['id']}})">GÜNCELLE</button>

                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>



                            </div>
                        </div>






                    </div>
                    <div class="tab-pane fade @if($selected==3) active show @endif" id="messages" role="tabpanel"
                         aria-labelledby="stock-tab">
                        <div class="row">
                            <div class="col-12">

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
@section('scripts')
    <script src="{{url("js/save.js")}}"></script>
    <script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
    <script src="{{url('vendors/iCheck/icheck.min.js')}}"></script>
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

         //   brandSelect();
       //     init_DataTables();
            $('#datatable-responsive-1').DataTable();
            $('#datatable-responsive-2').DataTable();

            if ($("input.flat")[0]) {
                $(document).ready(function () {
                    $('input.flat').iCheck({
                        checkboxClass: 'icheckbox_flat-green',
                        radioClass: 'iradio_flat-green'
                    });
                });
            }
        });
        function update_address(address_id){
            $('#modal_btn').click();
            $.get("{{url('admin/customers/address-update')}}/{{$customer['id']}}/"+address_id , function (data) {
                $('#lg-modal-title').html('Adres Güncelle');
                $('#lg-modal-body').html(data);



            });
        }
        function generatePw(){
            $.get("{{url('/admin/users/generate-pw')}}/" , function (data) {

                $('#pw').val(data);
                $('#password').val(data);
                $('#password_error').html('');
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
        $('#update-customer').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;
            if ($('#name').val() == '') {
                $('#name_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#name_error').html('');
            }
            if ($('#surname').val() == '') {
                $('#surname_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#surname_error').html('');
            }

            if ($('#email').val() == '') {
                $('#email_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {

                $.get("{{url('/admin/customers/check-email')}}/"+$('#email').val()+"/{{$customer['id']}}" , function (data) {

                    if(data=='ok'){
                        $('#email_error').html('');

                        if (error) {
                            return false;
                        }
                        save(formData, '{{route('customer.customer-update-post')}}', '', 'btn-1', '');

                    }else{
                        error = true;
                        $('#email').val('');
                        $('#email_error').html('<span style="color: red">'+data+'</span>');
                    }


                });


            }





        });

        $('#update-password').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            if ($('#password').val() == '') {
                $('#password_error').html('<span style="color: red">Lütfen şifre üretiniz</span>');
                error = true;
            } else {
                swal("Kullanıcının Şifresi Değişecek, Emin misiniz?", {
                    buttons: ["İptal", "Evet"],
                    dangerMode: true,
                }).then((value) => {
                    if (value) {

                        save(formData, '{{route('customer.customer-update-pw')}}', '', '', '');

                    }
                })
            }



        });
    </script>
@endsection
