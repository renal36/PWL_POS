<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Level List</title>
</head>
<body>
    <h1>Level List</h1>
    
    @if($levels->isEmpty())
        <p>No data available</p>
    @else
        <ul>
            @foreach($levels as $level)
                <li>{{ $level->level_nama }} ({{ $level->level_kode }})</li>
            @endforeach
        </ul>
    @endif
</body>
</html>
