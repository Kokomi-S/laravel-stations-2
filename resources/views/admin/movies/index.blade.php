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
                </td>
            </tr>
            @endforeach
        @endif
    </table>
    <button style="margin-top:20px;" onclick="location.href='{{ route('movies.create') }}'">新規作成</button>
</div>
