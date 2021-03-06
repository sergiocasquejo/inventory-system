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
                    <i class="icon-truck"></i>
                    <span>Suppliers</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="{{ route('admin_suppliers.index') }}">All</a></li>
                    <li><a class="" href="{{ route('admin_suppliers.create') }}">Add New</a></li>
                </ul>
            </li>
            
            
            <li class="sub-menu">
                <a href="javascript:;" class="">
                    <i class="icon-shopping-cart"></i>
                    <span>Products</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="{{ route('admin_products.index') }}">All Products</a></li>
                    <li><a class="" href="{{ route('admin_brands.index') }}">Brands</a></li>
                    <li><a class="" href="{{ route('admin_categories.index') }}">Categories</a></li>
                </ul>
            </li>

            <li class="sub-menu">
                <a href="javascript:;" class="">
                    <i class="icon-dropbox"></i>
                    <span>Stocks</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="{{ route('admin_stocks.index') }}">All</a></li>
                    <li><a class="" href="{{ route('admin_stocks.create') }}">Add New</a></li>
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
                    <li><a class="" href="{{ route('admin_customers.index') }}">Customers</a></li>
                    @if (Confide::user()->isAdmin())
                    <li><a class="" href="{{ route('admin_credits.payables') }}">Payables</a></li>
                    @endif
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
                <a href="{{ route('admin_reports.index') }}" class="">
                    <i class="icon-table"></i>
                    <span>Reports</span>
                </a>
            </li>

            <li class="sub-menu">
                <a href="javascript:;" class="">
                    <i class="icon-cogs"></i>
                    <span>Settings</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub">
                    <li><a class="" href="{{ route('admin_uoms.index') }}">Unit of measures</a></li>
                </ul>
            </li>
            @endif

        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->