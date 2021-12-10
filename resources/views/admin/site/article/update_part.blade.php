<link href="{{url('vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">
<div class="row">
    <div class="col-md-12">


        <form id="update-part" action="{{route('site.update-article-part-post')}}" method="post"
              enctype="multipart/form-data">
            {{csrf_field()}}

            <input type="hidden" id="article_id" name="article_id" value="{{$part['article_id']}}">
            <input type="hidden" id="id" name="id" value="{{$part['id']}}">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label font-weight-semibold">Başlık :</label>
                <div class="col-lg-8">
                    <input type="text" class="form-control" name="title2" id="title2"
                           value="{{$part['title']}}" data-popup="tooltip" data-trigger="focus"
                           placeholder="Başlık Yazısı">
                    <span id="title2_error"></span>
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
                    @if(!empty($part['thumb']))
                        <a href="{{url($part['image'])}}" target="_blank"><img id="avatar_img"
                                                                               src="{{url($part['thumb'])}}"></a>
                    @endif

                </div>
            </div>
            <div class="form-group row">

                <div class="col-lg-12">

                    <div class="x_content">
                        <div id="alerts"></div>
                        <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                            <div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i
                                        class="fa fa-font"></i><b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                </ul>
                            </div>
                            <div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i
                                        class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a data-edit="fontSize 5">
                                            <p style="font-size:17px">Huge</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-edit="fontSize 3">
                                            <p style="font-size:14px">Normal</p>
                                        </a>
                                    </li>
                                    <li>
                                        <a data-edit="fontSize 1">
                                            <p style="font-size:11px">Small</p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                                <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i
                                        class="fa fa-italic"></i></a>
                                <a class="btn" data-edit="strikethrough" title="Strikethrough"><i
                                        class="fa fa-strikethrough"></i></a>
                                <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i
                                        class="fa fa-underline"></i></a>
                            </div>
                            <div class="btn-group">
                                <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i
                                        class="fa fa-list-ul"></i></a>
                                <a class="btn" data-edit="insertorderedlist" title="Number list"><i
                                        class="fa fa-list-ol"></i></a>
                                <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i
                                        class="fa fa-dedent"></i></a>
                                <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
                            </div>
                            <div class="btn-group">
                                <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i
                                        class="fa fa-align-left"></i></a>
                                <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i
                                        class="fa fa-align-center"></i></a>
                                <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i
                                        class="fa fa-align-right"></i></a>
                                <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i
                                        class="fa fa-align-justify"></i></a>
                            </div>
                            <div class="btn-group">
                                <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i
                                        class="fa fa-link"></i></a>
                                <div class="dropdown-menu input-append">
                                    <input class="span2" placeholder="URL" type="text" data-edit="createLink">
                                    <button class="btn" type="button">Add</button>
                                </div>
                                <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
                            </div>
                            <div class="btn-group">
                                <a class="btn" title="Insert picture (or just drag &amp; drop)" id="pictureBtn"><i
                                        class="fa fa-picture-o"></i></a>
                                <input type="file" data-role="magic-overlay" data-target="#pictureBtn"
                                       data-edit="insertImage">
                            </div>
                            <div class="btn-group">
                                <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                                <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i
                                        class="fa fa-repeat"></i></a>
                            </div>
                        </div>
                        <div id="editor-one" class="editor-wrapper placeholderText"
                             contenteditable="true">{{$part['paragraph']}}</div>
                        <span id="title_error"></span>
                        <textarea name="descr" id="descr" style="display: none"></textarea>
                        <span id="descr_error"></span>
                    </div>

                </div>
            </div>


            <div class="form-group row">
                <label class="col-lg-2 col-form-label font-weight-semibold">Sıra :</label>
                <div class="col-lg-2">
                    <select name="count" id="count" class="form-control">
                        @for($i=$count;$i>0;$i--)
                            <option value="{{$i}}" @if($part['count']==$i) selected @endif>{{$i}}</option>
                        @endfor

                    </select>
                </div>
            </div>


            <div class="form-group row">
                <label class="col-lg-2 col-form-label font-weight-semibold">DURUM</label>
                <div class="col-lg-8">
                    <label>
                        <input type="checkbox" class="js-switch status_1" @if($part['status']==1) checked
                               @endif   name="status" id="status" value="13" data-switchery="true"
                               style="display: none;">


                    </label>
                </div>
            </div>

            <br>
            <!-- /touchspin spinners -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary font-weight-bold rounded-round" onmouseover="fill()">
                    Paragraf GÜNCELLE
                    <i class="icon-paperplane ml-2"></i></button>
            </div>
        </form>
    </div>
</div>
<script src="{{url("js/save.js")}}"></script>
<script src="{{url('vendors/switchery/dist/switchery.js')}}"></script>
<script src="{{url('vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js')}} "></script>

