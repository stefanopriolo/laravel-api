@extends('layouts.app')

@section("content")

<div class="container">
  <div class="navbar my-3">
    <a href="{{ route("admin.posts.index") }}" class="btn btn-link">
      <i class="fas fa-chevron-left"></i> Torna alla lista dei post
    </a>
  </div>

  <h1>Aggiorna post</h1>

  @if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <form action="{{ route('admin.posts.update', $post->slug) }}" method="POST" enctype="multipart/form-data">
    @csrf()
    @method("PATCH")

    <div class="mb-3"><label class="form-label" for="title_input">Titolo</label><input type="text" class="form-control" name="title" id="title_input" value="{{ $post->title }}"></div>
    <div class="mb-3"><label class="form-label">Data Pubblicazione</label><input type="date" class="form-control" name="published_at" value="{{ $post->published_at?->toDateString() }}"></div>

    <div class="mb-3">
        <label class="form-label">Immagine</label>
        @if($post->image)
            <img src="{{ asset('/storage/' . $post->image) }}" alt="" class="img-thumbnail" style="width: 100px">
        @endif

        <div class="input-group">
            <label class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon04">
                <input type="file" class="form-control d-none" name="image" accept="image/*">
                Scegli
            </label>
            <input type="text" class="form-control" name="image_link">
          </div>
    </div>

    <div class="mb-3"><label class="form-label">Contenuto</label><textarea class="form-control" name="body">{{ $post->body }}</textarea></div>
    <div class="mb-3">
      <div class="form-check">
        {{-- Barbatrucco per inviare un dato false nel caso in cui la checkbox non sia selezionata --}}
        <input type="hidden" name="is_published" value="0">
        <input class="form-check-input" type="checkbox" name="is_published" id="is_published_input" {{ $post->is_published ? 'checked' : '' }} value="1">
        <label class="form-check-label" for="is_published_input">
          Pubblicato
        </label>
      </div>
    </div>

    <button class="btn btn-primary">Aggiorna</button>
  </form>
</div>

@endsection