@extends('admin.main_layout');

@section('css_')
    <style>
.main_div{
    min-height: 1000px;
    height: auto;

}
    </style>
@endsection
@section('main')

    <div class="main_div">
        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div class="row x_title">
                    <div class="col-md-6">
                        <h3>BUYBACK<small> Hesaplama Modülü</small></h3>
                    </div>
                    <div class="col-md-6">
                        <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                            <i class="fa fa-calendar"></i>
                            <span>{{date('d-M-Y')}}</span> <b class="caret"></b>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="x_panel">
                            <div class="x_title">

                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">Settings 1</a>
                                            <a class="dropdown-item" href="#">Settings 2</a>
                                        </div>
                                    </li>

                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br>
                                <form class="form-label-left input_mask">
                                    <div class="col-md-6 col-sm-6  form-group has-feedback">
                                        <h2 for="">Marka Seçiniz </h2>
                                        <select name="brand_id" id="brand_id" class="form-control" onchange="brandSelect()">
                                            <option value="0">Seçiniz</option>
                                            @foreach($brands as  $brand)
                                                <option value="{{$brand['id']}}">{{$brand['BrandName']}}</option>
                                            @endforeach

                                        </select>


                                    </div>
                                    <div class="col-md-6 col-sm-6  form-group has-feedback">
                                        <h2 for="">Model Seçiniz </h2>
                                        <select name="model_id" id="model_id" disabled class="form-control" onchange="modelSelect()">
                                            <option value="0">Seçiniz</option>

                                        </select>
                                        <span id="model_result"></span>
                                    </div>


                                    <div class="form-group row" id="questions_div">

                                    </div>

                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6 ">
                        <div class="x_panel">
                            <input type="hidden" name="calculate_result"  id="calculate_result"  >
                        </div>
                    </div>
                </div>

            </div>
        </div>
@if(false)
    <div class="row" style="display: inline-block;" >
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
                        <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
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
                                    <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="80"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p>Twitter Campaign</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="60"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 ">
                        <div>
                            <p>Conventional Media</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="40"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p>Bill boards</p>
                            <div class="">
                                <div class="progress progress_sm" style="width: 76%;">
                                    <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="50"></div>
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
    <br /></div>
@endsection

@section('scripts')
    <script>

function brandSelect(){
    var brand_id=$('#brand_id').val();
    $('#questions_div').html('');
    if(brand_id>0){
        $('#model_id').show();
        $('#model_result').html('');
    $.get( "{{url('admin/data/get-models')}}/"+brand_id, function( data ) {
        //console.log("{{url('admin/brand/get-models')}}/"+brand_id);
        if(data=="none"){
            $('#model_result').html('<h2>Model Bulunamadı</h2>');
            $('#model_id').hide();
        }else{
        $('#model_id').prop( "disabled", false );
        $('#model_id').html(data);
        }
    });
    }else{
        $('#model_id').prop( "disabled", true );
        $('#model_id').html('');
    }
}

function modelSelect(){
    var model_id=$('#model_id').val();
    if(model_id>0){
        $('#model_result').html('');
        $.get( "{{url('admin/data/get-questions')}}/"+model_id, function( data ) {


            $('#questions_div').html(data);

            if(data=="none"){

            }else{

            }
        });
    }else{
        $('#model_id').prop( "disabled", true );
        $('#questions_div').html('');
    }

}



    </script>
@endsection
