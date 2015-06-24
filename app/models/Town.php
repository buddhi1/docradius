<?php

class Town extends Eloquent {
	
	protected $guarded = array();
	public static $rules = array('name' => 'required|unique:towns', 'lga_id'=>'required');
}