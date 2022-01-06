@extends('admin.main_layout')
@section('css_')

@endsection
@section('main')
    <div class="content">

        <div class="x_panel">
            <div class="x_title text-center">

                <div class="text-center">


                    <h2>{{$product->brand()->first()->BrandName}} &gt;{{$product->model()->first()->Modelname}} &gt; {{$product['title']}}</h2>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <div class="tab-content" id="myTabContent">



                        <form id="add-location" action="{{route('site.add-product-location-post')}}" method="post"
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" id="product_id" name="product_id" value="{{$product['id']}}">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Konum :</label>
                                <div class="col-lg-3">
                                    <select name="location_id" id="location_id" class="form-control" onchange="get_location_order(this.value)">
                                        <option value="0">Seçiniz</option>
                                        @foreach($site_locations as $location)
                                            <option value="{{$location['id']}}">{{$location['name']}}</option>
                                        @endforeach
                                    </select>
                                    <span id="location_id_error"></span>
                                </div>
                                <label class="col-lg-1 col-form-label font-weight-semibold">Sıra</label>
                                <div class="col-lg-1">
                                    <select name="location_order" id="location_order" class="form-control" disabled></select>
                                </div>
                                <div class="col-lg-3">
                                    <button type="submit" class="btn btn-primary">KONUM EKLE
                                        <i class="icon-paperplane ml-2"></i></button>

                                </div>
                            </div>





                            <!-- /touchspin spinners -->

                        </form>

                        <div style="margin-top: 50px" id="locations_div">



                            @if($locations->count()>0)
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Konum</th>
                                                <th>Sıra</th>

                                                <th class="text-center">İşlemler</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($locations as $location)

                                                <tr>
                                                    <td>
                                                        {{$location->location()->first()->name}}
                                                    </td>
                                                    <td style="width: 100px">

                                                        <select name="order_{{$location['id']}}" id="order_{{$location['id']}}" onchange="changeLocationOrder({{$location['id']}},this.value)" class="form-control">
                                                            @for($i=1;$i<$count_locations[$location['location_id']]+1;$i++)
                                                                <option value="{{$i}}" @if($i==$location['order']) selected @endif>{{$i}}</option>
                                                            @endfor
                                                        </select>

                                                    </td>
                                                    <td>
                                                        <button class="btn btn-danger" onclick="deleteLocation({{$location['id']}})"><i class="fa fa-close"></i></button>
                                                    </td>
                                                </tr>

                                            @endforeach
                                            </tbody>
                                        </table>

                                    </div></div>
                            @endif


                        </div>

                </div>
            </div>
        </div>


    </div>
@endsection
@section('scripts')
    <script src="{{url("js/save.js")}}"></script>
    <script>


        $(document).ready(function () {

       //     divlocations();
        });


        function get_location_order(location_id) {
        //    console.log("{{url('admin/site/product/get-location-order')}}/{{$product['id']}}/"+location_id );

            $.get("{{url('admin/site/product/get-location-order')}}/{{$product['id']}}/"+location_id   , function (data) {
                if(data=='no'){
                    swal("Bu ürün ilgili alana konumlandırılmış");
                }else{
                    $('#location_order').prop('disabled',false);
                    $('#location_order').html(data);
                }

            });
        }


        function deleteLocation(location_id) {
            swal("Ürün konumdan silinecek, Emin misiniz?", {
                buttons: ["İptal", "Evet"],
                dangerMode: true,
            }).then((value) => {
                if (value) {

                    $.get("{{url('admin/site/product/delete-product-location')}}/" + location_id, function (data) {
                        swal(data + "");
                        setTimeout(function () {
                            window.open('{{url('admin/site/product/locate/'.$product['id'])}}', '_self')
                        }, 1500)

                        //   console.log(user_id+":"+follower_id);


                    });


                }
            })
        }



        function changeLocationOrder(location_id, new_order){
            $.get("{{url('admin/site/product/change-product-location-order')}}/"  + location_id+"/"+new_order, function (data) {
                swal(data + "");
                setTimeout(function () {
                    window.open('{{url('admin/site/product/locate/'.$product['id'])}}', '_self')
                }, 1500)

            });
        }



        $('#add-location').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;

            if ($('#location_id').val() == 0) {
                $('#location_id_error').html('<span style="color: red">Lütfen konum seçiniz</span>');
                error = true;
            } else {
                $('#location_id_error').html('');
            }


            if (error) {
                return false;
            } else {
                save(formData, '{{route('site.add-product-location-post')}}', '', '', '');
            }
        });
    </script>
@endsection
