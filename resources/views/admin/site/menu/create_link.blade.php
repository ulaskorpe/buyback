
<link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">

<div class="content">
    <div class="row">
        <div class="col-md-12">




            <form id="create-sub-link2" action="{{route('site.create-sub-link-post')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" name="group_id" id="group_id" value="{{$group_id}}">

                <div class="form-group row"><div class="col-12"><h3>{{$group->menu_sub_item()->first()->title}} > {{$group['title']}}</h3></div></div>
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label font-weight-semibold">Başlık :</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="title_link" id="title_link"
                               value="" data-popup="tooltip" data-trigger="focus"
                               placeholder="Başlık Yazısı">
                        <span id="title_link_error"></span>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-lg-2 col-form-label font-weight-semibold">Link :</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="sub_link" id="sub_link"
                               value="" data-popup="tooltip" data-trigger="focus"
                               placeholder="Açılacak Bağlantı">
                        <span id="sub_link_error"></span>
                    </div>
                </div>




                <div class="form-group row">
                    <label class="col-lg-2 col-form-label font-weight-semibold">Sıra :</label>
                    <div class="col-lg-2">
                        <select name="order_link" id="order_link" class="form-control">
                            @for($i=1;$i<$count;$i++)
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
                            <input type="checkbox" class="js-switch" checked="true" name="status_link" id="status_link" value="13" data-switchery="true"
                                   style="display: none;">



                        </label>
                    </div>
                </div>
                <br>
                <!-- /touchspin spinners -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary font-weight-bold rounded-round">MENÜ ALT LİNK EKLE
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



        var elems = Array.prototype.slice.call(document.querySelectorAll('#status_link'));
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

    $('#create-sub-link2').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var error12 = false;

        if ($('#title_link').val() == '') {

            $('#title_link_error').html('<span style="color: red">Lütfen giriniz</span>');
            error12 = true;
        } else {
            $('#title_link_error').html('');
        }



        if ($('#sub_link').val() != '') {
            if(!validURL($('#sub_link').val())){

                // $('#sub_link_error').html('<span style="color: red">Geçersiz URL</span>');
                // $('#sub_link').val('');
                // error12 = true;
            }else{

                $('#sub_link_error').html('');
            }
        }
        if(error12){

            return false;
        }else{
            save(formData, '{{route('site.create-sub-link-post')}}', '', '','');
        }
    });
</script>

