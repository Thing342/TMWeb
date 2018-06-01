<nav aria-label="breadcrumb w-100">
    <ol class="breadcrumb">
        @foreach($links as $link)
            <li class="breadcrumb-item"><a href="{{ $link[1] }}">{{ $link[0] }}</a></li>
        @endforeach
        <li class="breadcrumb-item active" aria-current="page">{{ $item }}</li>
    </ol>
</nav>