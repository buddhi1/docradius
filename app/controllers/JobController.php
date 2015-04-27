<?php

class JobController extends BaseController {

	public function __construct() {

		$this->beforeFilter('csrf', array('on' => 'post'));
	}

	public function getIndex() {
		// display all the jobs available

		return View::make('member.job.index')
			->with('jobs', Job::all());
	}

	public function getCreate() {
		// display the create form for a Job

		return View::make('member.job.add');
	}

	public function postCreate() {
		// save health job in the database

		$title = Input::get('title');
		$des = Input::get('des');
		$email_admin = Input::get('email');
		$email = Auth::user()->email;
		$user_id = Auth::user()->id;
		
		$job = new Job;

		if($job) {

			$job->title = $title;
			$job->description = $des;
			if(!$email_admin) {
				$job->email = $email;
			} else {
				$job->email = $email_admin;
			}
			$job->user_id = $user_id;
			if(Auth::user()->type === 1) {

				$job->active = 1;
			}

			if($job->save()) {

				return Redirect::To('member/job/create')
					->with('message', 'Health Job is Created');
			}
		}
	}

	public function postDestroy() {
		// delete a job from the database //admin

		$id = Input::get('id');

		$job = Job::find($id);

		if($job) {

			$job->delete();
			return Redirect::To('member/job')
			->with('message', 'Job Deleted Successfully');
		}

		return Redirect::To('member/job')
			->with('message', 'Error Occured');
	}

	public function postMakejobactive() {
		// make a health job active //admin

		$id = Input::get('id');

		if(Auth::user()->type === 1) {

			$job = Job::find($id);

			if($job->active === 0) {

				$job->active = 1;
			} else {
				$job->active = 0;
			}
			

			if($job->save()) {

				return Redirect::To('member/job');
			}
			return Redirect::To('member/job')
				->with('message', 'Error Occured');
		}
		return Redirect::To('member/job')
			->with('message', 'You are not authorized to perform this action');
	}
}