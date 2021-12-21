
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{route('admin.index')}}" class="site_title"><i class="fa fa-calculator"></i> <span>BUYBACK</span></a>
        </div>

        <div class="clearfix"></div>

        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-user"></i> Kullanıcı <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('admin.profile')}}">Kullanıcı Bilgilerim</a></li>
                            <li><a href="{{route('admin.password')}}">Şifre Güncelle</a></li>
                            <li><a href="{{route('admin.settings')}}">Ayarlar</a></li>


                        </ul>
                    </li>

                    @if(!empty(Session::get('sudo') || !empty(Session::get('users'))))
                    <li><a><i class="fa fa-users"></i> Kullanıcılar <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('users.user-list')}}">Kullanıcı Listesi</a></li>
                            <li style="display: none"><a href="{{route('users.user-create')}}"></a></li>
                            <li style="display: none"><a href="{{route('users.group-create')}}"></a></li>
                            @if(!empty($user_id))
                            <li style="display: none"><a href="{{route('users.user-update',$user_id)}}"></a></li>
                            @endif
                            @if(!empty($group_id))
                                <li style="display: none"><a href="{{route('users.group-update',$group_id)}}"></a></li>
                            @endif
                            <li><a href="{{route('users.user-groups')}}">Kullanıcı Grupları</a></li>
                        </ul>
                    </li>
                    @endif

                    @if(!empty(Session::get('sudo') || !empty(Session::get('buyback'))))
                        <li><a><i class="fa fa-money"></i> Geri Alım <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="{{route('buyback.buyback-list')}}">Geri Alım Listesi</a></li>
                                <li><a href="{{route('buyback.create-buyBack')}}">Geri Alım Oluştur</a>

                                @if(!empty($u_id))
                                    <li style="display: none"><a href="{{route('buyback.buyback-list',$u_id)}}"></a></li>
                                @endif

                                @if(!empty($bb_id))
                                    <li style="display: none"><a href="{{route('buyback.buyback-update',$bb_id)}}"></a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if(!empty(Session::get('sudo') || !empty(Session::get('system'))))
                    <li><a><i class="fa fa-edit"></i> Sistem Verileri <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('brand.brandlist')}}">Markalar</a></li>
                            <li style="display: none"><a href="{{route('brand.brandadd')}}"></a></li>
                            @if(!empty($brand_id))
                                <li style="display: none"><a href="{{route('brand.brandupdate',$brand_id)}}"></a></li>
                                @endif
                            <li><a href="{{route('model.model-list')}}">Modeller</a></li>
                            <li style="display: none"><a href="{{route('model.model-add')}}"></a></li>
                            @if(!empty($model_id))
                                <li style="display: none"><a href="{{route('model.model-update',$model_id)}}"></a></li>
                                <li style="display: none"><a href="{{route('model.model-questions',$model_id)}}"></a></li>
                            @endif
                            <li><a href="{{route('color.color-list')}}">Renkler</a></li>
                            <li style="display: none"><a href="{{route('color.color-add')}}"></a></li>
                            @if(!empty($color_id))
                                <li style="display: none"><a href="{{route('color.colorUpdate',$color_id)}}"></a></li>
                            @endif
                            <li><a href="{{route('memory.memorylist')}}">Hafıza Listesi</a></li>
                            <li style="display: none"><a href="{{route('memory.memoryadd')}}"></a></li>
                            @if(!empty($memory_id))
                                <li style="display: none"><a href="{{route('memory.memoryupdate',$memory_id)}}"></a></li>
                            @endif

                            <li><a href="{{route('question.question-list')}}">Sorular</a></li>
                            <li style="display: none"><a href="{{route('question.questionadd')}}"></a></li>
                            @if(!empty($question_id))
                                <li style="display: none"><a href="{{route('question.questionupdate',$question_id)}}"></a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                      @if(!empty(Session::get('sudo') || !empty(Session::get('site'))))
                    <li><a><i class="fa fa-desktop"></i> Site İçeriği <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{route('site.site-settings')}}">Site Ayarları</a></li>
                            <li style="display: none"><a href="{{route('site.create-setting')}}"></a></li>
                            @if(!empty($setting_id))
                                <li style="display: none"><a href="{{route('site.setting-update',$setting_id)}}"></a></li>
                            @endif
                            <li><a href="{{route('site.menu-list')}}">Menu Bağlantıları</a></li>
                            <li style="display: none"><a href="{{route('site.create-menu')}}"></a></li>
                            @if(!empty($menu_id))
                                <li style="display: none"><a href="{{route('site.update-menu',$menu_id)}}"></a></li>
                            @endif

                            <li><a href="{{route('site.area-list')}}">Alan Listesi</a></li>
                            <li style="display: none"><a href="{{route('site.create-area')}}"></a></li>
                            @if(!empty($area_id))
                                <li style="display: none"><a href="{{route('site.update-area',$area_id)}}"></a></li>
                            @endif

                            <li><a href="{{route('site.slider-list')}}">Slider Listesi</a></li>
                            <li style="display: none"><a href="{{route('site.create-slider')}}"></a></li>
                            @if(!empty($slider_id))
                                <li style="display: none"><a href="{{route('site.update-slider',$slider_id)}}"></a></li>
                            @endif
                            <li><a href="{{route('site.article-list')}}">Yazılar Listesi</a></li>
                            <li style="display: none"><a href="{{route('site.create-article')}}"></a></li>
                            @if(!empty($article_id))
                                <li style="display: none"><a href="{{route('site.update-article',$article_id)}}"></a></li>
                            @endif
                            <li><a href="{{route('site.product-list')}}">Ürünler</a></li>

                            @if(!empty($model_id))
                                <li style="display: none"><a href="{{route('site.product-list',[$brand_id,$model_id])}}"></a></li>
                                @endif
                            @if(!empty($brand_id))
                                <li style="display: none"><a href="{{route('site.product-list',[$brand_id,$model_id])}}"></a></li>
                            @endif
                            <li style="display: none"><a href="{{route('site.create-product')}}"></a></li>
                            @if(!empty($product_id))
                                <li style="display: none"><a href="{{route('site.update-product',$product_id)}}"></a></li>
                                @if(!empty($selected))
                                    <li style="display: none"><a href="{{route('site.update-product',[$product_id,$selected])}}"></a></li>

                                @endif
                            @endif
                        </ul>
                    </li>
                    @endif


                    <li><a><i class="fa fa-desktop"></i> UI Elements <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="general_elements.html">General Elements</a></li>
                            <li><a href="media_gallery.html">Media Gallery</a></li>
                            <li><a href="typography.html">Typography</a></li>
                            <li><a href="icons.html">Icons</a></li>
                            <li><a href="glyphicons.html">Glyphicons</a></li>
                            <li><a href="widgets.html">Widgets</a></li>
                            <li><a href="invoice.html">Invoice</a></li>
                            <li><a href="inbox.html">Inbox</a></li>
                            <li><a href="calendar.html">Calendar</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-table"></i> Tables <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="tables.html">Tables</a></li>
                            <li><a href="tables_dynamic.html">Table Dynamic</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-bar-chart-o"></i> Data Presentation <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="chartjs.html">Chart JS</a></li>
                            <li><a href="chartjs2.html">Chart JS2</a></li>
                            <li><a href="morisjs.html">Moris JS</a></li>
                            <li><a href="echarts.html">ECharts</a></li>
                            <li><a href="other_charts.html">Other Charts</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-clone"></i>Layouts <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="fixed_sidebar.html">Fixed Sidebar</a></li>
                            <li><a href="fixed_footer.html">Fixed Footer</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    @if(false)

        <!-- menu profile quick info -->
            <div class="profile clearfix">

                <div class="profile_info">
                    <span>Welcome,</span>
                    <h2>John Doe</h2>
                </div>
            </div>
            <!-- /menu profile quick info -->

            <br />
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="index.html">Dashboard</a></li>
                            <li><a href="index2.html">Dashboard2</a></li>
                            <li><a href="index3.html">Dashboard3</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-edit"></i> Forms <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="form.html">General Form</a></li>
                            <li><a href="form_advanced.html">Advanced Components</a></li>
                            <li><a href="form_validation.html">Form Validation</a></li>
                            <li><a href="form_wizards.html">Form Wizard</a></li>
                            <li><a href="form_upload.html">Form Upload</a></li>
                            <li><a href="form_buttons.html">Form Buttons</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-desktop"></i> UI Elements <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="general_elements.html">General Elements</a></li>
                            <li><a href="media_gallery.html">Media Gallery</a></li>
                            <li><a href="typography.html">Typography</a></li>
                            <li><a href="icons.html">Icons</a></li>
                            <li><a href="glyphicons.html">Glyphicons</a></li>
                            <li><a href="widgets.html">Widgets</a></li>
                            <li><a href="invoice.html">Invoice</a></li>
                            <li><a href="inbox.html">Inbox</a></li>
                            <li><a href="calendar.html">Calendar</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-table"></i> Tables <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="tables.html">Tables</a></li>
                            <li><a href="tables_dynamic.html">Table Dynamic</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-bar-chart-o"></i> Data Presentation <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="chartjs.html">Chart JS</a></li>
                            <li><a href="chartjs2.html">Chart JS2</a></li>
                            <li><a href="morisjs.html">Moris JS</a></li>
                            <li><a href="echarts.html">ECharts</a></li>
                            <li><a href="other_charts.html">Other Charts</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-clone"></i>Layouts <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="fixed_sidebar.html">Fixed Sidebar</a></li>
                            <li><a href="fixed_footer.html">Fixed Footer</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="menu_section">
                <h3>Live On</h3>
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-bug"></i> Additioxxxnal Pages <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="e_commerce.html">E-commerce</a></li>
                            <li><a href="projects.html">Projects</a></li>
                            <li><a href="project_detail.html">Project Detail</a></li>
                            <li><a href="contacts.html">Contacts</a></li>
                            <li><a href="profile.html">Profile</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-windows"></i> Extras <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="page_403.html">403 Error</a></li>
                            <li><a href="page_404.html">404 Error</a></li>
                            <li><a href="page_500.html">500 Error</a></li>
                            <li><a href="plain_page.html">Plain Page</a></li>
                            <li><a href="login.html">Login Page</a></li>
                            <li><a href="pricing_tables.html">Pricing Tables</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-sitemap"></i> Multilevel Menu <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="#level1_1">Level One</a>
                            <li><a>Level One<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li class="sub_menu"><a href="level2.html">Level Two</a>
                                    </li>
                                    <li><a href="#level2_1">Level Two</a>
                                    </li>
                                    <li><a href="#level2_2">Level Two</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="#level1_2">Level One</a>
                            </li>
                        </ul>
                    </li>
                    <li><a href="javascript:void(0)"><i class="fa fa-laptop"></i> Landing Page <span class="label label-success pull-right">Coming Soon</span></a></li>
                </ul>
            </div>

        </div>
        <!-- /sidebar menu -->


        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
            @endif

    </div>
</div>
