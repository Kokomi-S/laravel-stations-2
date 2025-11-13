<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>映画詳細</title>
    </head>
    <body>
        <table style="border-collapse: collapse;" border="1">
            <tr>
                <td>映画ID</td>
                <td>映画タイトル</td>
                <td>画像URL</td>
                <td>公開年</td>
                <td>上映中</td>
                <td>概要</td>
                <td>登録日時</td>
                <td>更新日時</td>
            </tr>
            <tr>
                <td>{{$movie->id}}</td>
                <td>{{$movie->title}}</td>
                <td>{{$movie->image_url}}</td>
                <td>{{$movie->published_year}}</td>
                <td>{{$movie->is_showing ? '上映中' : '上映予定'}}</td>
                <td>{{$movie->description}}</td>
                <td>{{$movie->created_at}}</td>
                <td>{{$movie->updated_at}}</td>
            </tr>
        </table>

        <h2>上映スケジュール</h2>
        @if($schedules->isEmpty())
            <p>スケジュールはありません</p>
        @else
        <table style="border-collapse: collapse;" border="1">
            <tr>
                <td>スケジュールID</td>
                <td>開始時刻</td>
                <td>終了時刻</td>
                <td>登録日時</td>
                <td>更新日時</td>
            </tr>
            @foreach($schedules as $schedule)
            <tr>
                <td>{{$schedule->id}}</td>
                <td>{{$schedule->start_time}}</td>
                <td>{{$schedule->end_time}}</td>
                <td>{{$schedule->created_at}}</td>
                <td>{{$schedule->updated_at}}</td>
            </tr>
            @endforeach
        </table>
        @endif
    </body>
</html>