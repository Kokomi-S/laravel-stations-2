<div>
    <!-- It is quality rather than quantity that matters. - Lucius Annaeus Seneca -->
</div>
@foreach ($movies as $movie)
    <div>{{ $movie->title }}</div>
    <div>{{ $movie->image_url }}</div>
@endforeach