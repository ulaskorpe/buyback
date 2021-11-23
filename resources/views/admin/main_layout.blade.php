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
@include('admin.partials.scripts')

</body>
</html>
