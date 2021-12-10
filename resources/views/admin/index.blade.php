@extends('admin.main_layout');

@section('css_')

    <style>
        .main_div {
            min-height: 1000px;
            height: auto;

        }
    </style>
@endsection
@section('main')
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="dashboard_graph">

                <div class="row x_title">
                    <div class="col-md-6">
                        <h3>Network Activities <small>Graph title sub-title</small></h3>
                    </div>

                </div>

                <div class="col-md-12 col-sm-12 ">
                    <div id="chart_plot_01" class="demo-placeholder"></div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12 col-sm-12 ">
                    <div id="chart_plot_02" class="demo-placeholder"></div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12 col-sm-12 ">
                    <div id="chart_plot_03" class="demo-placeholder"></div>
                </div>
                <div class="clearfix"></div>


                <div class="clearfix"></div>

                <div class="row">


                    <div class="col-md-4 col-sm-4 ">
                        <div class="x_panel tile fixed_height_320">
                            <div class="x_title">
                                <h2>App Versions</h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                           aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">Settings 1</a>
                                            <a class="dropdown-item" href="#">Settings 2</a>
                                        </div>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <h4>Modeller</h4>
                                <div class="widget_summary">
                                    <div class="w_left w_25">
                                        <span>0.1.5.2</span>
                                    </div>
                                    <div class="w_center w_55">
                                        <div class="progress">
                                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60"
                                                 aria-valuemin="0" aria-valuemax="100" style="width: 66%;">
                                                <span class="sr-only">60% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w_right w_20">
                                        <span>123k</span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="widget_summary">
                                    <div class="w_left w_25">
                                        <span>0.1.5.3</span>
                                    </div>
                                    <div class="w_center w_55">
                                        <div class="progress">
                                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60"
                                                 aria-valuemin="0" aria-valuemax="100" style="width: 45%;">
                                                <span class="sr-only">60% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w_right w_20">
                                        <span>53k</span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="widget_summary">
                                    <div class="w_left w_25">
                                        <span>0.1.5.4</span>
                                    </div>
                                    <div class="w_center w_55">
                                        <div class="progress">
                                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60"
                                                 aria-valuemin="0" aria-valuemax="100" style="width: 25%;">
                                                <span class="sr-only">60% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w_right w_20">
                                        <span>23k</span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="widget_summary">
                                    <div class="w_left w_25">
                                        <span>0.1.5.5</span>
                                    </div>
                                    <div class="w_center w_55">
                                        <div class="progress">
                                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60"
                                                 aria-valuemin="0" aria-valuemax="100" style="width: 5%;">
                                                <span class="sr-only">60% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w_right w_20">
                                        <span>3k</span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="widget_summary">
                                    <div class="w_left w_25">
                                        <span>0.1.5.6</span>
                                    </div>
                                    <div class="w_center w_55">
                                        <div class="progress">
                                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="60"
                                                 aria-valuemin="0" aria-valuemax="100" style="width: 2%;">
                                                <span class="sr-only">60% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w_right w_20">
                                        <span>1k</span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-4 ">
                        <div class="x_panel tile fixed_height_320 overflow_hidden">
                            <div class="x_title">
                                <h2>Durum Dağılımı</h2>
                                <ul class="nav navbar-right panel_toolbox">

                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <table class="" style="width:100%">
                                    <tr>
                                        <th style="width:37%;">
                                            <p> </p>
                                        </th>
                                        <th>
                                            <div class="col-lg-7 col-md-7 col-sm-7 ">
                                                <p class="">Durum</p>
                                            </div>
                                            <div class="col-lg-5 col-md-5 col-sm-5 ">
                                                <p class="">Sayım</p>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <canvas class="canvasDoughnut" height="140" width="140"
                                                    style="margin: 15px 10px 10px 0"></canvas>
                                        </td>
                                        <td>
                                            <table class="tile_info">
                                                @foreach($array as $item)
                                                <tr>
                                                    <td>
                                                        <p><i class="fa fa-square blue"></i>{{$item['status']}} </p>
                                                    </td>
                                                    <td>{{$item['count']}}</td>
                                                </tr>
                                                @endforeach

                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-4 col-sm-4 ">
                        <div class="x_panel tile fixed_height_320">
                            <div class="x_title">
                                <h2>Quick Settings</h2>
                                <ul class="nav navbar-right panel_toolbox">

                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <div class="dashboard-widget-content">
                                    <ul class="quick-list">
                                        <li><i class="fa fa-calendar-o"></i><a href="#">Settings</a>
                                        </li>
                                        <li><i class="fa fa-bars"></i><a href="#">Subscription</a>
                                        </li>
                                        <li><i class="fa fa-bar-chart"></i><a href="#">Auto Renewal</a></li>
                                        <li><i class="fa fa-line-chart"></i><a href="#">Achievements</a>
                                        </li>
                                        <li><i class="fa fa-bar-chart"></i><a href="#">Auto Renewal</a></li>
                                        <li><i class="fa fa-line-chart"></i><a href="#">Achievements</a>
                                        </li>
                                        <li><i class="fa fa-area-chart"></i><a href="#">Logout</a>
                                        </li>
                                    </ul>

                                    <div class="sidebar-widget">
                                        <h4>Profile Completion</h4>
                                        <canvas width="150" height="80" id="chart_gauge_01" class=""
                                                style="width: 160px; height: 100px;"></canvas>
                                        <div class="goal-wrapper">
                                            <span id="gauge-text" class="gauge-value pull-left">0</span>
                                            <span class="gauge-value pull-left">%</span>
                                            <span id="goal-text" class="goal-value pull-right">100%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-3  bg-white">
                    <div class="x_title">
                        <h2>Top Campaign Performance</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12 col-sm-12 ">
                        <div>
                            <p>Facebook Campaign</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="20"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p>Twitter Campaign</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="60"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 ">
                        <div>
                            <p>Conventional Media</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="40"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p>Bill boards</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="50"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-3 col-sm-3  bg-white">
                    <div class="x_title">
                        <h2>Top Campaign Performance</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12 col-sm-12 ">
                        <div>
                            <p>Facebook Campaign</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="20"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p>Twitter Campaign</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="60"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 ">
                        <div>
                            <p>Conventional Media</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="40"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p>Bill boards</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="50"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-3 col-sm-3  bg-white">
                    <div class="x_title">
                        <h2>Top Campaign Performance</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12 col-sm-12 ">
                        <div>
                            <p>Facebook Campaign</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="20"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p>Twitter Campaign</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="60"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 ">
                        <div>
                            <p>Conventional Media</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="40"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p>Bill boards</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="50"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-3 col-sm-3  bg-white">
                    <div class="x_title">
                        <h2>Top Campaign Performance</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="col-md-12 col-sm-12 ">
                        <div>
                            <p>Facebook Campaign</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="20"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p>Twitter Campaign</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="60"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 ">
                        <div>
                            <p>Conventional Media</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="40"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p>Bill boards</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar"
                                         data-transitiongoal="50"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
    @if(false)
        <div class="main_div">
            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="row x_title">
                        <div class="col-md-6">
                            <h3>ANASAYFA</h3>
                        </div>
                        <div class="col-md-6">
                            <div id="reportrange" class="pull-right"
                                 style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                                <i class="fa fa-calendar"></i>
                                <span>{{date('d-M-Y')}}</span> <b class="caret"></b>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="x_panel">
                                <div class="x_title">

                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <br>

                                </div>
                            </div>

                        </div>


                    </div>

                </div>
            </div>

            <div class="row" style="display: inline-block;">
                <div class="tile_count">
                    <div class="col-md-2 col-sm-4  tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Total Users</span>
                        <div class="count">2500</div>
                        <span class="count_bottom"><i class="green">4% </i> From last Week</span>
                    </div>
                    <div class="col-md-2 col-sm-4  tile_stats_count">
                        <span class="count_top"><i class="fa fa-clock-o"></i> Average Time</span>
                        <div class="count">123.50</div>
                        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>
                    </div>
                    <div class="col-md-2 col-sm-4  tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Total Males</span>
                        <div class="count green">2,500</div>
                        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
                    </div>
                    <div class="col-md-2 col-sm-4  tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Total Females</span>
                        <div class="count">4,567</div>
                        <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> From last Week</span>
                    </div>
                    <div class="col-md-2 col-sm-4  tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Total Collections</span>
                        <div class="count">2,315</div>
                        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
                    </div>
                    <div class="col-md-2 col-sm-4  tile_stats_count">
                        <span class="count_top"><i class="fa fa-user"></i> Total Connections</span>
                        <div class="count">7,325</div>
                        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
                    </div>
                </div>
            </div>
            <!-- /top tiles -->

            <div class="row">
                <div class="col-md-12 col-sm-12 ">
                    <div class="dashboard_graph">

                        <div class="row x_title">
                            <div class="col-md-6">
                                <h3>Network Activities <small>Graph title sub-title</small></h3>
                            </div>
                            <div class="col-md-6">
                                <div id="reportrange" class="pull-right"
                                     style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9 col-sm-9 ">
                            <div id="chart_plot_01" class="demo-placeholder"></div>
                        </div>
                        <div class="col-md-3 col-sm-3  bg-white">
                            <div class="x_title">
                                <h2>Top Campaign Performance</h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12 col-sm-12 ">
                                <div>
                                    <p>Facebook Campaign</p>
                                    <div class="">
                                        <div class="progress progress_sm" style="width: 76%;">
                                            <div class="progress-bar bg-green" role="progressbar"
                                                 data-transitiongoal="80"></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p>Twitter Campaign</p>
                                    <div class="">
                                        <div class="progress progress_sm" style="width: 76%;">
                                            <div class="progress-bar bg-green" role="progressbar"
                                                 data-transitiongoal="60"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 ">
                                <div>
                                    <p>Conventional Media</p>
                                    <div class="">
                                        <div class="progress progress_sm" style="width: 76%;">
                                            <div class="progress-bar bg-green" role="progressbar"
                                                 data-transitiongoal="40"></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p>Bill boards</p>
                                    <div class="">
                                        <div class="progress progress_sm" style="width: 76%;">
                                            <div class="progress-bar bg-green" role="progressbar"
                                                 data-transitiongoal="50"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>

            </div>
            @endif
            <br/></div>
