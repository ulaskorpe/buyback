@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
@endsection
@section('main')
    <div class="x_panel">
        <div class="x_title">
            <h2><i class="fa fa-bars"></i> {{ \Carbon\Carbon::parse($buyback['created_at'])->format('d.m.Y H:i')}} <small>Tarihli Geri Alım Talebi</small></h2>

            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Müsteri Bilgisi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Cihaz Durumu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">İşlemler</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form id="update-buyback" action="#" method="post"
                          enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="id" id="id" value="{{$buyback['id']}}">
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Marka / Model :</label>
                            <div class="col-lg-8">
                                {{$buyback->model()->first()->Modelname}}
                                @if($buyback['color_id'])
                                    ( {{$buyback->color()->first()->color_name}} )
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Renk :</label>
                            <div class="col-lg-8">
                                <select name="color_id" id="color_id" class="form-control">
                                    <option value="0">Seçiniz</option>
                                    @foreach($colors as $color)
                                        <option value="{{$color['id']}}" @if($buyback['color_id']==$color['id']) selected @endif>
                                            {{$color['color_name']}}</option>
                                    @endforeach
                                </select>


                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold"> Kullanıcı Adı :</label>
                            <div class="col-lg-8" style="font-size: 25px">

                                <a href="#" onclick="userDetail({{$buyback['buyback_user_id']}})"
                                   class="btn btn-secondary">{{$buyback->buybackuser()->first()->name}}
                                    {{$buyback->buybackuser()->first()->surname}}
                                </a>

                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Önerilen Tutar:</label>
                            <div class="col-lg-8">

                                <input type="number" class="form-control has-feedback-right" name="offer_price" value="{{$buyback['offer_price']}}" id="offer_price"
                                       maxlength="11">
                                <span class="form-control-feedback right" aria-hidden="true">TL</span>


                                <span class="form-text text-muted" id="offer_price_error"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label font-weight-semibold">Durum :</label>
                            <div class="col-lg-3">
                                <select name="status" id="status" class="form-control">
                                    @foreach($types as $type)
                                        <option value="{{$type}}" @if($buyback['status'] == $type) selected @endif>{{$bb_status_array[$type]}}</option>
                                    @endforeach
                                </select>
                                <span class="form-text text-muted" id="seotitle_error"></span>
                            </div>
                        </div>




                        <br>
                        <!-- /touchspin spinners -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary font-weight-bold rounded-round">GÜNCELLE
                                <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div id="questions_div"></div>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                </div>
            </div>
        </div>
    </div>


    <button type="button"  style="display: none" id="show-lg-modal" data-toggle="modal" data-target="#lg-modal">Large modal</button>
@endsection
@section('scripts')
    <script src="{{url("js/save.js")}}"></script>

    <script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
    <script>

        $(document).ready(function () {

            $(".js-switch")[0] && Array.prototype.slice.call(document.querySelectorAll(".js-switch")).forEach(function (e) {
                new Switchery(e, {color: "#26B99A"})
            })


            $.get("{{url('data/get-questions')}}/{{$buyback['model_id']}}/{{$answer_array}}/{{$buyback['id']}}", function (data) {
                $('#questions_div').html(data);

            });

        });

        function userDetail(user_id){
            $('#show-lg-modal').click();
            $.get( "{{url('admin/buyback/user-info')}}/"+user_id, function( data ) {
                $( "#lg-modal-title" ).html('Kullanıcı Detay');
                $( "#lg-modal-body" ).html( data );

            });
        }

        $('#update-buyback').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;
            if ($('#offer_price').val() == '') {
                $('#offer_price_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#offer_price_error').html('');
            }




            if (error) {
                return false;
            }

            //   swal("ok");
            save(formData, '{{route('buyback.buybackupdate-post')}}', '', 'btn-1', '');
        });
    </script>
@endsection
