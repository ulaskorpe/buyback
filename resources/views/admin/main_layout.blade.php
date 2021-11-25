<!DOCTYPE html>
<html lang="tr">
@include('admin.partials.head')

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        @include('admin.partials.left_menu')

        <!-- top navigation -->
        @include('admin.partials.top_menu')
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            @yield('main')
        </div>
        <!-- /page content -->

        <!-- footer content -->
        @include('admin.partials.footer')
        <!-- /footer content -->
    </div>
</div>

@if(false)
<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal</button>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-sm">Small modal</button>
@endif

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

@include('admin.partials.scripts')

</body>
</html>
