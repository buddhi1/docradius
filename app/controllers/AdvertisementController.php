<?php

class AdvertisementController extends BaseController{
	public function __consturct(){
		$this->beforeFilter('csfr', array('on'=>'post'));
		$this->beforeFilter('admin');
	}

	//views the create page
	public function getCreate(){
		return View::make('admin.advertisement.add');
	}

	//create function
	public function postCreate(){
		$image_data = Input::get('image_data');
		$link = Input::get('link');
		if($link != null || $image_data != null ){
			$advert = new Advertisement;			
			$advert->link = $link;
			if($image_data){
				$img_name = time().'.jpeg';
				$im = imagecreatefromjpeg($image_data);
				imagejpeg($im, 'uploads/adverts/'.$img_name, 70);
				imagedestroy($im);
				$advert->image = $img_name;
			}
			$advert->save();

			return Redirect::to('admin/advert/index')
					->with('message', 'New advertisement added successfully');
		}

		return Redirect::to('admin/advert/create')
				->with('message', 'Neither image nor link should be not null');
	}

	//views all avdertisements
	public function getIndex(){		
		return View::make('admin.advertisement.index')
				->with('adverts', Advertisement::all())
				->with('type', 1);
	}

	//delete advertisement
	public function postDestroy(){
		$advert = Advertisement::find(Input::get('id'));
		
		if($advert){			
			$path = 'uploads/adverts/'.$advert->image;
			if(file_exists($path) && $advert->image != null){
				unlink($path);
			}

			$advert->delete();
			return Redirect::to('admin/advert/index')
					->with('message', 'Advertisement deleted successfully');
		}

		return Redirect::to('admin/advert/index')
				->with('message', 'Something went wrong. Please try again');
	}

	//viws the advertisement edit page
	public function postEdit(){
		$advert = Advertisement::find(Input::get('id'));
		if($advert){
			return View::make('admin.advertisement.edit')
					->with('advert', $advert);
		}

		return Redirect::to('admin/advert/index')
				->with('message', 'Something went wrong. Please try again');
	}

	//update advertisement 
	public function postUpdate(){
		$advert = Advertisement::find(Input::get('id'));		
		if($advert){
			$image_data = Input::get('image_data');
			$path = 'uploads/adverts/'.$advert->image;			
			if($image_data){
				if(file_exists($path)){
					unlink($path);
				}
				$img_name = time().'.jpeg';
				$im = imagecreatefromjpeg($image_data);
				imagejpeg($im, 'uploads/adverts/'.$img_name, 70);
				imagedestroy($im);
				$advert->image = $img_name;
			}
			$link = Input::get('link');
			if($link){
				$advert->link = $link;
			}
			$advert->save();

			return Redirect::to('admin/advert/index')
					->with('message', 'Advertisement edited successfully');
		}

		return Redirect::to('admin/advert/index')
				->with('message', 'Something went wrong. Please try again');
	}
}