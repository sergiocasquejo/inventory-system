<?php

class Branch extends Eloquent {
	protected $table = 'branches';
	protected $primaryKey = 'id';


	public function users() {
		return $this->hasMany('User');
	}
}