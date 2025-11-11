<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Sheet</title>
    </head>
    <body>
        <table style="border-collapse: collapse;" border="1">
            <thead>
                <tr>
                    <th colspan="{{ $sheets->count() }}">スクリーン</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $groupedSheets = $sheets->groupBy('row');
                @endphp
                @foreach ($groupedSheets as $row => $rowSheets)
                <tr>
                    @foreach ($rowSheets as $sheet)
                    <td>{{ $sheet->row }}-{{ $sheet->column }}</td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>