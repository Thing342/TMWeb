@php($routeNum = str_replace("Vt", "", $route[\App\ROUTE_ROUTE]))
<svg xmlns="http://www.w3.org/2000/svg" height="120px" width="120px" version="1.1" viewBox="0 0 120 120">
    <g fill-rule="evenodd">
        <rect rx="0" ry="0" height="120" width="120" y="0" x="0" fill="#ff163c"/>
        <rect rx="10" ry="10" height="112" width="112" y="4.25" x="4.25" fill="#ffffff"/>
        <rect rx="10" ry="10" height="100" width="100" y="10.25" x="10.25" fill="#ff163c"/>
        <text letter-spacing="0px" text-anchor="middle" word-spacing="0px" text-align="center" font-size="100px" line-height="100%" y="60" x="100" font-family="&apos;Roadgeek 2005 Mittelschrift&apos;" fill="#ffffff">
            <tspan y="94" x="60">{{ $routeNum }}</tspan>
        </text>
    </g>
</svg>