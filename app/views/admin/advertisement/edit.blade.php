@extends('admin.layouts.main')

@section('content')

@if(Session::has('message'))
	<div>{{ Session::get('message') }}</div>
@endif
@if($errors->has())
<div>
	<ul>
		@foreach($errors->all() as $error)
			<li> {{ $error }} </li>
		@endforeach
	</ul>
</div>	
@endif

{{ Form::open(array('url'=>'admin/advert/update', 'files'=>true)) }}
{{ Form::hidden('id', $advert->id) }}
<div> {{ Form::label('lbldescription', 'Advertisement ID') }}: {{ Form::label('lbldescription', $advert->id) }} </div>
<div> {{ Form::label('lbldescription', 'Link') }}: {{ Form::text('link', $advert->link) }} </div>
<div>
	{{ Form::file('files', array('id'=>'advert_img', 'accept'=>'image/jpeg')) }}
</div>
{{ Form::hidden('image_data','', array('id'=>'image_data')) }}
<div id="displayArea2"></div>
<div> {{ Form::submit('Save chnages') }} </div>
{{ Form::close() }}


<script type="text/javascript">
window.onload = function(){
	var img_thumb = document.createElement("IMG");
	img_thumb.src = "{{ URL::to('/')."/uploads/adverts/".$advert->image }}";
	img_thumb.height = 200;
	document.getElementById("displayArea2").appendChild(img_thumb);
}
</script>
<script type="text/javascript" src="{{ url() }}/js/admin/photos.js"></script>
<script type="text/javascript" src="{{ url() }}/js/admin/image.js"></script>
@stop