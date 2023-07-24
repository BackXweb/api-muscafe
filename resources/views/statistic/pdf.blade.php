<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Стиль музыки</th>
        <th>Подстиль музыки</th>
        <th>Название</th>
        <th>Музыка/Реклама</th>
        <th>Дата добавления</th>
    </tr>
    </thead>
    <tbody>
    @foreach($statistics as $statistic)
        <tr>
            <td>{{ $statistic->id }}</td>
            <td>{{ $statistic->style }}</td>
            <td>{{ $statistic->substyle }}</td>
            <td>{{ $statistic->music }}</td>
            <td>{{ $statistic->is_ad ? 'Реклама' : 'Музыка' }}</td>
            <td>{{ date('d.m.Y H:i:s', strtotime($statistic->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
