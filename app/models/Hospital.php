<?php

class Hospital extends Eloquent{
	protected $guarded = array();
	public static $rules = array(
			'name' => 'required',
			'address'=>'required',
			'street'=>'required',
			'town_id'=>'required',
			'insurances'=>'required'
		);
}