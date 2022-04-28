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
                            <a href="{{route("customer.coupon-list")}}">
                                <button type="button"
                                        class="btn btn-primary">
                                    <b><i class="icon-ticket"></i></b> Kupon Listesine Git
                                </button>
                            </a>
                        </div>
                        <br><br>

                        <form id="create-coupon" action="{{route('customer.couponAdd-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="form-group row">

                                <div class="col-lg-2">
                                    <label class="col-form-label font-weight-bold">Tutar</label>
                                    <input type="number" class="form-control" name="amount" id="amount"
                                           value="0" data-popup="tooltip" data-trigger="focus"
                                           >
                                    <div id="amount_error"></div>
                                </div>

                                <div class="col-lg-2">
                                    <label class="col-form-label font-weight-bold">Yüzde</label>
                                    <select name="percentage" id="percentage" class="form-control">

                                        @for($i=0;$i<101;$i++)
                                            <option value="{{$i}}">%{{$i}}</option>
                                            @endfor
                                    </select>
                                    <div id="percentage_error"></div>
                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-lg-2">
                                    <label class="col-form-label font-weight-bold">Kullanım Sayısı</label>
                                        <input type="number" class="form-control" name="usage" id="usage"
                                               value="0" data-popup="tooltip" data-trigger="focus"
                                        >
                                        <div id="usage_error"></div>


                                </div>


                                <div class="col-lg-2">
                                    <label class="col-form-label font-weight-bold">Son Kullanım Tarihi</label>
                                    <input type="date" class="form-control" name="expires_at" id="expires_at"
                                           value="{{\Carbon\Carbon::parse(date('d.m.Y'))->addMonth()->format('Y-m-d')}}"  >
                                    <div id="expires_at_error"></div>


                                </div>
                                <div class="col-lg-2">
                                    <label class="col-form-label font-weight-bold">Üretilecek Kupon Adedi</label>
                                    <input type="number" class="form-control" name="last" id="last"
                                           value="1" data-popup="tooltip" data-trigger="focus"
                                    >


                                </div>

                            </div>



                            <div class="form-group row">
                                <label class="col-lg-1 col-form-label font-weight-bold">Durum</label>
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
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">KUPON EKLE
                                    <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
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
    <script src="{{url('vendors/moment/min/moment.min.js')}}"></script>


    <script>



        $('#create-coupon').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;
            if ($('#percentage').val() == 0 && $('#amount').val()==0) {
                $('#percentage_error').html('<span style="color: red">Yüzde ya da tutar belirtiniz</span>');
                $('#amount_error').html('<span style="color: red">Yüzde ya da tutar belirtiniz</span>');
                error = true;
            } else {
                $('#percentage_error').html('<span style="coupon: red"></span>');
                $('#amount_error').html('<span style="coupon: red"></span>');
            }

            if ($('#usage').val()<1) {
                $('#usage_error').html('<span style="color: red">En az 1 olmalı</span>');
                error = true;
            } else {
                $('#usage_error').html('');
            }

            if ( $('#expires_at').val().replace('-','').replace('-','')<'{{date('Ymd')}}') {
                $('#expires_at_error').html('<span style="color: red">SKT Gelecekte olmalı</span>');
                error = true;
            } else {
                $('#expires_at_error').html('');
            }

            if(error){
                return false;
            }

            //   swal("ok");
        save(formData, '{{route('customer.couponAdd-post')}}', '', '','');
        });
    </script>
@endsection


