<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BUYBACK LOGIN </title>

    <!-- Bootstrap -->
    <link href="{{url('vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{url('vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{url('vendors/nprogress/nprogress.css')}}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{url('vendors/animate.css/animate.min.css')}}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="{{url('build/css/custom.min.css')}}" rel="stylesheet">
</head>

<body class="login">
<div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <form id="login-form" action="{{route('admin.login')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <h1>BUYBACK ADMIN</h1>
                    <div>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Kullanıcı Email" />
                        <span id="email_error"></span>
                    </div>
                    <div>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Şifre"  />
                        <span id="password_error"></span>
                    </div>
                    <div style="font-size: 20px">
                        <label for="">Beni Hatırla</label>
                        <input type="checkbox" id="remember_me" name="remember_me"  value="546546"  />

                    </div>
                    <div>
                        <button class="btn btn-primary" onclick="$('#login-form').submit()">GİRİŞ</button>

                    </div>

                    <div class="clearfix"></div>
                        @if(false)
                    <div class="separator">
                        <p class="change_link">New to site?
                            <a href="#signup" class="to_register"> Create Account </a>
                        </p>

                        <div class="clearfix"></div>
                        <br />

                        <div>
                            <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
                            <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                        </div>
                    </div>
                            @endif
                </form>
            </section>
        </div>

        <div id="register" class="animate form registration_form">
            <section class="login_content">
                <form>
                    <h1>Create Account</h1>
                    <div>
                        <input type="text" class="form-control" placeholder="Username" required="" />
                    </div>
                    <div>
                        <input type="email" class="form-control" placeholder="Email" required="" />
                    </div>
                    <div>
                        <input type="password" class="form-control" placeholder="Password" required="" />
                    </div>
                    <div>
                        <a class="btn btn-default submit" href="index.html">Submit</a>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <p class="change_link">Already a member ?
                            <a href="#signin" class="to_register"> Log in </a>
                        </p>

                        <div class="clearfix"></div>
                        <br />

                        <div>
                            <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
                            <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
<script src="{{url('js/jquery-3.5.1.min.js')}}"></script>
<script src="{{url('js/sweetalert.min.js')}}"></script>
<script src="{{url('js/save.js')}}"></script>
<script>

    $('#login-form').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var error = false;
        if ($('#email').val() == '') {
            $('#email_error').html('<span style="color: red">Lütfen giriniz</span>');
            error = true;
        } else {
            $('#email_error').html('');
        }
        if ($('#password').val() == '') {
            $('#password_error').html('<span style="color: red">Lütfen giriniz</span>');
            error = true;
        } else {
            $('#password_error').html('');
        }

        if(error){
            return false;
        }

        //   swal("ok");
        save(formData, '{{route('admin.login')}}', '', 'btn-1','');
    });
</script>
</body>
</html>
