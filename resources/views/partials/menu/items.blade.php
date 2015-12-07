@foreach($items as $item)

    <li {!! $item->attributes() !!} >
        @if($item->link) <a href="{{ $item->url() }}">
            {!! $item->icon !!}<span class="nav-label">{!! $item->title !!}</span>
            @if($item->hasChildren()) <span class="fa arrow"></span> @endif
        </a>
        @else
            {{$item->title}}
        @endif
        @if($item->hasChildren())
            <ul class="nav nav-second-level collapse">
                @foreach($item->children() as $child)
                    <li><a href="{{ $child->url() }}">{{ $child->title }}</a></li>
                @endforeach
            </ul>
        @endif
    </li>
@endforeach