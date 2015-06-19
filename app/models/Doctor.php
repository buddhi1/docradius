<?php

class Doctor extends Eloquent{
	protected $guarded = array();
	public static $rules = array(
		'name' => 'required',
		'specialties'=>'required',
		'reg_no'=>'required',
		);
}