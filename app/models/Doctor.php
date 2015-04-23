<?php

class Doctor extends Eloquent{
	protected $guarded = array();
	public static $rules = array(
		'name' => 'required',
		//'hospitals'=>'required',
		//'specialties'=>'required',
		//'town_id'=>'required',
		);
}