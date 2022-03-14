
    <link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">

    <div class="content">
        <div class="row">
            <div class="col-md-12">




                        <form id="create-sub-menu" action="{{route('site.create-sub-menu-post')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="menu_id" id="menu_id" value="{{$menu_id}}">

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Başlık :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="title_sub" id="title_sub"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Başlık Yazısı">
                                    <span id="title_sub_error"></span>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Link :</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="link_sub" id="link_sub"
                                           value="" data-popup="tooltip" data-trigger="focus"
                                           placeholder="Açılacak Bağlantı">
                                    <span id="link_sub_error"></span>
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

                                    <img id="target" style="display: none;max-width: 500px">
                                    <img id="avatar_img" style="display: none;">

                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">Sıra :</label>
                                <div class="col-lg-2">
                                    <select name="order_sub" id="order_sub" class="form-control">
                                           @for($i=$count-1;$i>0;$i--)
                                            <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                    </select>

                                </div>
                                <div class="col-lg-9"></div>
                            </div>

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                                <div class="col-lg-8">
                                    <label>
                                        <input type="checkbox" class="js-switch" checked="true" name="status_sub" id="status_sub" value="13" data-switchery="true"
                                               style="display: none;">



                                    </label>
                                </div>
                            </div>
                            <br>
                            <!-- /touchspin spinners -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary font-weight-bold rounded-round">MENÜ ALT UNSURU EKLE
                                    <i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>

            </div>
        </div>
    </div>

    <script src="{{url("js/save.js")}}"></script>
    <script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
    <script>
        $(document).ready(function () {



            var elems = Array.prototype.slice.call(document.querySelectorAll('#status_sub'));
            elems.forEach(function (html) {

                var switchery = new Switchery(html, {
                    color: '#26B99A'
                });

            });


        });
        function validURL(str) {
            var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
                '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
            return !!pattern.test(str);
        }
        $('#create-sub-menu').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            var error = false;

            if ($('#title_sub').val() == '') {
                $('#title_sub_error').html('<span style="color: red">Lütfen giriniz</span>');
                error = true;
            } else {
                $('#title_sub_error').html('');
            }



            // if ($('#link_sub').val() != '') {
            //     if(!validURL($('#link_sub').val())){
            //         // $('#link_sub_error').html('<span style="color: red">Geçersiz URL</span>');
            //         // $('#link_sub').val('');
            //         // error = true;
            //     }else{
            //         $('#link_sub_error').html('');
            //     }
            // }
            if(error){


                return false;
            }else{
                save(formData, '{{route('site.create-sub-menu-post')}}', '', '','');
            }
        });
    </script>

