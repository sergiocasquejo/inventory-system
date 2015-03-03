@extends('admin.layout_master')

@section('content')
<!--state overview start-->
<div class="row state-overview">
    <div class="col-lg-3 col-sm-6">
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
                <i class="icon-tags"></i>
            </div>
            <div class="value">
                <h1>{{{ \Helper::nf($total_sales) }}}</h1>
                <p>Sales</p>
            </div>
        </section>
    </div>
    <div class="col-lg-3 col-sm-6">
        <section class="panel">
            <div class="symbol yellow">
                <i class="icon-shopping-cart"></i>
            </div>
            <div class="value">
                <h1>{{{ \Helper::nf($total_expense) }}}</h1>
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
                <h1>{{{ \Helper::nf($earning->total_amount - $total_expense) }}}</h1>
                <p>Total Profit</p>
            </div>
        </section>
    </div>
</div>
<!--state overview end-->

<div class="row">
    <div class="col-lg-12">
        <!--custom chart start-->
        <div class="border-head">
            <h3>Earning Graph</h3>
        </div>
        <div class="custom-bar-chart">
            <div class="bar">
                <div class="title">JAN</div>
                <div class="value tooltips" data-original-title="{{{ $earning->Total_Jan }}}%" data-toggle="tooltip" data-placement="top">{{{ $earning->Total_Jan }}}%</div>
            </div>
            <div class="bar doted">
                <div class="title">FEB</div>
                <div class="value tooltips" data-original-title="{{{ $earning->Total_Feb }}}%" data-toggle="tooltip" data-placement="top">{{{ $earning->Total_Feb }}}%</div>
            </div>
            <div class="bar ">
                <div class="title">MAR</div>
                <div class="value tooltips" data-original-title="{{{ $earning->Total_Mar }}}%" data-toggle="tooltip" data-placement="top">{{{ $earning->Total_Mar }}}%</div>
            </div>
            <div class="bar doted">
                <div class="title">APR</div>
                <div class="value tooltips" data-original-title="{{{ $earning->Total_Apr }}}%" data-toggle="tooltip" data-placement="top">{{{ $earning->Total_Apr }}}%</div>
            </div>
            <div class="bar">
                <div class="title">MAY</div>
                <div class="value tooltips" data-original-title="{{{ $earning->Total_May }}}%" data-toggle="tooltip" data-placement="top">{{{ $earning->Total_May }}}%</div>
            </div>
            <div class="bar doted">
                <div class="title">JUN</div>
                <div class="value tooltips" data-original-title="{{{ $earning->Total_Jun }}}%" data-toggle="tooltip" data-placement="top">{{{ $earning->Total_Jun }}}%</div>
            </div>
            <div class="bar">
                <div class="title">JUL</div>
                <div class="value tooltips" data-original-title="{{{ $earning->Total_Jul }}}%" data-toggle="tooltip" data-placement="top">{{{ $earning->Total_Jul }}}%</div>
            </div>
            <div class="bar doted">
                <div class="title">AUG</div>
                <div class="value tooltips" data-original-title="{{{ $earning->Total_Aug }}}%" data-toggle="tooltip" data-placement="top">{{{ $earning->Total_Aug }}}%</div>
            </div>
            <div class="bar ">
                <div class="title">SEP</div>
                <div class="value tooltips" data-original-title="{{{ $earning->Total_Sep }}}%" data-toggle="tooltip" data-placement="top">{{{ $earning->Total_Sep }}}%</div>
            </div>
            <div class="bar doted">
                <div class="title">OCT</div>
                <div class="value tooltips" data-original-title="{{{ $earning->Total_Oct }}}%" data-toggle="tooltip" data-placement="top">{{{ $earning->Total_Oct }}}%</div>
            </div>
            <div class="bar ">
                <div class="title">NOV</div>
                <div class="value tooltips" data-original-title="{{{ $earning->Total_Nov }}}%" data-toggle="tooltip" data-placement="top">{{{ $earning->Total_Nov }}}%</div>
            </div>
            <div class="bar doted">
                <div class="title">DEC</div>
                <div class="value tooltips" data-original-title="{{{ $earning->Total_Dec }}}%" data-toggle="tooltip" data-placement="top">{{{ $earning->Total_Dec }}}%</div>
            </div>
        </div>
        <!--custom chart end-->
    </div>
   
</div>

<div class="row">
     <div class="col-lg-6">
        <!--new earning start-->
        <div class="panel terques-chart">
            <div class="panel-body chart-texture">
                <div class="chart">
                    <div class="heading">
                        <span>This Week</span>
                        <strong>$ 57,00 | 15%</strong>
                    </div>
                    <div id="sales" class="sparkline" data-type="line" data-resize="true" data-height="75" data-width="90%" data-line-width="1" data-line-color="#fff" data-spot-color="#fff" data-fill-color="" data-highlight-line-color="#fff" data-spot-radius="4" data-data="[10,100,50,20,200,100,564,123,890,564,455]"></div>
                    <div id="expenses" class="sparkline" data-type="line" data-resize="true" data-height="75" data-width="90%" data-line-width="1" data-line-color="#EF6F66" data-spot-color="#EF6F66" data-fill-color="" data-highlight-line-color="#EF6F66" data-spot-radius="4" data-data="[200,135,667,333,526,996,564,123,890,564,455]"></div>
                    <div id="credits" class="sparkline" data-type="line" data-resize="true" data-height="75" data-width="90%" data-line-width="1" data-line-color="#4d90fe" data-spot-color="#4d90fe" data-fill-color="" data-highlight-line-color="#4d90fe" data-spot-radius="4" data-data="[100,20,33,12,526,100,564,15,890,54,200]"></div>
                </div>
            </div>
            <div class="chart-tittle">
                <span class="title">New Earning</span>
                <span class="value">
                    <a href="#sales" class="active">Sales</a>
                    |
                    <a href="#expenses">Expenses</a>
                    |
                    <a href="#credits">Credits</a>
                </span>
            </div>
        </div>
        <!--new earning end-->
    </div>

    <div class="col-lg-6">    
        <!--total earning start-->
        <div class="panel green-chart">
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
        </div>
        <!--total earning end-->
    </div>
</div>
@stop