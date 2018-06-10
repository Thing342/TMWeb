<ul class="nav nav-pills nav-fill" id="{{ $tabId }}" role="tablist">
    @foreach($tabs as $name=>$id)
        <li class="nav-item">
            <a class="nav-link @if($loop->first) active @endif" data-toggle="tab" href="#{{ $id }}">{{ $name }}</a>
        </li>
    @endforeach
</ul>
<div class="tab-content">
    {{ $slot }}
</div>