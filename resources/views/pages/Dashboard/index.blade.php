@extends('layouts.layouts')


@section('page_title')

@section('breadcrumbs')
    @php
        $breadcum = [
            [
                'name' => 'Dashboard',
                'url' => '/home'
            ],
            [
                'name' => 'test',
                'url' => ''
            ]
        ];

    @endphp
<div class="row mb-3 mt-2 bg-white">
    <div class="col-md-12 px-0">
        @include('layouts.components.breadcrumb',$breadcum)
    </div>
</div>
    
@endsection

@section('content')
    <div class="row" style="margin-left: -15px;margin-right:-15px"> <!--begin::Col-->
        <div class="col-lg-3 col-sm-6">
            <div class="overview-item bg-white p-3">
                <a href="" class="small-box-footer bg-white">
                    <div class="row">
                        <div class="col-xl-8 col-lg-6">
                            <div class="mb-2 overview_title">
                                Lớp học Elearning
                            </div>
                            <h2>89</h2>
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <img src="https://vus.toplearning.vn/images/dashboard/iconDashboard/online.png" style="height: 60px;transition: all .4s" alt="Online course">
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="overview-item bg-white p-3">
                <a href="" class="small-box-footer bg-white">
                    <div class="row">
                        <div class="col-xl-8 col-lg-6">
                            <div class="mb-2 overview_title">
                                Lớp học Elearning
                            </div>
                            <h2>89</h2>
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <img src="https://vus.toplearning.vn/images/dashboard/iconDashboard/online.png" style="height: 60px;transition: all .4s" alt="Online course">
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="overview-item bg-white p-3">
                <a href="" class="small-box-footer bg-white">
                    <div class="row">
                        <div class="col-xl-8 col-lg-6">
                            <div class="mb-2 overview_title">
                                Lớp học Elearning
                            </div>
                            <h2>89</h2>
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <img src="https://vus.toplearning.vn/images/dashboard/iconDashboard/online.png" style="height: 60px;transition: all .4s" alt="Online course">
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="overview-item bg-white p-3">
                <a href="" class="small-box-footer bg-white">
                    <div class="row">
                        <div class="col-xl-8 col-lg-6">
                            <div class="mb-2 overview_title">
                                Lớp học Elearning
                            </div>
                            <h2>89</h2>
                        </div>
                        <div class="col-xl-4 col-lg-6">
                            <img src="https://vus.toplearning.vn/images/dashboard/iconDashboard/online.png" style="height: 60px;transition: all .4s" alt="Online course">
                        </div>
                    </div>
                </a>
            </div>
        </div>     
      
    </div> <!--end::Row--> <!--begin::Row-->

    <div class="row"> <!-- Start col -->
       
    </div> <!-- /.row (main row) -->

@endsection