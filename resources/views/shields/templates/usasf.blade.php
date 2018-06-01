@php
    $lines = explode(',',preg_replace('/(?!^)[A-Z]{3,}(?=[A-Z][a-z])|[A-Z][a-z]/', ',$0', $route[\App\ROUTE_ROUTE]));

    if($lines[0] != "") {
        array_unshift($lines, "");
    }

    $numLines = sizeof($lines);

    $strokewidth = 14.88;
    $textbase = 36;
    $textsep = 120;
    $height = ((480.0 - (2 * $strokewidth)) * (($numLines-1) / 3)) + (2 * $strokewidth);
    $width = 640

@endphp

<svg xmlns="http://www.w3.org/2000/svg" width="{{ $width + $strokewidth + 1 }}"
     height="{{ $height + $strokewidth + 1 }}"
     viewBox="0 0 {{ $width + $strokewidth + 1 }} {{ $height + $strokewidth + 1 }}">
    <g transform="scale(-1-1)">
        <rect width="{{ $width + $strokewidth + 1 }}" height="{{ $height + $strokewidth + 1 }}"
              x="-{{ $width + $strokewidth + 1 }}" y="-{{ $height + $strokewidth + 1 }}" rx="30" fill="#000000"></rect>
        <rect width="{{ $width }}" height="{{ $height }}" x="-{{ $width }}" y="-{{ $height }}" rx="30" fill="#006b5d"></rect>
        <rect ry="26.0" rx="26.0" y="-{{ $height + $strokewidth / 2 }}" x="-{{ $width + $strokewidth / 2 }}" height="{{ $height }}" width="{{ $width }}" fill="none"
              stroke="#fff"
              stroke-linejoin="round" stroke-linecap="square" stroke-width="{{ $strokewidth }}"></rect>
    </g>
    <text x="{{ $width / 2 }}" y="{{ $textbase  }}" font-family="&apos;Roadgeek 2014 Series EM&apos;" letter-spacing="0"
          word-spacing="0"
          text-anchor="middle" text-align="center" fill="#fff" line-height="100%" font-size="160">@for($i = 0; $i < $numLines; $i++)<tspan text-anchor="middle" x="{{ $width / 2 + $strokewidth }}" y="{{ $textbase + $i * $textsep }}">{{ $lines[$i] }}</tspan>@endfor</text>
</svg>