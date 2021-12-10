<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BUYBACK</title>
    <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{url('css/bootstrap-theme.min.css')}}">
    @yield('css')
</head>
<body>

@yield('content')

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="lg-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="lg-modal-title">Modal title</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="lg-modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>

            </div>
        </div>
    </div>
</div>


<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="sm-modal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="sm-modal-title">Modal title</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="sm-modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>

            </div>
        </div>
    </div>
</div>

<script src="{{url('js/jquery-3.5.1.min.js')}}"></script>
<script src="{{url('js/bootstrap.js')}}"></script>
<script src="{{url('js/sweetalert.min.js')}}"></script>
@yield('scripts')
</body>
</html>
