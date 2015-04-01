<!-- Modal -->
<div class="modal fade" id="partialPaymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Partial Payment</h4>
            </div>
            <div class="modal-body">
                <form id="payablesPayForm">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="form-group">
                        <label for="customer">Supplier</label>
                        {{ Form::select('supplier', $suppliers, Input::old('supplier', $supplier_id), ['class' => 'form-control'])  }}
                    </div>
                    <div class="form-group">
                        <label for="">Total Payables</label>
                        <strong class="total-payables">Php 0.00</strong>
                    </div>
                    <div class="form-group">
                        <label for="amount">Partial Payment</label>
                        <input name="amount" type="number" step="any" class="form-control" id="amount" placeholder="Enter amount" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Comments</label>
                        <textarea name="comments" class="form-control" placeholder="Enter Comments">Partial payment</textarea>
                    </div>
                </form>
                <p class="alert-message alert hide"></p>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button type="submit" class="btn btn-success" id="payPayables" type="button">Pay</button>
            </div>

        </div>
    </div>
</div>