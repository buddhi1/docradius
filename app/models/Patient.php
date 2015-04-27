<?php

class Patient extends Eloquent {

	protected $guarded = array();
	public static $rules = array('name' => 'required',
								'town_id' => 'required',
								'sex' => 'required',
								'tp' => 'required');
}