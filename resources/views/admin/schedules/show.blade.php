<html>
    <head>
      <!-- <title></title> -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>
    <body>
        <div>
            <h1>{{$movies->title}}</h1>
            <img src='{{$movies->image_url}}' alt=''>
            <div style="padding: 20px 0;">
                <h2>映画情報</h2>
                <table border cellspacing="0">
                    <tr>
                        <td>ID</td>
                    <!-- <td>映画タイトル</td> -->
                    <td>ジャンル</td>
                    <td>画像URL</td>
                    <td>公開年</td>
                    <td>上映情報</td>
                    <td>概要</td>
                </tr>
                <tr>
                    <td>{{$movies->id}}</td>
                    <td>{{$movies->genre->name}}</td>
                    <td>{{$movies->image_url}}</td>
                    <td>{{$movies->published_year}}</td>
                    <td>{{$movies->is_showing ? '上映中' : '上映予定'}}</td>
                    <td>{{$movies->description}}</td>
                </tr>
            </table>
        </div>
            <h2>スケジュール一覧</h2>
            <a href="{{ route('movies.scheduleCreate', ['id' => $movies->id]) }}">スケジュール追加</a>
            @if($movies->schedules->isEmpty())
                <p>スケジュールはありません。</p>
            @else
                <table style="border-collapse: collapse;" border="1">
                    <tr>
                        <td>スケジュールID</td>
                        <td>開始時刻</td>
                        <td>終了時刻</td>
                        <td>登録日時</td>
                        <td>更新日時</td>
                        <td></td>
                    </tr>
                    @foreach($movies->schedules as $schedule)
                    <tr>
                        <td>{{$schedule->id}}</td>
                        <td>{{optional($schedule->start_time)->format('Y-m-d H:i:s')}}</td>
                        <td>{{optional($schedule->end_time)->format('Y-m-d H:i:s')}}</td>
                        <td>{{optional($schedule->created_at)->format('Y-m-d H:i:s')}}</td>
                        <td>{{optional($schedule->updated_at)->format('Y-m-d H:i:s')}}</td>
                        <td>
                            <button onclick="location.href='{{ route('movies.scheduleEdit', ['id' => $schedule->id]) }}'">編集</button>
                        </td>
                        <td>
                            <form action="{{ route('movies.scheduleDestroyById', ['schedule_id' => $schedule->id]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">削除</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </body>
</html>