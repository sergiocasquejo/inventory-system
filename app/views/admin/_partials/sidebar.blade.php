<!--sidebar start-->
<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu">
            <li class="active">
                <a class="" href="{{ route('admin_dashboard.index') }}">
                    <i class="icon-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            @if (Confide::user()->isAdmin())
            <li class="sub-menu">
                <a href="javascript:;" class="">
                    <i class="icon-home"></i>
                    <span>Branches</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="{{ route('admin_branches.index') }}">All</a></li>
                    <li><a class="" href="{{ route('admin_branches.create') }}">Add New</a></li>
                </ul>
            </li>
            
            
            <li class="sub-menu">
                <a href="javascript:;" class="">
                    <i class="icon-bar-chart"></i>
                    <span>Catelogue</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="{{ route('admin_products.index') }}">Products</a></li>
                    <li><a class="" href="{{ route('admin_brands.index') }}">Brands</a></li>
                    <li><a class="" href="{{ route('admin_categories.index') }}">Categories</a></li>
                </ul>
            </li>
            @endif
            <li class="sub-menu">
                <a href="javascript:;" class="">
                    <i class="icon-bar-chart"></i>
                    <span>Sales</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="{{ route('admin_sales.index') }}">All</a></li>
                    <li><a class="" href="{{ route('admin_sales.create') }}">Add New</a></li>
                </ul>
            </li>

            <li class="sub-menu">
                <a href="javascript:;" class="">
                    <i class="icon-money"></i>
                    <span>Expenses</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="{{ route('admin_expenses.index') }}">All</a></li>
                    <li><a class="" href="{{ route('admin_expenses.create') }}">Add New</a></li>
                </ul>
            </li>

            <li class="sub-menu">
                <a href="javascript:;" class="">
                    <i class="icon-money"></i>
                    <span>Credits</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="{{ route('admin_credits.index') }}">All</a></li>
                    <li><a class="" href="{{ route('admin_credits.create') }}">Add New</a></li>
                </ul>
            </li>

            @if (Confide::user()->isAdmin())
            

            <li class="sub-menu">
                <a href="javascript:;" class="">
                    <i class="icon-user"></i>
                    <span>Users</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="{{ route('admin_users.index') }}">All</a></li>
                    <li><a class="" href="{{ route('admin_users.create') }}">Add New</a></li>
                </ul>
            </li>

            <li class="sub-menu">
                <a href="javascript:;" class="">
                    <i class="icon-cogs"></i>
                    <span>Settings</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="#">Unit of measures</a></li>
                </ul>
            </li>
            @endif

        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->