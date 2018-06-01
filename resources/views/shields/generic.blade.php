@if(strlen($routeNum) <= 2)
    <svg xmlns="http://www.w3.org/2000/svg" width="425.2" height="425.2" viewBox="0 0 425.19685039 425.19685039">
        <g fill="none" stroke="#000" stroke-width=".354">
            <rect width="416.59" height="416.59" x="4.303" y="4.303" rx="41.659" ry="39.675" fill="#fff" stroke="#000"
                  stroke-linejoin="round" stroke-linecap="square" stroke-width="8.605"/>
            <g font-family="&apos;Roadgeek 2014 Series D&apos;" fill="#000" text-anchor="middle" text-align="center"
               stroke="none" word-spacing="0" line-height="125%" letter-spacing="0" font-size="160">
                <text x="212.66" y="149.2">
                    <tspan x="212.66" y="149.2">{{ $region }}</tspan>
                </text>
                <text x="212.66" y="355.2">
                    <tspan x="212.66" y="355.2" font-size="320">{{ $routeNum }}</tspan>
                </text>
            </g>
        </g>
    </svg>
@else
    <svg xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" sodipodi:docname="generic_wide.svg" height="425.2" width="520.7" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" inkscape:version="0.91 r" viewBox="0 0 520.7 425.19685039" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd">
        <sodipodi:namedview bordercolor="#666666" inkscape:pageshadow="2" guidetolerance="10" pagecolor="#ffffff" gridtolerance="10" inkscape:window-maximized="1" inkscape:zoom="0.78493507" objecttolerance="10" borderopacity="1" inkscape:current-layer="svg9882" inkscape:cx="269.99673" inkscape:cy="241.18326" inkscape:window-y="0" inkscape:window-x="0" inkscape:window-width="1920" showgrid="false" inkscape:pageopacity="0" inkscape:window-height="1027"/>
        <rect stroke-linejoin="round" rx="41.659" ry="39.675" height="416.59" width="520.7" stroke="#000" stroke-linecap="square" y="4.303" x="4.303" stroke-width="8.605" fill="#fff"/>
        <g letter-spacing="0" transform="translate(51.841)" style="word-spacing:0;letter-spacing:0;text-anchor:middle;text-align:center" line-height="125%" word-spacing="0" font-size="160px" text-align="center">
            <text y="149.2" x="212.66" font-family="&apos;Roadgeek 2014 Series D&apos;">
                <tspan y="149.2" x="212.66">{{ $region }}</tspan>
            </text>
            <text y="355.20001" x="212.66" font-family="&apos;Roadgeek 2014 Series C&apos;">
                <tspan y="355.20001" x="212.66" font-size="320px">{{ $routeNum }}</tspan>
            </text>
        </g>
    </svg>
@endif