<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<table>
    <tr><td>ID</td><td>BRAND</td><td>MICRO-ID</td></tr>
    @foreach($brands as $brand)
        <tr><td>{{$brand['id']}}</td><td>{{$brand['BrandName']}}</td><td>{{$brand['micro_id']}}</td></tr>
        @endforeach
</table>

</body>
</html>