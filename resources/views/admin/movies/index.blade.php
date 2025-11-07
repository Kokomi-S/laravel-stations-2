<form action="{{ route('movies.index') }}">
    <div>
        <label for="title">映画タイトル：</label>
        <input type="text" id="title" name="title" value="{{ request('title') }}" placeholder="入力">
    </div>
    <div>
        <div>
            <label>上映状況：</label>
            <input type="radio" id="query_all" name="is_showing" value="" {{ request('is_showing') === null  || request('is_showing') === '' ? 'checked' : '' }}>
            <label for="query_all">すべて</label>
            
            <input type="radio" id="query_showing" name="is_showing" value="1" {{ request('is_showing') === '1' ? 'checked' : '' }}>
            <label for="query_showing">上映中</label>
            
            <input type="radio" id="query_not_showing" name="is_showing" value="0" {{ request('is_showing') === '0' ? 'checked' : '' }}>
            <label for="query_not_showing">上映予定</label>
        </div>
        <button type="submit">検索</button>
    </div>
</form>

<div>
    @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif
    <table border cellspacing="0">
        @if ($movies->isEmpty())
            <div>
                映画データが存在しません。
            </div>
            @else
            <tr>
                <td>ID</td>
                <td>映画タイトル</td>
                <td>画像URL</td>
                <td>公開年</td>
                <td>上映情報</td>
                <td>概要</td>
                <td>登録日時</td>
                <td>更新日時</td>
                <td></td>
            </tr>
            @foreach ($movies as $movie)
            <tr>
                <td>{{$movie->id}}</td>
                <td>{{$movie->title}}</td>
                <td>{{$movie->image_url}}</td>
                <td>{{$movie->published_year}}</td>
                <td>{{$movie->is_showing ? '上映中' : '上映予定'}}</td>
                <td>{{$movie->description}}</td>
                <td>{{$movie->created_at}}</td>
                <td>{{$movie->updated_at}}</td>
                <td>
                    <button onclick="location.href='{{ route('movies.edit', ['id' => $movie->id]) }}'">編集</button>
                    <form style="display:inline;" method="POST" action="{{ route('movies.destroy', ['id' => $movie->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('本当に削除しますか？')">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        @endif
    </table>
    <button style="margin-top:20px;" onclick="location.href='{{ route('movies.create') }}'">新規作成</button>
</div>
