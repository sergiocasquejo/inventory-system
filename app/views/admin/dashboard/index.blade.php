@extends('admin.layout_master')

@section('content')
<!--state overview start-->
<div class="row state-overview">
    <div class="col-lg-2 col-sm-5">
        <section class="panel">
            <div class="symbol terques">
                <i class="icon-user"></i>
            </div>
            <div class="value">
                <h1>{{{ $total_users }}}</h1>
                <p>Users</p>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="panel">
            <div class="symbol red">
                <i class="icon-bar-chart"></i>
            </div>
            <div class="value">
                <strong>{{{ \Helper::nf($total_sales) }}}</strong>
                <p>Cash on Hand</p>
            </div>
        </section>
    </div>
    <div class="col-lg-2 col-sm-5">
        <section class="panel">
            <div class="symbol yellow">
               <i class="icon-bar-chart"></i>
            </div>
            <div class="value">
                <strong>{{{ \Helper::nf($total_credits) }}}</strong>
                <p>Credits</p>
            </div>
        </section>
    </div>
    <div class="col-lg-2 col-sm-5">
        <section class="panel">
            <div class="symbol terques">
                <i class="icon-bar-chart"></i>
            </div>
            <div class="value">
                <strong>{{{ \Helper::nf($total_expense) }}}</strong>
                <p>Expense</p>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="panel">
            <div class="symbol blue">
                <i class="icon-bar-chart"></i>
            </div>
            <div class="value">
                <strong>{{{ \Helper::nf(($total_sales + $total_credits) - $total_expense) }}}</strong>
                <p>Total Profit</p>
            </div>
        </section>
    </div>
</div>
<!--state overview end-->

<div class="row">
    <div class="col-lg-8">
        <!--custom chart start-->
        <div class="border-head">
            <h3>Earning Graph</h3>
        </div>
        <div class="custom-bar-chart">
            <div class="bar">
                <div class="title">JAN</div>
                <div class="value tooltips" data-original-title="{{{ !$earning?0:$earning->Total_Jan }}}%" data-toggle="tooltip" data-placement="top">{{{ !$earning?0:$earning->Total_Jan }}}%</div>
            </div>
            <div class="bar doted">
                <div class="title">FEB</div>
                <div class="value tooltips" data-original-title="{{{ !$earning?0:$earning->Total_Feb }}}%" data-toggle="tooltip" data-placement="top">{{{ !$earning?0:$earning->Total_Feb }}}%</div>
            </div>
            <div class="bar ">
                <div class="title">MAR</div>
                <div class="value tooltips" data-original-title="{{{ !$earning?0:$earning->Total_Mar }}}%" data-toggle="tooltip" data-placement="top">{{{ !$earning?0:$earning->Total_Mar }}}%</div>
            </div>
            <div class="bar doted">
                <div class="title">APR</div>
                <div class="value tooltips" data-original-title="{{{ !$earning?0:$earning->Total_Apr }}}%" data-toggle="tooltip" data-placement="top">{{{ !$earning?0:$earning->Total_Apr }}}%</div>
            </div>
            <div class="bar">
                <div class="title">MAY</div>
                <div class="value tooltips" data-original-title="{{{ !$earning?0:$earning->Total_May }}}%" data-toggle="tooltip" data-placement="top">{{{ !$earning?0:$earning->Total_May }}}%</div>
            </div>
            <div class="bar doted">
                <div class="title">JUN</div>
                <div class="value tooltips" data-original-title="{{{ !$earning?0:$earning->Total_Jun }}}%" data-toggle="tooltip" data-placement="top">{{{ !$earning?0:$earning->Total_Jun }}}%</div>
            </div>
            <div class="bar">
                <div class="title">JUL</div>
                <div class="value tooltips" data-original-title="{{{ !$earning?0:$earning->Total_Jul }}}%" data-toggle="tooltip" data-placement="top">{{{ !$earning?0:$earning->Total_Jul }}}%</div>
            </div>
            <div class="bar doted">
                <div class="title">AUG</div>
                <div class="value tooltips" data-original-title="{{{ !$earning?0:$earning->Total_Aug }}}%" data-toggle="tooltip" data-placement="top">{{{ !$earning?0:$earning->Total_Aug }}}%</div>
            </div>
            <div class="bar ">
                <div class="title">SEP</div>
                <div class="value tooltips" data-original-title="{{{ !$earning?0:$earning->Total_Sep }}}%" data-toggle="tooltip" data-placement="top">{{{ !$earning?0:$earning->Total_Sep }}}%</div>
            </div>
            <div class="bar doted">
                <div class="title">OCT</div>
                <div class="value tooltips" data-original-title="{{{ !$earning?0:$earning->Total_Oct }}}%" data-toggle="tooltip" data-placement="top">{{{ !$earning?0:$earning->Total_Oct }}}%</div>
            </div>
            <div class="bar ">
                <div class="title">NOV</div>
                <div class="value tooltips" data-original-title="{{{ !$earning?0:$earning->Total_Nov }}}%" data-toggle="tooltip" data-placement="top">{{{ !$earning?0:$earning->Total_Nov }}}%</div>
            </div>
            <div class="bar doted">
                <div class="title">DEC</div>
                <div class="value tooltips" data-original-title="{{{ !$earning?0:$earning->Total_Dec }}}%" data-toggle="tooltip" data-placement="top">{{{ !$earning?0:$earning->Total_Dec }}}%</div>
            </div>
        </div>
        <!--custom chart end-->
    </div>
    <div class="col-lg-4">
        <!--new earning start-->
        <div class="border-head">
            <h3>This Week</h3>
        </div>
        <div class="panel terques-chart">
            <div class="panel-body chart-texture">
                <div class="chart">
                    <div class="heading">
                        <span>This Week</span>
                        <!--<strong>$ 57,00 | 15%</strong>-->
                    </div>
                    <div id="sales" class="sparkline" data-type="line" data-resize="true" data-height="75" data-width="90%" data-line-width="1" data-line-color="#A9D86E" data-spot-color="#A9D86E" data-fill-color="" data-highlight-line-color="#A9D86E" data-spot-radius="4" data-data="[{{ implode(',', $weekly_sales) }}]"></div>
                    <div id="expenses" class="sparkline" data-type="line" data-resize="true" data-height="75" data-width="90%" data-line-width="1" data-line-color="#FCB322" data-spot-color="#FCB322" data-fill-color="" data-highlight-line-color="#FCB322" data-spot-radius="4" data-data="[{{ implode(',', $weekly_expenses) }}]"></div>
                </div>
            </div>
            <div class="chart-tittle">
                <span class="title">New Earning</span>
                <span class="value">
                    <a href="#sales"> <span class="badge bg-success">&nbsp;</span> Sales</a>
                    |
                    <a><span class="badge bg-warning">&nbsp;</span> Expenses</a>
                </span>
            </div>
        </div>
        <!--new earning end-->

        <!--total earning start-->
        <!--<div class="panel green-chart">
            <div class="panel-body">
                <div class="chart">
                    <div class="heading">
                        <span>June</span>
                        <strong>23 Days | 65%</strong>
                    </div>
                    <div id="barchart"></div>
                </div>
            </div>
            <div class="chart-tittle">
                <span class="title">Total Earning</span>
                <span class="value">$, 76,54,678</span>
            </div>
        </div>-->
        <!--total earning end-->
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        
    </div>
    <div class="col-lg-6">
        
    </div>
</div>
@stop