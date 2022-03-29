<div class="content">

    <div class="x_panel">
        <div class="x_title text-center">

            <div class="text-center">
{{ route('products.product-update',[3,4])}}

                <h2>{{$product->brand()->first()->BrandName}} &gt;{{$product->model()->first()->Modelname}} &gt; {{$product['title']}}</h2>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <div class="tab-content" id="myTabContent">



                <form id="add-location" action="{{route('site.add-product-location-post')}}" method="post"
                      enctype="multipart/form-data">
                    {{csrf_field()}}

                    <input type="hidden" id="product_page" name="product_page" value="{{$product['id']}}">
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