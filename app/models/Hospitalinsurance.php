<?php

class Hospitalinsurance extends Eloquent{
	protected $guarded = array();
	public static $rules = array(
			'hospital_id' => 'required',
			'insurance_id'=>'required',
		);
}