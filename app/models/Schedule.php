<?php

class Schedule extends Eloquent{
	protected $guarded = array();
	public static $rules = array(
			'start_time'=>'required',
			'end_time'=>'required',
			'no_of_patients'=>'required',
			'hospital'=>'required',
			'day'=>'required',
			'town_id'=>'required'
		);
}