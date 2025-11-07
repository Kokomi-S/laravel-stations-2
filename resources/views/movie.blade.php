@foreach ($movies as $movie)
    <div>{{ $movie->title }}</div>
    <div>{{ $movie->image_url }}</div>
@endforeach