<?php

class PlanController extends BaseController{

	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
	}

	//displays the add page
	public function getCreate(){
		return View::make('admin.plan.add')
					->with('insurances', Insurance::lists('name', 'id'));
	}

	//create function
	public function postCreate(){
		$validator = Validator::make(Input::all(), Plan::$rules);

		if($validator->passes()){
			$ins_plan = new Plan;
			$ins_plan->name = Input::get('name');
			$ins_plan->insurance_id = Input::get('insurance_id');
			$ins_plan->save();

			return Redirect::to('admin/insurancePlan')
						->with('message', 'The insurance plan added successfully');
		}
		return Redirect::to('admin/insurancePlan/create')
					->with('message', 'Following errors occurred')
					->withErrors($validator)
					->withInput();
	}

	//display index page
	public function getIndex(){
		$plan = DB::table('insurance_plans')
					->leftJoin('insurances', 'insurances.id', '=', 'insurance_plans.insurance_id')
					->select('insurance_plans.id as id', 'insurance_plans.name as name', 'insurances.name as insurance')
					->paginate(10);

		return View::make('admin.plan.index')
					->with('plans', $plan);
	}

	//displays the edit page
	public function postEdit(){
		$plan = Plan::find(Input::get('id'));

		if($plan){
			return View::make('admin.plan.edit')
						->with('insurances', Insurance::lists('name', 'id'))
						->with('plan', $plan);
		}
		return Redirect::to('admin/insurancePlan')
					->with('message', 'Something went wrong. Please try again.');
	}

	//update function
	public function postUpdate(){
		$ins_plan = Plan::find(Input::get('id'));
		if($ins_plan){
			$validator = Validator::make(Input::all(), Plan::$rules);

			if($validator->passes()){
				$ins_plan->name = Input::get('name');
				$ins_plan->insurance_id = Input::get('insurance_id');
				$ins_plan->save();

				return Redirect::to('admin/insurancePlan')
							->with('message', 'The insurance plan added successfully');
			}
			return Redirect::to('admin/insurancePlan')
						->with('message', 'Following errors occurred')
						->withErrors($validator);
			}

			return Redirect::to('admin/insurancePlan')
						->with('message', 'Something went wrong. Please try again.');
	}

	//delete function
	public function postDestroy(){
		$plan = Plan::find(Input::get('id'));

		if($plan){
			$plan->delete();
			return Redirect::to('admin/insurancePlan');
		}
		return Redirect::to('admin/insurancePlan')
						->with('message', 'Something went wrong. Please try again.');
		
	}
}