{{-- <div class="row mb-2"> --}}
    {{-- <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Dashboard v1</li>
      </ol>
    </div><!-- /.col -->
</div><!-- /.row --> --}}

@php
    $counter = 0;
@endphp
<div class="ibox-content forum-container">
    <h2 class="st_title">
        @foreach ($breadcum as $key => $item)
            @if ($key == 0 )
            <i class="fas fa-th-large mr-2"></i>
            @else
            <i class="fas fa-angle-right mx-2"></i>
            @endif
        @php
            $dropContainer = !empty($item['drop-menu'])?'relative drop-container':'';
            $arrowDrop = !empty($item['drop-menu'])?' <span class="pl-1"><i class="fas fa-caret-down"></i></span>':'';
            $dropMenu =
                '<div class="drop bg-white">
                    <ul class="list pl0">';
            if (!empty($item['drop-menu'])){
                foreach ($item['drop-menu'] as $sub){
                    $dropMenu .= '<li><a href="'.$sub['url'].'">'.$sub['name']."</a></li>";
                }
                $dropMenu.='</ul></div>';
            }else
                $dropMenu='';
        @endphp
            {{-- @if (!empty($item['url']))
                <span class="{{$dropContainer}} mybreadcrumb"><a href="{{ $item['url'] }}" title="{{ $item['name'] }}">{{ $item['name'] }} @if ($item['drop-menu']) ({{$item['code']}})@endif</a> {!! $arrowDrop !!} {!! $dropMenu !!}</span>
            @else --}}
                @if ($counter == count( $breadcum ) - 1)
                    <span class="font-weight-bold {{$dropContainer}} mybreadcrumb">{{ $item['name'] }} {!! $arrowDrop !!} {!! $dropMenu !!}</span>
                @else
                    <span class=" {{$dropContainer}} mybreadcrumb">{{ $item['name'] }} {!! $arrowDrop !!} {!! $dropMenu !!}</span>
                @endif
            {{-- @endif --}}
            @php
                $counter = $counter + 1;
            @endphp
        @endforeach
    </h2>
</div>