<script src="{{url('vendors/jquery.hotkeys/jquery.hotkeys.js')}}"></script>
<script src="{{url('vendors/google-code-prettify/src/prettify.js')}}"></script>
<script>
    $(document).ready(function () {


        init_wysiwyg();
        var elems = Array.prototype.slice.call(document.querySelectorAll('.status_1'));
        elems.forEach(function (html) {

            var switchery = new Switchery(html, {
                color: '#26B99A'
            });

        });


    });

    function fill() {
        $('#descr').val($('#editor-one').html());
    }

    function init_wysiwyg() {

        if (typeof ($.fn.wysiwyg) === 'undefined') {
            return;
        }

        // console.log('init_wysiwyg');

        function init_ToolbarBootstrapBindings() {
            var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
                    'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
                    'Times New Roman', 'Verdana'
                ],
                fontTarget = $('[title=Font]').siblings('.dropdown-menu');
            $.each(fonts, function (idx, fontName) {
                fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
            });
            $('a[title]').tooltip({
                container: 'body'
            });
            $('.dropdown-menu input').click(function () {
                return false;
            })
                .change(function () {
                    $(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
                })
                .keydown('esc', function () {
                    this.value = '';
                    $(this).change();
                });

            $('[data-role=magic-overlay]').each(function () {
                var overlay = $(this),
                    target = $(overlay.data('target'));
                overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
            });

            if ("onwebkitspeechchange" in document.createElement("input")) {
                var editorOffset = $('#editor').offset();

                $('.voiceBtn').css('position', 'absolute').offset({
                    top: editorOffset.top,
                    left: editorOffset.left + $('#editor').innerWidth() - 35
                });
            } else {
                $('.voiceBtn').hide();
            }
        }

        function showErrorAlert(reason, detail) {
            var msg = '';
            if (reason === 'unsupported-file-type') {
                msg = "Unsupported format " + detail;
            } else {
                console.log("error uploading file", reason, detail);
            }
            $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
                '<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
        }

        $('.editor-wrapper').each(function () {
            var id = $(this).attr('id');	//editor-one

            $(this).wysiwyg({
                toolbarSelector: '[data-target="#' + id + '"]',
                fileUploadError: showErrorAlert
            });
        });


        window.prettyPrint;
        prettyPrint();

    };


    function showImage(img, t, hide_it,w=0,h=0) {
        $('#' + hide_it).hide();
        $('#' + img).show();
        var src = document.getElementById(img);
        var target = document.getElementById(t);
        var val = $('#' + img).val();
        var arr = val.split('\\');
        $('#'+img+'_error').html("");
        $('#'+t).show();


        $.get("{{url('/check_image')}}/" + arr[arr.length - 1], function (data) {

            if (data === 'ok') {
                $('#'+img+'_error').html("");
                $('#'+t).show();
                var fr = new FileReader();
                fr.onload = function (e) {

                    var image = new Image();
                    image.src = e.target.result;

                    image.onload = function (e) {
                        console.log(""+w+":"+h);
                        if(this.width>2000 || this.height>2000){
                            $('#'+img+'_error').html('<span style="color: red">Çok büyük dosya</span>');
                            //swal("admin.image_wrong_format");
                            $('#'+img).val('');

                            $('#'+hide_it ).hide();

                            $('#'+t ).hide();
                        }else{

                            if(w>0 || h>0){
                                var error= false;
                                var txt = "";
                                if(w!=this.width){
                                    txt+="Dosya genişliği "+w+"px ";
                                    error=true;
                                }

                                if(h!=this.height){
                                    if(error) {
                                        txt+="ve ";
                                    }
                                    txt+="Dosya yüksekliği "+h+"px ";
                                    error=true;
                                }
                                if(error){
                                    $('#'+img+'_error').html('<span style="color: red">'+txt+' olmalıdır </span>');
                                    //swal("admin.image_wrong_format");
                                    $('#'+img).val('');
                                    $('#'+t ).hide();
                                    $('#'+hide_it ).hide();
                                    return  false;
                                }


                                // swal(this.width+"x"+this.height);
                                // $('#'+img+'_error').html("ölçü yanlş");
                                // $('#'+t).hide();
                                return false;
                            }

                        }///2000
                        //  $('#imgresizepreview, #profilepicturepreview').attr('src', this.src);
                    };

                    target.src = this.result;
                };
                fr.readAsDataURL(src.files[0]);
            } else {
                $('#'+img+'_error').html('<span style="color: red">Yanlış dosya biçimi</span>');
                //swal("admin.image_wrong_format");
                $('#'+img).val('');
                $('#'+t ).hide();
                $('#'+hide_it ).hide();


            }
        });


    }

    $('#update-part').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var error = false;

        if ($('#title2').val() == '') {
            $('#title2_error').html('<span style="color: red">Lütfen giriniz</span>');
            error = true;
        } else {
            $('#title2_error').html('');
        }

        if ($('#descr').val() == '' && $('#image').val() == '') {
            $('#image_error').html('<span style="color: red">Lütfen dosya seçiniz ya da metin giriniz</span>');
            $('#descr_error').html('<span style="color: red">Lütfen dosya seçiniz ya da metin giriniz</span>');
            error = true;
        } else {
            $('#image_error').html('');
            $('#descr_error').html('');
        }


        if (error) {
            return false;
        } else {
            save(formData, '{{route('site.update-article-part-post')}}', '', '', '');
        }
    });
</script>
