@extends('layouts.master')
@section('content')
<div class="content" style="margin:10px 5px 15px 20px;">
	<div class="title m-b-md" >
		Simple Translation Memory
	</div>
	<div class="container">
		<div class="row">
		{!! Form::open(['url' => '/post', 'class' => 'form-horizontal']) !!}
			<div class="col-sm">
				<div class="form-group">
				  <label for="comment">You can write text or word to be translated:</label>
				  <textarea class="form-control" rows="5" id="comment" name="text"></textarea>
				</div>
			</div>
			<div class="col-sm">
				<div class="form-group">
				  <label for="sel1">Select Language From:</label>
				  <select class="form-control" id="sel1" name="languageFrom">
				    <option value="option">Options</option>
				    @foreach($languages as $language)
				    	<option value="{{$language}}">{{$language}}</option>
				    @endforeach
				  </select>
				</div>
			</div>
			<div class="col-sm">
				<div class="form-group">
				  <label for="sel1">Select Language To:</label>
				  <select class="form-control" id="sel1" name="languageTo">
				    <option value="option">Options</option>
				    @foreach($languages as $language)
				    	<option value="{{$language}}">{{$language}}</option>
				    @endforeach
				  </select>
				</div>
			</div>
			<div class="col-sm">
				<button type="submit" class="btn">Translate</button>
			</div>
		 {!! Form::close()  !!}
		</div>
		@if(isset($translatedText))
		{{dd($translatedText)}}
		<div class="col-sm">
				<div class="form-group">
				  <label for="comment">Translated Text/Word is:</label>
				  <textarea class="form-control" rows="5" id="comment" name="text">
				  	{{$translatedText}}
				  </textarea>
				</div>
		</div>
		@endif
	</div>
</div>
@endsection