@extends('layouts.app')

@section("content")

<div class="container">
  <div class="navbar my-3">
    <a href="{{ route("admin.posts.index") }}" class="btn btn-link">
      <i class="fas fa-chevron-left"></i> Torna alla lista dei post
    </a>
  </div>

  <h1>Nuovo post</h1>

  @if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf()

    <div class="mb-3"><label class="form-label">Titolo</label><input type="text" class="form-control" name="title"></div>
    <div class="mb-3">
        <label class="form-label">Immagine</label>
        <input type="file" accept="image/*" class="form-control" name="image">
    </div>
    <div class="mb-3"><label class="form-label">Contenuto</label><textarea class="form-control" name="body"></textarea></div>

    <button class="btn btn-primary">Crea</button>
  </form>
</div>

@endsection