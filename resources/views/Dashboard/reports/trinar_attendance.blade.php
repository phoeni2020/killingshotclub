@extends('Dashboard.includes.admin')
@section('content')
    <!-- modal medium -->
    <!-- end modal medium -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">قسم التقارير</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/admin')}}">لوحة التحكم</a></li>
                                <li class="breadcrumb-item active">تقرير حضور المدربين</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @if(Session::has('message'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{session()->get('message')}}</strong>
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{session()->get('error')}}</strong>
                </div>
            @endif

            <div class="content-body">
                <!-- Recent Transactions -->
                <div class="row">
                    <div id="recent-transactions" class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                            <li>
                                                <a class="btn btn-sm btn-primary   pull-right" href="{{route('reports.trinar_attendance_report')}}"> <i class="ft-rotate-cw ft-md"></i> </a>
                                            </li>
                                            <li>
                                                <a class="btn btn-sm btn-danger box-shadow-2 round btn-min-width pull-right" href="{{route('reports.trinar_attendance_report',['pdf'=>1,'filter'=>1, request()->fullUrl()])}}" target="_blank"> <i class="ft-pepper ft-md"></i> تحميل  ملف PDF</a>
                                            </li>
                                            <li>
                                                <a class="btn btn-sm btn-success box-shadow-2 round btn-min-width pull-right" href="{{route('reports.trinar_attendance_report',['excel'=>1,'filter'=>1, request()->fullUrl()])}}"> <i class="ft-pepper ft-md"></i> تحميل  ملف Excel</a>
                                            </li>
                                    </ul>
                                </div>
                                <h4 class="card-title">الفلتر </h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div>
                                    <form action="{{route('reports.trinar_attendance_report')}}" method="GET">

                                        <input type="hidden" name="filter" value="1">

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-3 mt-2">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1">بحث باسم المدرب</label>
                                                        <input type="text" name="name">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mt-2">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1">بحث بايميل المدرب</label>
                                                        <input type="email" name="email">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mt-2">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1">بحث برقم هاتف المدرب</label>
                                                        <input type="text" name="phone">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                Cancel
                                            </button>
                                            <input type="submit" value="Confirm" class="btn btn-primary">
                                            {{-- <button type="button"  class="btn btn-primary">Confirm</button> --}}
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="table-responsive">
                                    <table id="tablecontents" class="table table-hover table-xl mb-0 sortable">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0">السيريال</th>
                                            <th class="border-top-0">اسم المدرب</th>
                                            <th class="border-top-0">اليوم</th>
                                            <th class="border-top-0">من</th>
                                            <th class="border-top-0">الي</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($players as $player )
                                            @php
                                                $attendnce = [];
                                                $playerData = \App\Models\User::query()->where('id',$player->trainer_id)->first();
                                                //dd($player->trainer_id);
                                            @endphp
                                            <tr class="row1" data-id="{{ $playerData->id }}" >
                                                <td>{{$playerData->id}}</td>
                                                <td>{{$playerData->name}}</td>
                                                <td>
                                                   {{date('Y-m-d',strtotime($player->check_in))}}

                                                </td>
                                                <td>
                                                    {{date('D, H:i:s',strtotime($player->check_in))}}
                                                </td>
                                                <td>
                                                    {{date('D, H:i:s',strtotime($player->check_out))}}
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                لايوجد سجلات حضور حاليا
                                            </tr>
                                        @endforelse

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($players->hasPages())
                    {{$players->appends(request()->input())->links('pagination::bootstrap-4')}}
                @endif
                <!--/ Recent Transactions -->
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            checkfromType();
            $('.from_type').change(function (){
                checkfromType();

            });

        });
        function checkfromType(){
            if($('input[name="from_type"]:checked').val() =='players'){
                $('#from_players').show();
                $('#from_others').hide();
                $('#others_id').val("");

            }
            if($('input[name="from_type"]:checked').val() =='others'){
                $('#from_others').show();

                $('#from_players').hide();
                $('#player_id').val("");

            }
        }
    </script>
@endsection
*
