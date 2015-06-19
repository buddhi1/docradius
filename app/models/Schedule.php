<?php

class Schedule extends Eloquent{
	protected $guarded = array();
	public static $rules = array(
			'start_time'=>'required',
			'end_time'=>'required',
			'no_of_patients'=>'required',
			'day'=>'required',
			'doctor_id'=>'required',
		);
}