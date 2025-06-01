<!--
*
*  INSPINIA - Responsive Admin Theme
*  version 2.7
*
-->

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ (getUserSetting('project_name') ?? config('app.name')) . ' - ' . (getUserSetting('project_tagline') ?? '') }}</title>

    <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <!-- Toastr style -->
    <link href="{{ asset('backend/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">

    <!-- Gritter -->
    <link href="{{ asset('backend/js/plugins/gritter/jquery.gritter.css') }}" rel="stylesheet">

    <link href="{{ asset('backend/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('styles')
</head>

<body>
    <div id="wrapper">

        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <span>
                                <img alt="image" class="img-circle" src="{{ asset('backend/img/profile_small.jpg') }}" />
                            </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->name }}</strong> </span> <span class="text-muted text-xs block">{{ auth()->user()->getRoleNames()->first() }}<b class="caret"></b></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="{{ url('profile') }}">Profile</a></li>
                                <li class="divider"></li>
                                <li><a href="#" onclick="event.preventDefault(); triggerLogout();">Logout</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            +
                        </div>
                    </li>

                    <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> <span class="nav-label">Dashboard</span></a>
                    </li>

                    <li class="{{ request()->is('admin/customers*') ? 'active' : '' }}">
                        <a href="{{ route('customers.index') }}"><i class="fa fa-users"></i> <span class="nav-label">Customers</span></a>
                    </li>

                    <li class="{{ request()->is('admin/guarantors*') ? 'active' : '' }}">
                        <a href="{{ route('guarantors.index') }}"><i class="fa fa-user-plus"></i> <span class="nav-label">Guarantors</span></a>
                    </li>

                    <li class="{{ request()->is('admin/products*') ? 'active' : '' }}">
                        <a href="{{ route('products.index') }}"><i class="fa fa-cube"></i> <span class="nav-label">Products</span></a>
                    </li>

                    <li class="{{ request()->is('admin/recovery-officers*') ? 'active' : '' }}">
                        <a href="{{ route('recovery-officers.index') }}"><i class="fa fa-user-circle-o"></i> <span class="nav-label">Recovery Officers</span></a>
                    </li>

                    <li class="{{ request()->is('admin/purchases*') ? 'active' : '' }}">
                        <a href="{{ route('purchases.index') }}"><i class="fa fa-shopping-cart"></i> <span class="nav-label">Purchases</span></a>
                    </li>

                    <li class="{{ request()->is('admin/installments*') ? 'active' : '' }}">
                        <a href="{{ route('installments.index') }}"><i class="fa fa-credit-card"></i> <span class="nav-label">Installments</span></a>
                    </li>

                    <!-- User Management section with better icons -->
                    <li class="{{ request()->is('profile') || request()->routeIs('admin.settings') ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-user-circle"></i> <span class="nav-label">User Management</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level {{ request()->is('profile') || request()->routeIs('admin.settings') ? 'collapse' : '' }}">
                            <li class="{{ request()->is('profile') ? 'active' : '' }}">
                                <a href="{{ url('profile') }}"><i class="fa fa-user"></i> Profile</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                                <a href="{{ route('admin.settings') }}"><i class="fa fa-cogs"></i> General Setting</a>
                            </li>
                        </ul>
                    </li>

                    <!-- Settings section with better icons -->
                    <li class="{{ request()->routeIs('admin.users') || request()->routeIs('admin.roles') || request()->routeIs('role-assignment') || request()->routeIs('permissions') ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-cog"></i> <span class="nav-label">System Settings</span> <span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level {{ request()->routeIs('admin.users') || request()->routeIs('admin.roles') || request()->routeIs('role-assignment') || request()->routeIs('permissions') ? 'collapse' : '' }}">
                            <li class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                                <a href="{{ route('admin.users') }}"><i class="fa fa-users"></i> Users</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.roles') ? 'active' : '' }}">
                                <a href="{{ route('admin.roles') }}"><i class="fa fa-shield"></i> Roles</a>
                            </li>
                            <li class="{{ request()->routeIs('role-assignment') ? 'active' : '' }}">
                                <a href="{{ route('role-assignment') }}"><i class="fa fa-user-secret"></i> Role Assignments</a>
                            </li>
                            <li class="{{ request()->routeIs('permissions') ? 'active' : '' }}">
                                <a href="{{ route('permissions') }}"><i class="fa fa-lock"></i> Permissions</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#" onclick="event.preventDefault(); triggerLogout();"><i class="fa fa-sign-out"></i> <span class="nav-label">Logout</span></a>
                    </li>
                </ul>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg">

            <div class="row border-bottom">
                <nav class="navbar navbar-static-top " role="navigation" style="margin-bottom: 0;">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#"><i class="fa fa-bars"></i> </a>
                        <form role="search" class="navbar-form-custom" action="search_results.html">
                            <div class="form-group">
                                <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search" />
                            </div>
                        </form>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">Welcome {{ Auth::user()->name }}</span>
                        </li>

                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#"> <i class="fa fa-bell"></i> <span class="label label-primary">8</span> </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="mailbox.html">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="profile.html">
                                        <div>
                                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                            <span class="pull-right text-muted small">12 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="grid_options.html">
                                        <div>
                                            <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a href="notifications.html">
                                            <strong>See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#" onclick="event.preventDefault(); triggerLogout();"> <i class="fa fa-sign-out"></i> Log out </a>
                        </li>
                        <li class="dropdown">
                            <a class="right-sidebar-toggle dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-tasks"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="mailbox.html">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> test
                                            <span class="pull-right text-muted small">4 minutes ago</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>

                @yield('content')

            <div class="footer">
                <div class="pull-right"></div>
                <div><strong>Copyright</strong> {{ (getUserSetting('project_name') ?? config('app.name')) }} &copy; {{ date('Y') }}</div>
            </div>

        </div>

        <form method="POST" action="{{ route('logout') }}" style="display: none;" class="sidebarlogout">
            @csrf
        </form>

    </div>

    <!-- Mainly scripts -->
    <!-- FIXED: Use only ONE jQuery version - using the newer CDN version for better compatibility -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap and other plugins -->
    <script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/dataTables/datatables.min.js') }}"></script>
    
    <!-- DataTables CDN -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <!-- Flot -->
    <script src="{{ asset('backend/js/plugins/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/flot/jquery.flot.spline.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/flot/jquery.flot.pie.js') }}"></script>

    <!-- Peity -->
    <script src="{{ asset('backend/js/plugins/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('backend/js/demo/peity-demo.js') }}"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('backend/js/inspinia.js') }}"></script>
    <script src="{{ asset('backend/js/plugins/pace/pace.min.js') }}"></script>

    <!-- jQuery UI -->
    <script src="{{ asset('backend/js/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

    <!-- GITTER -->
    <script src="{{ asset('backend/js/plugins/gritter/jquery.gritter.min.js') }}"></script>

    <!-- Sparkline -->
    <script src="{{ asset('backend/js/plugins/sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Sparkline demo data  -->
    <script src="{{ asset('backend/js/demo/sparkline-demo.js') }}"></script>

    <!-- ChartJS-->
    <script src="{{ asset('backend/js/plugins/chartJs/Chart.min.js') }}"></script>

    <!-- Toastr -->
    <script src="{{ asset('backend/js/plugins/toastr/toastr.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Debug: Check if jQuery is loaded properly
            console.log('jQuery version:', $.fn.jquery);

            var d1 = [[1262304000000, 6], [1264982400000, 3057], [1267401600000, 20434], [1270080000000, 31982], [1272672000000, 26602], [1275350400000, 27826], [1277942400000, 24302], [1280620800000, 24237], [1283299200000, 21004], [1285891200000, 12144], [1288569600000, 10577], [1291161600000, 10295]];
            var d2 = [[1262304000000, 5], [1264982400000, 200], [1267401600000, 1605], [1270080000000, 6129], [1272672000000, 11643], [1275350400000, 19055], [1277942400000, 30062], [1280620800000, 39197], [1283299200000, 37000], [1285891200000, 27000], [1288569600000, 21000], [1291161600000, 17000]];

            var data1 = [
                { label: "Data 1", data: d1, color: '#17a084'},
                { label: "Data 2", data: d2, color: '#127e68' }
            ];
            
            // Only plot if the element exists
            if ($("#flot-chart1").length) {
                $.plot($("#flot-chart1"), data1, {
                    xaxis: {
                        tickDecimals: 0
                    },
                    series: {
                        lines: {
                            show: true,
                            fill: true,
                            fillColor: {
                                colors: [{
                                    opacity: 1
                                }, {
                                    opacity: 1
                                }]
                            },
                        },
                        points: {
                            width: 0.1,
                            show: false
                        },
                    },
                    grid: {
                        show: false,
                        borderWidth: 0
                    },
                    legend: {
                        show: false,
                    }
                });
            }

            var lineData = {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "Example dataset",
                        backgroundColor: "rgba(26,179,148,0.5)",
                        borderColor: "rgba(26,179,148,0.7)",
                        pointBackgroundColor: "rgba(26,179,148,1)",
                        pointBorderColor: "#fff",
                        data: [48, 48, 60, 39, 56, 37, 30]
                    },
                    {
                        label: "Example dataset",
                        backgroundColor: "rgba(220,220,220,0.5)",
                        borderColor: "rgba(220,220,220,1)",
                        pointBackgroundColor: "rgba(220,220,220,1)",
                        pointBorderColor: "#fff",
                        data: [65, 59, 40, 51, 36, 25, 40]
                    }
                ]
            };

            var lineOptions = {
                responsive: true
            };

            // Only create chart if the element exists
            if (document.getElementById("lineChart")) {
                var ctx = document.getElementById("lineChart").getContext("2d");
                new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});
            }
        });
    </script>
    
    <script>
        //logout
        function triggerLogout() {
            const logoutForm = document.querySelector('.sidebarlogout');
            if (logoutForm) {
                logoutForm.submit();
            } else {
                console.error('Logout form not found!');
            }
        }
    </script>

    @stack('script')
</body>
</html>