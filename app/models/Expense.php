<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Expense extends Eloquent {
	use SoftDeletingTrait;
	protected $table = 'expenses';
	protected $primaryKey = 'expense_id';


    protected $dates = ['deleted_at'];

	public function user() {
		return $this->belongsTo('User', 'encoded_by');
	}
}