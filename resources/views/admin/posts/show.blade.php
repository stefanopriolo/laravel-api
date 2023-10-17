@extends('layouts.app')

@section("content")

<div class="container">
  <div class="navbar my-3">
    <a href="{{ route("admin.posts.index") }}" class="btn btn-link">
      <i class="fas fa-chevron-left"></i> Torna alla lista dei post
    </a>
  </div>

  <h1>{{ $post->title }}</h1>

  <span class="badge mb-4 mt-3" style="background-color: rgb({{ $post->category->color }})">
    {{ $post->category->name }} ({{ $post->category->description }})
  </span>

  {{-- tags --}}
  <div>
    @foreach ($post->tags as $tag)
        <div class="badge" style="background-color: rgb({{ $tag->color }})">{{ $tag->name }}</div>
    @endforeach
  </div>

  <div>
    Scritto da {{ $post->user?->name }}
  </div>

  <div class="">
    <img src="{{ asset('/storage/' . $post->image) }}" alt="" class="img-fluid">
  </div>

  <small>Data pubblicazione: {{ $post->published_at?->format("d/m/Y H:i") }}</small>

  <img src="{{ $post->image }}" alt="" class="img-fluid">

  <p>{{ $post->body }}</p>
</div>

@endsection