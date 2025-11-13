<html>
    <head>
      <!-- <title></title> -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>
    <body>
        <h2>スケジュール登録</h2>
        <h3>{{$movie->id}}. {{$movie->title}}</h3>

        @if ($errors->any())
            <div style="color: red;">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
        <form action="{{ route('movies.scheduleStore', ['id' => $movie->id]) }}" method="POST" style="padding:20px 0;">
            @csrf
            <div>
                <label for="start_time_date">開始日付:</label>
                <input type="date" id="start_time_date" name="start_time_date" value="{{ old('start_time_date') }}" required>
            </div>
            <div>
                <label for="start_time_time">開始時刻:</label>
                <input type="time" id="start_time_time" name="start_time_time" value="{{ old('start_time_time') }}" required>
            </div>
            <div>
                <label for="end_time_date">終了日付:</label>
                <input type="date" id="end_time_date" name="end_time_date" value="{{ old('end_time_date') }}" required>
            </div>
            <div>
                <label for="end_time_time">終了時刻:</label>
                <input type="time" id="end_time_time" name="end_time_time" value="{{ old('end_time_time') }}" required>
            </div>
            <div style="padding: 20px;">
                <button type="submit">登録</button>
            </div>
        </form>
        <a href="{{ route('movies.scheduleIndex') }}">上映スケジュール一覧に戻る</a>
    </body>
</html>