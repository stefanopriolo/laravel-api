@extends('layouts.admin')
@section("content")
	<h1>Lista dei post</h1>
	<small>Release date: {{ $post->published_at?->format("d/m/Y H:i") }}</small>
	<img src="{{ $post->image }}" alt="Post Image>
	<p>{{ $post->body }}</p>
@endsection
