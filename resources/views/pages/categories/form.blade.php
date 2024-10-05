@extends('layouts.layouts')


@section('page_title')

@section('breadcrumbs')
    @php
        $breadcum = [
            [
                'name' => 'Danh mục',
                'url' => route('categories')
            ],
            [
                'name' => 'Chỉnh sửa',
                'url' => '/'
            ],
        ];

    @endphp
<div class="row mb-3 mt-2 bg-white">
    <div class="col-md-12 px-0">
        @include('layouts.components.breadcrumb',$breadcum)
    </div>
</div>
    
@endsection

@section('content')

    <div class="row bg-white backend-container pt-3" style="margin-left: -15px;margin-right:-15px">
        <div class="col-md-12 pb-3">
            <div class="">
        
            </div>
        </div>
    </div> 

    

@endsection

@section('scripts')
   <script>
    
   </script>

 
@endsection