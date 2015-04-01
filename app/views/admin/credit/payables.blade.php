@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <form action="{{ route('admin_credits.payable_details') }}" id="payableList"  class="form-inline tasi-form" method="GET">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <header class="panel-heading">
                        Payables  <a class="btn btn-warning btn-xs" href="{{ route('admin_credits.payables') }}" title="Reset"><i class=" icon-refresh"></i></a>
                        <a class="btn btn-primary btn-xs pull-right" data-toggle="modal" href="#partialPaymentModal">Make Partial Payment</a>
                    </header>
                    <div class="dataTables_wrapper form-inline">
                        <br />
                        <div class="col-md-12">
                            <div class="form-group">
                                {{ Form::select('records_per_page', \Config::get('agrivet.records_per_page'), Input::get('records_per_page', 10), ['class' => 'form-control', 'size' => '1', 'onchange' => 'this.form.submit();']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::select('branch', $branches, Input::get('branch', ''), ['class' => 'form-control branch-filter']) }}
                            </div>

                            <div class="form-group">
                                {{ Form::select('supplier', ['Select Supplier'], Input::get('supplier', ''), ['class' => 'form-control supplier-filter', 'size' => '1', 'data-selected' => Input::get('supplier', ''), 'disabled' => true]) }}
                            </div>


                            <button type="submit" class="btn btn-info">Filter</button>
                        </div>


                        <table class="table table-striped table-advance table-hover">
                            <thead>
                            <tr>
                                <th>Supplier Name</th>
                                <th>Location</th>
                                <th>Contact No</th>
                                <th>Total Payables</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @if ($payables)
                                @foreach ($payables as $payable)
                                    <tr>
                                        <td><a href="{{ route('admin_credits.payable_details') }}?branch={{$payable->location}}&supplier={{ $payable->supplier_id }}">{{{ $payable->supplier_name }}}</a></td>
                                        <td>{{{ !$payable->branch? '': $payable->branch->address }}}</td>
                                        <td>{{{ $payable->contact_no }}}</td>
                                        <td>{{{ \Helper::nf($payable->total_payables) }}}</td>
                                        <td>
                                            <a href="{{{ route('admin_credits.payables_edit', $payable->supplier_id) }}}" class="btn btn-primary btn-xs" title="Edit"><i class="icon-pencil"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">{{{ \Lang::get('agrivet.empty', ['name' => 'Suppliers']) }}}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="dataTables_length">
                                    {{{ $totalRows }}} entries</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_filter pagination-sm">
                                    <label>{{ $payables->appends($appends)->links() }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <!-- page end-->
    @include ('admin._partials.payables_form', ['supplier_id' => ''])
@stop