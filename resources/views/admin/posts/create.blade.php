@extends('layouts.admin')
@section("content")
	<h1>Nuovo post</h1>
	<form action="{{ route('admin.posts.store') }}" method="POST">
		@csrf()
			<div><label><input></div>
			<div><label><input></div>
			<div><label><input></div>
			<button>Crea</button>
	</form>
@endsection