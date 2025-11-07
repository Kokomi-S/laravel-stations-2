@if ($errors->any())
    <div style="color: red;">
        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach
    </div>
@endif
<form action="{{ route('movies.update', ['id' => $movie->id]) }}" method="POST" style="padding:20px 0;">
    @csrf
    @method('PATCH')
    <div>
        <label for="title">タイトル:</label>
        <input type="text" id="title" name="title" value="{{ old('title', $movie->title) }}" required>
    </div>
    <div>
        <label for="image_url">画像URL:</label>
        <input type="text" id="image_url" name="image_url" value="{{ old('image_url', $movie->image_url) }}" required>
    </div>
    <div>
        <label for="published_year">公開年:</label>
        <input type="number" id="published_year" name="published_year" value="{{ old('published_year', $movie->published_year) }}" required>年
    </div>
    <div>
        <label for="is_showing">上映中:</label>
        <input type="hidden" name="is_showing" value="0">
        <input type="checkbox" id="is_showing" name="is_showing" value="1" {{ old('is_showing', $movie->is_showing) ? 'checked' : '' }}>
    </div>
    <div>
        <label for="description">説明:</label>
        <textarea id="description" name="description">{{ old('description', $movie->description) }}</textarea>
    </div>
    <div style="padding: 20px;">
        <button type="submit">更新</button>
    </div>
</form>
