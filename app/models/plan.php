<?php

class Plan extends Eloquent
{
	public $table = 'insurance_plans';
	protected $guarded = array();

	public static $rules = array(
			'name'=>'required',
			'insurance_id'=>'required'
		);
}