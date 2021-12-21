@extends('admin.main_layout')
@section('css_')
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
    <link href="{{url('vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
@endsection
@section('main')
    <div class="content">

        <div class="x_panel">
            <div class="x_title text-center">

                <div class="text-center">
                    <a href="{{route("site.product-list")}}">
                        <button type="button"
                                class="btn btn-primary">
                            <b><i class="icon-ticket"></i></b> Ürün Listesine Git
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
                               aria-controls="home" aria-selected="true">Ürün Bilgisi</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab"
                               aria-controls="home" aria-selected="false">Ürün Bilgisi</a>
                        </li>
                    @endif

                    @if($selected==1)
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                               aria-controls="profile" aria-selected="true">Ürün Resimleri</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                               aria-controls="profile" aria-selected="false">Ürün Resimleri</a>
                        </li>
                    @endif

                    @if($selected==2)
                        <li class="nav-item">
                            <a class="nav-link active" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                               aria-controls="contact" aria-selected="true">Ürün Konumları  </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                               aria-controls="contact" aria-selected="false">Ürün Konumları </a>
                        </li>
                    @endif
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade  @if($selected==0) active show @endif" id="home" role="tabpanel"
                         aria-labelledby="home-tab">
                        <form id="update-product" action="{{route('site.create-product-post')}}" method="post"
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" id="id" name="id" value="{{$product['id']}}">

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Başlık :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="title" id="title"
                                           value="{{$product['title']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Başlık Yazısı">
                                    <span id="title_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Kategori :</label>
                                <div class="col-lg-4">
                                    <select name="category_id" id="category_id" class="form-control">

                                        @foreach($categories as $category)
                                            <option value="{{$category['id']}}"
                                                    @if($category['id']==$product['category_id']) selected @endif>{{$category['category_name']}}</option>
                                        @endforeach
                                    </select>
                                    <span id="category_id_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Marka :</label>
                                <div class="col-lg-4">
                                    <select name="brand_id" id="brand_id" class="form-control" onchange="brandSelect()">
                                        <option value="0">Seçiniz</option>
                                        @foreach($brands as $brand)
                                            <option value="{{$brand['id']}}"
                                                    @if($brand['id']==$product['brand_id']) selected @endif>{{$brand['BrandName']}}</option>
                                        @endforeach
                                    </select>
                                    <span id="brand_id_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Model :</label>
                                <div class="col-lg-4">
                                    <select name="model_id" id="model_id" class="form-control" disabled>
                                        <option value="0">Seçiniz</option>
                                    </select>
                                    <span id="model_id_error"></span>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Mevcut Renkler :</label>
                                <div class="col-lg-8">
                                    <input type="hidden" name="color_list" id="color_list" value="{{$c_value}}">
                                    <table width="100%">
                                        <tr>

                                            @foreach($colors as $color)
                                                <td style="width:100px ">
                                                    {{$color['color_name']}}<br>
                                                    <label class="">
                                                        <div class="icheckbox_flat-green" style="position: relative;">
                                                            <input type="checkbox" id="c{{$color['id']}}"
                                                                   name="c{{$color['id']}}"
                                                                   @if(in_array($color['id'],$product_colors)) checked
                                                                   @endif class="flat"
                                                                   style="position: absolute; opacity: 0;">

                                                            <ins class="iCheck-helper"
                                                                 onclick="eklecikar('c{{$color['id']}}','color_list')"
                                                                 style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">

                                                            </ins>
                                                        </div>
                                                    </label>
                                                    <div
                                                        style="width: 100%;height: 50px; background: {{$color['color_code']}}"></div>
                                                </td>
                                            @endforeach
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Açıklama :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="description" id="description"
                                           value="{{$product['description']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="description_error"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Fiyat :</label>
                                <div class="col-lg-3">
                                    <input type="number" class="form-control" name="price" id="price"
                                           value="{{$product['price']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="price_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Fiyat (eski) :</label>
                                <div class="col-lg-3">
                                    <input type="number" class="form-control" name="price_ex" id="price_ex"
                                           value="{{$product['price_ex']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="price_ex_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Stok Miktarı :</label>
                                <div class="col-lg-3">
                                    <input type="number" class="form-control" name="quantity" id="quantity"
                                           value="{{$product['quantity']}}" data-popup="tooltip" data-trigger="focus"
                                           placeholder="">
                                    <span id="quantity_error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" @if($product['status']==1) checked
                                               @endif name="status" id="status" value="13" data-switchery="true"
                                               style="display: none;">


                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Ürün Güncelleme Sayfasında
                                    kal</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" checked name="stay" id="stay"
                                               value="13" data-switchery="true"
                                               style="display: none;">


                                    </label>
                                </div>
                            </div>
                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">ÜRÜN
                                    GÜNCELLE
                                    <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade @if($selected==1) active show @endif" id="profile" role="tabpanel"
                         aria-labelledby="profile-tab">

                        <form id="add-image" action="{{route('site.add-product-image-post')}}" method="post"
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" id="product_id" name="product_id" value="{{$product['id']}}">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Resim</label>
                                <div class="col-lg-8">
                                    <input type="file" name="image" id="image" class="form-control h-auto"
                                           data-popup="tooltip"
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
                                    <img id="avatar_img" style="display: none;">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Sıra</label>
                                <div class="col-lg-1">
                                    <select name="order" id="order" class="form-control">
                                        @for($i=1;$i<$image_count;$i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" checked="true" name="status"
                                               id="status" value="13" data-switchery="true"
                                               style="display: none;">


                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">İlk Resim</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" checked="true" name="first" id="first"
                                               value="13" data-switchery="true"
                                               style="display: none;">


                                    </label>
                                </div>
                            </div>
                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">RESİM EKLE
                                    <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>

                        <div class="row" style="margin-top: 50px">

                            @foreach($product->images()->get() as $image)
                                <div class="col-md-2 text-center">
                                    <table width="100%">
                                        <tr>
                                            <td height="100"><img src="{{url($image['thumb'])}}" alt=""></td>
                                        </tr>
                                        <tr>
                                            <td><select name="order_{{$image['id']}}" id="order_{{$image['id']}}"
                                                        onchange="changeOrder(this.value,{{$image['id']}})"
                                                        class="form-control">
                                                    @for($i=1;$i<$image_count-1;$i++)
                                                        <option value="{{$i}}"
                                                                @if($image['order']==$i) selected @endif>{{$i}}</option>
                                                    @endfor

                                                </select></td>
                                        </tr>
                                        <tr>
                                            <td>

                                                <label class="">
                                                    <div class="iradio_flat-green checked" style="position: relative;">
                                                        <input type="radio" class="flat"   @if($image['first']==1)) checked
                                                               @endif name="first"
                                                               style="position: absolute; opacity: 0;">
                                                        <ins class="iCheck-helper" onclick="changeFirst({{$image['id']}})"
                                                             style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                                    </div>

                                                </label>


                                            </td>
                                        </tr>
                                        <tr><td>
                                                <button class="btn btn-danger" onclick="deleteImage({{$image['id']}})"><i class="fa fa-close"></i></button>
                                            </td></tr>
                                    </table>


                                </div>
                            @endforeach

                        </div>

                    </div>
                    <div class="tab-pane fade @if($selected==2) active show @endif" id="contact" role="tabpanel"
                         aria-labelledby="contact-tab">

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

                        <div style="margin-top: 50px" id="locations_div"></div>
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
        function eklecikar(kutu, inputAlan) {

            if (!$('#' + kutu).is(':checked')) {
                document.getElementById(inputAlan).value += "@" + kutu;
            } else {
                document.getElementById(inputAlan).value = document.getElementById(inputAlan).value.replace("@" + kutu, "");
            }
        }

        $(document).ready(function () {

            brandSelect();
            divlocations();
            if ($("input.flat")[0]) {
                $(document).ready(function () {
                    $('input.flat').iCheck({
                        checkboxClass: 'icheckbox_flat-green',
                        radioClass: 'iradio_flat-green'
                    });
                });
            }
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
                            window.open('{{url('admin/site/product/update/'.$product['id'].'/2')}}', '_self')
                        }, 1500)

                        //   console.log(user_id+":"+follower_id);


                    });


                }
            })
        }

        function divlocations(){
            $.get("{{url('admin/site/product/get-locations')}}/{{$product['id']}}" , function (data) {

                $('#locations_div').html(data);
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
                                    txt += "Dosya genişliği " + w + "px ";
                                    error = true;
                                }

                                if (h != this.height) {
                                    if (error) {
                                        txt += "ve ";
                                    }
                                    txt += "Dosya yüksekliği " + h + "px ";
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

        function brandSelect() {
            var brand_id = $('#brand_id').val();
            $('#questions_div').html('');
            if (brand_id > 0) {
                $('#model_id').show();
                $('#model_result').html('');
                $.get("{{url('data/get-models')}}/" + brand_id + "/{{$product['model_id']}}", function (data) {


                    if (data == "none") {
                        swal("model bulunamadı");
//                        $('#model_result').html('<h2>Model Bulunamadı</h2>');
                        //  $('#model_id').hide();
                    } else {
                        $('#model_id').prop("disabled", false);
                        $('#model_id').html(data);
                    }
                });
            } else {
                $('#model_id').prop("disabled", true);
                $('#model_id').html('');
            }
        }

        function changeOrder(order, img_id) {
            $.get("{{url('admin/site/product/change-image-order')}}/" + order + "/" + img_id, function (data) {
                swal(data + "");
                setTimeout(function () {
                    window.open('{{url('admin/site/product/update/'.$product['id'].'/1')}}', '_self')
                }, 1500)

            });
        }

        function changeFirst(img_id) {
            $.get("{{url('admin/site/product/change-first')}}/"  + img_id, function (data) {
                swal(data + "");
                setTimeout(function () {
                    window.open('{{url('admin/site/product/update/'.$product['id'].'/1')}}', '_self')
                }, 1500)

            });
        }

        function changeLocationOrder(location_id, new_order){
            $.get("{{url('admin/site/product/change-product-location-order')}}/"  + location_id+"/"+new_order, function (data) {
                swal(data + "");
                setTimeout(function () {
                    window.open('{{url('admin/site/product/update/'.$product['id'].'/2')}}', '_self')
                }, 1500)

            });
        }
        function deleteImage(img_id) {
            swal("Resim silinecek, Emin misiniz?", {
                buttons: ["İptal", "Evet"],
                dangerMode: true,
            }).then((value) => {
                if (value) {

                    $.get("{{url('admin/site/product/delete-image')}}/" + img_id, function (data) {
                        swal(data + "");
                        setTimeout(function () {
                            window.open('{{url('admin/site/product/update/'.$product['id'].'/1')}}', '_self')
                        }, 1500)

                        //   console.log(user_id+":"+follower_id);


                    });


                }
            })
        }

        $('#update-product').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;

            if ($('#title').val() == '') {
                $('#title_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#title_error').html('');
            }
            if ($('#category_id').val() == 0) {
                $('#category_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
                error = true;
            } else {
                $('#category_id_error').html('');
            }
            if ($('#brand_id').val() == 0) {
                $('#brand_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
                error = true;
            } else {
                $('#brand_id_error').html('');
            }
            if ($('#model_id').val() == 0) {
                $('#model_id_error').html('<span style="color: red">Lütfen seçiniz</span>');
                error = true;
            } else {
                $('#model_id_error').html('');
            }
            if ($('#price').val() == 0) {
                $('#price_error').html('<span style="color: red">Lütfen fiyat giriniz</span>');
                error = true;
            } else {
                $('#price_error').html('');
            }
            if (error) {
                return false;
            } else {
                save(formData, '{{route('site.update-product-post')}}', '', '', '');
            }
        });

        $('#add-image').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;

            if ($('#image').val() == '') {
                $('#image_error').html('<span style="color: red">Lütfen resim seçiniz</span>');
                error = true;
            } else {
                $('#image_error').html('');
            }


            if (error) {
                return false;
            } else {
                save(formData, '{{route('site.add-product-image-post')}}', '', '', '');
            }
        });

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
