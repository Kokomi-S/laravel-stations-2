<html>
    <head>
      <!-- <title></title> -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>
    <body>
        <h1>上映スケジュール</h1>

        @foreach($movies as $movie)
        <div style="margin: 30px 0;">
            <h2>
                <a href="{{ route('movies.scheduleShow', ['id' => $movie->id]) }}">
                {{$movie->title}}
                </a>
            </h2>
            <table style="border-collapse: collapse;" border="1">
                <tr>
                    <td>スケジュールID</td>
                    <td>開始時刻</td>
                    <td>終了時刻</td>
                    <td>登録日時</td>
                    <td>更新日時</td>
                </tr>
                @foreach($movie->schedules as $schedule)
                    <tr>
                        <td>{{$schedule->id}}</td>
                        <td>{{optional($schedule->start_time)->format('Y-m-d H:i:s')}}</td>
                        <td>{{optional($schedule->end_time)->format('Y-m-d H:i:s')}}</td>
                        <td>{{optional($schedule->created_at)->format('Y-m-d H:i:s')}}</td>
                        <td>{{optional($schedule->updated_at)->format('Y-m-d H:i:s')}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        @endforeach
        {{$movies->links()}}
    </body>
</html>