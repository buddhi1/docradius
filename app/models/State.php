<?php

class State extends Eloquent {
	
	protected $guarded = array();
	public static $rules = array('name' => 'required|unique:states');
}