@endsection

@section('scripts')
    <script src="{{url('vendors/Chart.js/dist/Chart.min.js')}}"></script>
    <script src="{{url('vendors/Flot/jquery.flot.js')}}"></script>
    <script src="{{url('vendors/Flot/jquery.flot.pie.js')}}"></script>
    <script src="{{url('vendors/Flot/jquery.flot.time.js')}}"></script>
    <script src="{{url('vendors/flot-spline/js/jquery.flot.spline.min.js')}}"></script>
    <script src="{{url('vendors/DateJS/build/date.js')}}"></script>
    <script src="{{url('vendors/gauge.js/dist/gauge.min.js')}}"></script>
    <script src="{{url('vendors/moment/min/moment.min.js')}}"></script>
     <script>
        init_flot_chart();
        init_chart_doughnut();
        init_gauge();
        function init_chart_doughnut() {

            if ("undefined" != typeof Chart && (  $(".canvasDoughnut").length)) {
                var a = {
                    type: "doughnut",
                    tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                    data: {
                        labels: [
                            @foreach($array as $item)

                            "{{$item['status']}}",
                            @endforeach

                        ],
                        datasets: [{
                            data: [  @foreach($array as $item)

                                {{$item['count']}},
                                @endforeach ],
                            backgroundColor: [@foreach($array as $item)

                                "{{$item['color']}}",
                                @endforeach ],
                            hoverBackgroundColor: [
                                @foreach($array as $item)

                                    "#CFD4D8",
                                @endforeach
                                  ]
                        }]
                    },
                    options: {legend: !1, responsive: !1}
                };
                $(".canvasDoughnut").each(function () {
                    var e = $(this);
                    new Chart(e, a)
                })
            }
        }
        function init_flot_chart() {
            if (void 0 !== $.plot) {
           //     console.log("init_flot_chart");
                for (var e = [[gd(2012, 1, 1), 17], [gd(2012, 1, 2), 74], [gd(2012, 1, 3), 6], [gd(2012, 1, 4), 39], [gd(2012, 1, 5), 20], [gd(2012, 1, 6), 85], [gd(2012, 1, 7), 7]], a = [[gd(2012, 1, 1), 82], [gd(2012, 1, 2), 23], [gd(2012, 1, 3), 66], [gd(2012, 1, 4), 9], [gd(2012, 1, 5), 119], [gd(2012, 1, 6), 6], [gd(2012, 1, 7), 9]], t = [], o = 0; o < 30; o++) t.push([new Date(Date.today().add(o).days()).getTime(), Math.floor(21 * Math.random()) + 20 + o + o + 10]);
                var n = {
                    grid: {
                        show: !0,
                        aboveData: !0,
                        color: "#3f3f3f",
                        labelMargin: 10,
                        axisMargin: 0,
                        borderWidth: 0,
                        borderColor: null,
                        minBorderMargin: 5,
                        clickable: !0,
                        hoverable: !0,
                        autoHighlight: !0,
                        mouseActiveRadius: 100
                    },
                    series: {
                        lines: {show: !0, fill: !0, lineWidth: 2, steps: !1},
                        points: {show: !0, radius: 4.5, symbol: "circle", lineWidth: 3}
                    },
                    legend: {
                        position: "ne",
                        margin: [0, -25],
                        noColumns: 0,
                        labelBoxBorderColor: null,
                        labelFormatter: function (e, a) {
                            return e + "&nbsp;&nbsp;"
                        },
                        width: 40,
                        height: 1
                    },
                    colors: ["#96CA59", "#3F97EB", "#72c380", "#6f7a8a", "#f7cb38", "#5a8022", "#2c7282"],
                    shadowSize: 0,
                    tooltip: !0,
                    tooltipOpts: {
                        content: "%s: %y.0",
                        xDateFormat: "%d/%m",
                        shifts: {x: -30, y: -50},
                        defaultTheme: !1
                    },
                    yaxis: {min: 0},
                    xaxis: {mode: "time", minTickSize: [1, "day"], timeformat: "%d/%m/%y", min: t[0][0], max: t[20][0]}
                };
                $("#chart_plot_01").length && ($.plot($("#chart_plot_01"), [e, a], {
                    series: {
                        lines: {
                            show: !1,
                            fill: !0
                        },
                        splines: {show: !0, tension: .4, lineWidth: 1, fill: .4},
                        points: {radius: 0, show: !0},
                        shadowSize: 2
                    },
                    grid: {
                        verticalLines: !0,
                        hoverable: !0,
                        clickable: !0,
                        tickColor: "#d5d5d5",
                        borderWidth: 1,
                        color: "#fff"
                    },
                    colors: ["rgba(38, 185, 154, 0.38)", "rgba(3, 88, 106, 0.38)"],
                    xaxis: {
                        tickColor: "rgba(51, 51, 51, 0.06)",
                        mode: "time",
                        tickSize: [1, "day"],
                        axisLabel: "Date",
                        axisLabelUseCanvas: !0,
                        axisLabelFontSizePixels: 12,
                        axisLabelFontFamily: "Verdana, Arial",
                        axisLabelPadding: 10
                    },
                    yaxis: {ticks: 8, tickColor: "rgba(51, 51, 51, 0.06)"},
                    tooltip: !1
                })), $("#chart_plot_02").length && (  $.plot($("#chart_plot_02"), [{
                    label: "Email Sent",
                    data: t,
                    lines: {fillColor: "rgba(150, 202, 89, 0.12)"},
                    points: {fillColor: "#fff"}
                }], n)), $("#chart_plot_03").length && (  $.plot($("#chart_plot_03"), [{
                    label: "Registrations",
                    data: [[0, 1], [1, 9], [2, 6], [3, 10], [4, 5], [5, 17], [6, 6], [7, 10], [8, 7], [9, 11], [10, 35], [11, 9], [12, 12], [13, 5], [14, 3], [15, 4], [16, 9]],
                    lines: {fillColor: "rgba(150, 202, 89, 0.12)"},
                    points: {fillColor: "#fff"}
                }], {
                    series: {curvedLines: {apply: !0, active: !0, monotonicFit: !0}},
                    colors: ["#26B99A"],
                    grid: {
                        borderWidth: {top: 0, right: 0, bottom: 1, left: 1},
                        borderColor: {bottom: "#7F8790", left: "#7F8790"}
                    }
                }))
            }
        }
        function init_gauge() {
            if ("undefined" != typeof Gauge) {
             //   console.log("init_gauge [" + $(".gauge-chart").length + "]"), console.log("init_gauge");
                var e = {
                    lines: 12,
                    angle: 0,
                    lineWidth: .4,
                    pointer: {length: .75, strokeWidth: .042, color: "#1D212A"},
                    limitMax: "false",
                    colorStart: "#1ABC9C",
                    colorStop: "#1ABC9C",
                    strokeColor: "#F0F3F3",
                    generateGradient: !0
                };
                if ($("#chart_gauge_01").length) var a = document.getElementById("chart_gauge_01"),
                    t = new Gauge(a).setOptions(e);
                if ($("#gauge-text").length && (t.maxValue = 6e3, t.animationSpeed = 32, t.set(3200), t.setTextField(document.getElementById("gauge-text"))), $("#chart_gauge_02").length) var o = document.getElementById("chart_gauge_02"),
                    n = new Gauge(o).setOptions(e);
                $("#gauge-text2").length && (n.maxValue = 9e3, n.animationSpeed = 32, n.set(2400), n.setTextField(document.getElementById("gauge-text2")))
            }
        }
    </script>
@endsection
