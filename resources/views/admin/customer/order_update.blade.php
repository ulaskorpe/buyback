
@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">

@endsection
@section('main')
    <button type="button" style="display: none"  data-toggle="modal" data-target=".bs-example-modal-lg" id="modal_btn"></button>
    <div class="content">
        <button type="button" style="display: none"  data-toggle="modal" data-target=".bs-example-modal-lg" id="modal_btn"></button>
        <div class="x_panel">
            <div class="x_title text-center">

                <div class="text-center">
                    <a href="{{route("customer.orders")}}">
                        <button type="button"
                                class="btn btn-primary">
                            <b><i class="icon-ticket"></i></b> Sipariş Listesine Git
                        </button>
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                    @if($selected==0)
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                               aria-controls="home" aria-selected="true">Alışveriş Özeti</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab"
                               aria-controls="home" aria-selected="false">Alışveriş Özeti</a>
                        </li>
                    @endif

                    @if($selected==1)
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                               aria-controls="profile" aria-selected="true">Kargo Bilgisi</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                               aria-controls="profile" aria-selected="false">Kargo Bilgisi</a>
                        </li>
                    @endif

                    @if($selected==2)
                        <li class="nav-item">
                            <a class="nav-link active" id="contact-tab" data-toggle="tab" href="#variant" role="tab"
                               aria-controls="contact" aria-selected="true">İptal ve İade </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="variant-tab" data-toggle="tab" href="#variant" role="tab"
                               aria-controls="contact" aria-selected="false">İptal ve İade</a>
                        </li>
                    @endif

                    @if($selected==3)
                        <li class="nav-item">
                            <a class="nav-link active" id="stock-tab" data-toggle="tab" href="#stock" role="tab"
                               aria-controls="contact" aria-selected="true">Mesajları</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="stock-tab" data-toggle="tab" href="#stock" role="tab"
                               aria-controls="contact" aria-selected="false">Mesajları</a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade  @if($selected==0) active show @endif" id="home" role="tabpanel"
                         aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-3 my-5 mx-3">
                          <h5> <b>Sipariş Kodu :</b>{{$order['order_code']}}</h5>
                                @if(!empty($order['receipt']))
                                    <a href="{{url($order['receipt'])}}" target="_blank"><b>DEKONT</b></a>
                                    @endif
                            </div>

                            <div class="col-4 my-5 mx-3">
                                <b>Müşteri :</b><br>
                                {{$order->customer()->first()->name}}     {{$order->customer()->first()->surname}}<br>
                                {{$order->customer()->first()->email}}
                            </div>
                            @if(!empty($order->customer()->first()->avatar))
                                <div class="col-2">
                                    <img src="{{url($order->customer()->first()->avatar)}}" alt="">
                                </div>
                            @endif
                        </div>
                            <div class="row">
                            <div class="col-12">

                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th scope="col">Ürün</th>
                                            <th scope="col">Marka/Model</th>
                                            <th scope="col">Birim Fiyat</th>
                                            <th scope="col">Adet</th>
                                            <th scope="col">Tutar</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        @php
                                        $amount =0;
                                        @endphp
                                @foreach($order->cart_items()->get() as $item)
                                    <tr>
                                        <td>{{$item->product()->first()->title}}</td>
                                        <td>    {{$item->product()->first()->brand()->first()->BrandName}} /
                                            {{$item->product()->first()->model()->first()->Modelname}}
                                            <br>
                                            {{$item->memory()->first()->memory_value}}GB
                                            {{$item->color()->first()->color_name}}</td>
                                        <td>{{$item->product()->first()->price}} TL</td>
                                        <td>
                                           x{{$item['quantity']}}
                                        </td>
                                        <td>
                                            {{$item['price']}} TL
                                        </td>
                                        <td>
                                            <button class="btn btn-danger"><i class="fa fa-close"></i></button>
                                        </td>
                                    </tr>
                                            @php
                                                $amount +=$item['price'];
                                            @endphp
                                @endforeach
                                    </table>
                                    <!-- /touchspin spinners -->

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-10"> </div>
                            <div class="col-2">
                                @if($order['amount']==$amount)
                                <h3>{{$amount}} TL</h3>
                                @else
                                    <h4><del>{{$amount}}</del> TL</h4>
                                    <h3>{{$amount}} TL</h3>

                                @endif

                            </div>

                        </div>

                    </div>
                    <div class="tab-pane fade @if($selected==1) active show @endif" id="profile" role="tabpanel"
                         aria-labelledby="profile-tab">

                        <div class="row">
                            <div class="col-12">
                                <form id="order-detail" action="#" method="post"
                                      enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <input type="hidden" id="id" name="id" value="{{$order['id']}}">

                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Müşteri Adresi :</label>
                                        <div class="col-lg-3" id="customer_address">

                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Kargo Şirketi :</label>
                                        <div class="col-lg-3">
                                            <select name="cargo_company_id" id="cargo_company_id" class="form-control" onchange="selectCargo(this.value)">
                                                @foreach($cargo_companies as $cc)
                                                    <option value="{{$cc['id']}}" @if($cc['id']==$order['cargo_company_id']) selected @endif>
                                                        {{$cc['name']}}

                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>

                                    </div>
                                    <div class="row my-3"><div class="col-lg-2"></div> <div class="col-lg-5" id="cargo_info"></div></div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Şube :</label>
                                        <div class="col-lg-3">
                                            <select name="cargo_branch_id" id="cargo_branch_id" class="form-control"   onchange="selectbranch(this.value)" ></select>
                                            <span id="cargo_branch_id_error"></span>
                                        </div>

                                    </div>
                                    <div class="row my-3"><div class="col-lg-2"></div>   <div class="col-lg-5" id="branch_info"></div></div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Kargo Kodu :</label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" name="cargo_code" id="cargo_code"
                                                   value="{{$order['cargo_code']}}" data-popup="tooltip" data-trigger="focus"
                                                   title="" placeholder="">
                                            <span id="cargo_code_error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Çıkış Adresi :</label>
                                        <div class="col-lg-4">
                                            <select name="service_address_id" id="service_address_id"  class="form-control" onchange="serviceAddress(this.value)">
                                                <option value="0">Seçiniz</option>
                                                @foreach($service_addresses as $address)
                                                    <option value="{{$address['id']}}"  @if($order['service_address_id']==$address['id']) selected @endif>{{$address['title']}}</option>
                                                @endforeach
                                            </select>
                                            <span id="service_address_id_error"></span>
                                        </div>
                                    </div>

                                        <div class="row my-3"><div class="col-lg-2"></div>   <div class="col-lg-5" id="service_address"></div></div>

                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Ödeme Yöntemi:</label>
                                        <div class="col-lg-4">
                                            <select name="order_method" id="order_method"  class="form-control" onchange="showPayment(this.value)">
                                                <option value="0" @if($order['order_method']==0) selected @endif>Kredi Kartı Ödemesi</option>
                                                @foreach($bank_accounts as $bank)
                                                    <option value="{{$bank['id']}}" @if($order['order_method']==$bank['id']) selected @endif>
                                                        {{$bank['bank_name']}}</option>
                                                @endforeach
                                            </select>
                                            <span id="cargo_code_error"></span>
                                        </div>
                                    </div>

                                    <div class="row my-3"><div class="col-lg-2"></div>   <div class="col-lg-5" id="bank_detail"></div></div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label font-weight-semibold">Durum :</label>
                                        <div class="col-lg-4">
                                            <select name="status" id="status"  class="form-control" >
                                                <option value="0" @if($order['status']==0) selected @endif>Sepette</option>
                                                <option value="1" @if($order['status']==1) selected @endif>Ödendi</option>
                                                <option value="2" @if($order['status']==2) selected @endif>Gönderildi</option>
                                                <option value="3" @if($order['status']==3) selected @endif>İptal Edildi</option>
                                                <option value="4" @if($order['status']==4) selected @endif>Tamamlandı</option>

                                            </select>
                                            <span id="service_address_id_error"></span>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary font-weight-bold rounded-round">SİPARİŞ GÜNCELLE
                                            <i class="icon-paperplane ml-2"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="result"></div>


                    </div>
                    <div class="tab-pane fade @if($selected==2) active show @endif" id="variant" role="tabpanel"
                         aria-labelledby="variant-tab">
                        <div class="row">
                            <div class="col-12">

                            </div>
                        </div>






                    </div>
                    <div class="tab-pane fade @if($selected==3) active show @endif" id="stock" role="tabpanel"
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

    <script>

        $(document).ready(function () {
            selectCargo($('#cargo_company_id').val(),{{$order['cargo_company_branch_id']}});

            @if($order['customer_address_id']>0)
            selectAddress({{$order['customer_address_id']}});
            @endif

            @if($order['cargo_company_branch_id']>0)
            selectbranch({{$order['cargo_company_branch_id']}});
            @endif
            @if($order['service_address_id']>0)
            serviceAddress({{$order['service_address_id']}});
            @endif
            @if($order['order_method']>0)
            showPayment({{$order['order_method']}});
            @endif
        });
        function selectCargo(cargo_id,selected){
            $('#branch_info').html('');
            $.get("{{url('admin/customers/cargo-branches')}}/"+cargo_id+"/"+selected , function (data) {

                    $('#cargo_branch_id').html(data.branches);
                    $('#cargo_info').html(data.cargo);
              //  console.log(data);
            });
        }

        function selectbranch(branch_id){
            $('#cargo_branch_id_error').html('');
            $.get("{{url('admin/customers/branch-detail')}}/"+branch_id  , function (data) {
                $('#branch_info').html(data.branch);
            });
        }

        function selectAddress(address_id){
            $.get("{{url('admin/customers/customer-address-detail')}}/"+address_id  , function (data) {
                $('#customer_address').html(data.branch);
            });
        }



        function serviceAddress(address_id){
            $('#service_address_id_error').html('');
            $.get("{{url('admin/customers/service-address-detail')}}/"+address_id  , function (data) {
                $('#service_address').html(data.branch);
            });
        }

        function showPayment(bank_id){

            $.get("{{url('admin/customers/bank-account-detail')}}/"+bank_id  , function (data) {
                $('#bank_detail').html(data.bank);
            });
        }


        $('#order-detail').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            var error = false;
            if ($('#cargo_branch_id').val() == 0) {
                $('#cargo_branch_id_error').html('<span style="color: red">Lütfen şube seçiniz</span>');
                error = true;
            } else {
                $('#cargo_branch_id_error').html('');
            }
            if ($('#cargo_code').val() == "") {
                $('#cargo_code_error').html('<span style="color: red">Lütfen kargo kodu giriniz</span>');
                error = true;
            } else {
                $.get("{{url('admin/customers/cargo-code-check/')}}/"+$('#cargo_code').val()+"/{{$order['id']}}"  , function (data) {
                      if(data=="ok"){
                          $('#cargo_code_error').html('');


                          if ($('#service_address_id').val() == 0) {
                              $('#service_address_id_error').html('<span style="color: red">Lütfen çıkış adresi seçiniz</span>');
                              error = true;
                          } else {
                              $('#service_address_id_error').html('');
                          }


                          if (error) {
                              return false;
                          }


                         save(formData, '{{route('customer.order-update-post')}}', '', 'btn-1', '');

                      }else{
                          $('#cargo_code_error').html('<span style="color: red">Lütfen kargo kodunu kontrol ediniz</span>');

                      }
                });



            }



        });
    </script>
@endsection
