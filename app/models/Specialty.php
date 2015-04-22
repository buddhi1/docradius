<?php

class Specialty extends Eloquent{
	protected $guarded = array();
	public static $rules = array('name' => 'required');
}