<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('home') }}">
        <img src="/img/tm.png" alt="TravelMapping logo" width="30" height="30">
        Travel Mapping
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Traveler Stats</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('route.browser') }}">Highway Browser</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Forum</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Updates</a>
            </li>
        </ul>
        <form action="{{ route('travelers.change') }}" class="form-inline my-2 my-lg-0" method="post">
            @csrf
            <select name="user" id="userSelect" class="form-control mr-sm-2"
                    @if(session('user')) data-selected-user="{{ session('user') }}" @endif>
                <option value="" selected>Select User</option>
            </select>
            <button class="btn btn-outline-secondary my-2 my-sm-0 mr-2" type="submit">Change</button>
        </form>
        <form class="form-inline my-2 my-lg-0" action="{{ route('route.browser') }}">
            @csrf
            <input class="form-control mr-sm-2" type="search" placeholder="Search Routes" aria-label="Search" name="query" style="max-width: 150px">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>