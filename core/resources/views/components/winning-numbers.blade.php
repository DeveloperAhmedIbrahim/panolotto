@props(['index' => 0, 'result'])

@php
    $colors = [
        ['bg' => '#ffe0f7', 'color' => '#d400a8'],
        ['bg' => '#fff0cc', 'color' => '#ff6b00'],
        ['bg' => '#dffffc', 'color' => '#00c9b1'],
        ['bg' => '#ffeded', 'color' => '#ff1744'],
        ['bg' => '#eafcff', 'color' => '#0077ff'],
        ['bg' => '#caf0f8', 'color' => '#00b4d8'],
        ['bg' => '#ffd6e0', 'color' => '#ff4d6d'],
        ['bg' => '#d8f3dc', 'color' => '#40916c'],
        ['bg' => '#fff3b0', 'color' => '#e9c46a'],
        ['bg' => '#e2d9f3', 'color' => '#7b2d8b'],
        ['bg' => '#fde8d8', 'color' => '#f4845f'],
        ['bg' => '#d0f4de', 'color' => '#06d6a0'],
        ['bg' => '#fce4ec', 'color' => '#e91e8c'],
        ['bg' => '#e3f2fd', 'color' => '#1e88e5'],
        ['bg' => '#fff8e1', 'color' => '#ffb300'],
    ];

    if(!function_exists('getColor'))
    {
        function getColor($index, $colors)
        {
            if(isset($colors[$index])) {
                $color = [
                    'bg' => $colors[$index]["bg"],
                    'color' => $colors[$index]["color"],
                ];
                return $color;
            } else {
                $index = $index - count($colors);
                return getColor($index, $colors);
            }
        }
    }

    $color = getColor($index, $colors);
@endphp




<ul class="list list--row flex-wrap justify-content-center gap-1">
    @foreach ($result->winning_normal_balls as $winningNormalBall)
        <li>
            <span class="result-card__number result-card__number--light" style="color: {{$color["color"]}}; background-color: {{$color["bg"]}};">{{ $winningNormalBall }}</span>
        </li>
    @endforeach
    @foreach ($result->winning_power_balls as $winningPowerBall)
        <li>
            <span class="result-card__number result-card__number--light active" style="color: {{$color["color"]}}; background-color: {{$color["bg"]}};">{{ $winningPowerBall }}</span>
        </li>
    @endforeach
</ul>
