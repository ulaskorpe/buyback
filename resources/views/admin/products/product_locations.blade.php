<div class="content">

    <div class="x_panel">
        <div class="x_title text-center">

            <div class="text-center">


                <h2>{{$product->brand()->first()->BrandName}} &gt;{{$product->model()->first()->Modelname}} &gt; {{$product['title']}}</h2>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form id="product-locate" action="#" method="post"
                  enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="product_micro_id" name="product_micro_id" value="{{$product['micro_id']}}">
            <div class="tab-content my-5" id="myTabContent">
            <div class="row">
                <div class="col-3">
                    <input type="hidden" id="area_list" name="area_list" value="{{$areas_}}">
                    <h5>Alanlar</h5>
                    <table>
               @foreach($areas as $area)
                   <tr><td>
                        <label class="">
                            <div class="icheckbox_flat-green" style="position: relative;">
                                <input type="checkbox" id="a{{$area['id']}}"
                                       name="a{{$area['id']}}"
                                       @if(in_array($area['id'],$area_array)) checked
                                       @endif class="flat"
                                       style="position: absolute; opacity: 0;">

                                <ins class="iCheck-helper"
                                     onclick="eklecikar('a{{$area['id']}}','area_list')"
                                     style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">

                                </ins>

                            </div>  {{$area['title']}} <b>{{$area['code']}}</b> <br>
                        </label>
                       </td></tr>
                   @endforeach
                    </table>
                </div>
                <div class="col-3">
                    <input type="hidden" id="slider_list" name="slider_list" value="{{$sliders_}}">
                    <h5>Sliderlar</h5>
                    <table>
                        @foreach($sliders as $slider)
                            <tr><td>
                                    <label class="">
                                        <div class="icheckbox_flat-green" style="position: relative;">
                                            <input type="checkbox" id="s{{$slider['id']}}"
                                                   name="s{{$slider['id']}}"
                                                   @if(in_array($slider['id'],$slider_array)) checked
                                                   @endif class="flat"
                                                   style="position: absolute; opacity: 0;">

                                            <ins class="iCheck-helper"
                                                 onclick="eklecikar('s{{$slider['id']}}','slider_list')"
                                                 style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">

                                            </ins>

                                        </div>  {{$slider['title']}}   <br>
                                    </label>
                                </td></tr>
                        @endforeach
                    </table>
                </div>
                <div class="col-3  text-center my-5">
                    <button type="submit" class="btn btn-primary font-weight-bold rounded-round">Konumlandır
                        GÜNCELLE
                    </button>

            </div>

            </div>
            </div>
            </form>
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