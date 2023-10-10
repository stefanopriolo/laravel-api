@extends('layouts.admin')
@section("content")
	<h1>Lista dei post</h1>
	<div>
		<a href="{{ route('admin.posts.create') }}">Nuovo post</a>
	</div>
	<table>
		<thead>
			<tr>
				<th>Titolo</th>
				<th>Immagine</th>
				<th>Data Pubblicazione</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($posts as $post)
				<tr>
					<td>{{ $post->title }}</td>
					<td>{{ $post->image }}</td>
					<td>{{ $post->published_at?->format("d/m/Y H:i") }}</td>
					<td><a href="{{ route('admin.posts.show', $post->slug )}}">Dettagli</a></td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection