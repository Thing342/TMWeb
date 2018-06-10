@extends('app')

@section('title', 'User Leaderboard')

@section('content')
    <h1>{{ $traveler->traveler }}</h1>

    @component('components.tabs', ['tabId'=>'nav', 'tabs' => [
    'Overall Stats' => 'overall',
    'Stats by Region' => 'regions',
    'Stats by System' => 'systems']
    ])
        @component('components.tab-item', ['active' => true, 'id' => 'overall'])
            <div>
                <table class="table w-100">
                    <thead><tr>
                        <td></td>
                        <td>Active Systems</td>
                        <td>Active + Preview Systems</td>
                    </tr></thead>
                    <tbody>
                        <tr>
                            <td>Miles Driven</td>
                            <td>{{ number_format($traveler->overallActiveMileage,2) }} mi</td>
                            <td>{{ number_format($traveler->overallActivePreviewMileage, 2) }} mi</td>
                        </tr>
                        <tr>
                            <td>Routes Traveled</td>
                            <td>TBD</td>
                            <td>TBD</td>
                        </tr>
                        <tr>
                            <td>Routes Clinched</td>
                            <td>TBD</td>
                            <td>TBD</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endcomponent
        @component('components.tab-item', ['active' => false, 'id' => 'regions'])
            <table class="table table-hover" id="regionTable">
                <thead>
                <tr>
                    <td colspan="2"></td>
                    <td colspan="3">Active</td>
                    <td colspan="3">Active + Preview</td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>Country</td>
                    <td>Region</td>
                    <td>Distance</td>
                    <td>Overall</td>
                    <td>%</td>
                    <td>Distance</td>
                    <td>Overall</td>
                    <td>%</td>
                    <td>Actions</td>
                </tr>
                </thead>
                <tbody>
                @foreach($traveler->regions as $region)
                    @php($regionTotal = $regionTotals[$region->code])
                    <tr class="regionRow" data-region="{{ $region->code }}">
                        <td>{{ $region->country }}</td>
                        <td>{{ $region->name }}</td>
                        <td data-order="{{ $region->pivot->activeMileage }}">{{ number_format($region->pivot->activeMileage, 0) }} mi</td>
                        <td data-order="{{ $regionTotal->activeMileage }}">{{ number_format($regionTotal->activeMileage, 0) }} mi</td>
                        <td>{{ number_format($region->pivot->activeMileage / $regionTotal->activeMileage, 2) }}</td>
                        <td data-order="{{ $region->pivot->activePreviewMileage }}">{{ number_format($region->pivot->activePreviewMileage, 0) }} mi</td>
                        <td data-order="{{ $regionTotal->activePreviewMileage }}">{{ number_format($regionTotal->activePreviewMileage, 0) }} mi</td>
                        <td>{{ number_format($region->pivot->activePreviewMileage / $regionTotal->activePreviewMileage * 100, 2) }}</td>
                        <td><div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" role="button" href="#"
                                   id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('travelers.mapview', $traveler->traveler) }}?rg={{ $region->code }}">View in Mapview</a>
                                    <a class="dropdown-item" href="{{ route('route.index.region', $region->code) }}">View in Highway Browser</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endcomponent
        @component('components.tab-item', ['active' => false, 'id' => 'systems'])
            <table class="table table-hover my-2" id="systemsTable">
                <thead>
                <tr>
                    <td>Country</td>
                    <td>System Code</td>
                    <td>System Name</td>
                    <td>Tier</td>
                    <td>Status</td>
                    <td>Clinched (miles)</td>
                    <td>Total (miles)</td>
                    <td>% Clinched</td>
                    <td>Actions</td>
                </tr>
                </thead>
                <tbody>
                @foreach($traveler->systems as $system)
                    @php($systemTotal = $systemTotals[$system->systemName])
                    <tr class="systemRow" data-system="{{ $system->systemName }}">
                        <td>{{ $system->countryCode }}</td>
                        <td>{{ $system->systemName }}</td>
                        <td>{{ $system->fullName }}</td>
                        <td>{{ $system->tier }}</td>
                        <td>{{ $system->level }}</td>
                        <td data-order="{{ $system->pivot->mileage }}">{{ number_format($system->pivot->mileage, 0) }} mi</td>
                        <td data-order="{{ $systemTotal->mileage }}">{{ number_format($system->pivot->mileage, 0) }} mi</td>
                        <td>{{ number_format($system->pivot->mileage / $systemTotal->mileage * 100, 2) }}</td>
                        <td>
                            <div class="dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
                                   id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="{{ route('travelers.mapview', $traveler->traveler) }}?rg={{ $system->systemName }}">View in Mapview</a>
                                    <a class="dropdown-item" href="{{ route('route.index.system', $system->systemName) }}">View in Highway Browser</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endcomponent
    @endcomponent
@endsection

@section('js')
    <script type="application/javascript">
        function regionPageURL(region) {
            var urlBase = "{{ route('travelers.region', $traveler->traveler, 'XXX') }}";
            return urlBase.replace('XXX', region);
        }

        function systemPageURL(system) {
            var urlBase = "{{ route('travelers.system', $traveler->traveler, 'XXX') }}";
            return urlBase.replace('XXX', system);
        }

        $(function(){
            console.log("HHHHH");
            var regionTable = $('#regionTable');
            var systemTable = $('#systemsTable');

            regionTable.DataTable({
                order: [2, 'desc']
            });
            systemTable.DataTable({
                order: [5, 'desc']
            });

            regionTable.find('tr.regionRow').on('click', function() {
               var regionName = $(this).data('region');
               window.location.href = regionPageURL(regionName);
            });

            systemTable.find('tr.systemRow').on('click', function() {
                var systemName = $(this).data('system');
                window.location.href = systemPageURL(systemName);
            });
        });
    </script>
@endsection