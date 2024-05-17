@extends('Dashboard.includes.admin')
@section('content')
    <!-- modal medium -->
    <div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">Filter </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('lists.income_list')}}" method="GET">
                    @csrf
                    <input type="hidden" name="filter" value="1">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="" class="control-label mb-1">فرع:</label>
                                    <select class="form-control" name="branch_id">
                                        <option value="">اختر فرع</option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}"
                                                {{ $branch->id == request('branch_id') ? 'selected' : '' }}
                                            >{{$branch->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="" class="control-label mb-1">الخزن </label>
                                    <select class="form-control" name="branch_id">
                                        <option value="">اختر خزنة</option>
                                        @foreach($safes as $safe)
                                            <option value="{{$safe->id}}"
                                                {{ $safe->id == request('safe_id') ? 'selected' : '' }}
                                            >{{$safe->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label for="" class="control-label mb-1">الخزن:</label>
                                    <select class="form-control" name="safe_id">
                                        <option value="">اختر فرع</option>
                                        @foreach($safes as $safe)
                                            <option value="{{$safe->id}}"
                                                {{ $safe->id == request('safe_id') ? 'selected' : '' }}
                                            >{{$safe->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="" class="control-label mb-1">من التاريخ:</label>
                                    <input class="form-control" type="date" name="fromDate"
                                           value="{{request('fromDate')}}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="" class="control-label mb-1"> الي التاريخ </label>
                                    <input class="form-control" type="date" name="toDate" value="{{request('toDate')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <input type="submit" value="Confirm" class="btn btn-primary">
                        {{-- <button type="button"  class="btn btn-primary">Confirm</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal medium -->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">قسم القوائم</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/admin')}}">لوحة التحكم</a></li>
                                <li class="breadcrumb-item active">قائمة الدخل</li>
                            </ol>
                        </div>
                    </div>
                    <ul class="list-inline mb-0">
                        <li>
                            <a class="btn btn-sm btn-primary   pull-right" href="{{route('lists.income_list')}}"> <i class="ft-rotate-cw ft-md"></i> </a>
                        </li>
                        <li>
                            <a class="btn btn-sm btn-danger box-shadow-2 round btn-min-width pull-right" href="{{route('lists.income_list',['pdf'=>1,'filter'=>1, request()->fullUrl()])}}" target="_blank"> <i class="ft-pepper ft-md"></i> تحميل  ملف PDF</a>
                        </li>
                        <li>
                            <a class="btn btn-sm btn-success box-shadow-2 round btn-min-width pull-right" href="{{route('lists.income_list',['excel'=>1,'filter'=>1, request()->fullUrl()])}}"> <i class="ft-pepper ft-md"></i> تحميل  ملف Excel</a>
                        </li>
                    </ul>
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
                                <h4 class="card-title">الفلتر </h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div>
                                    <form action="{{route('lists.income_list')}}" method="GET">

                                        <input type="hidden" name="filter" value="1">

                                        <div class="modal-body">


                                            <div class="row">
                                                <div class="col-md-12 mt-2">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1">فرع:</label>
                                                        <select class="form-control" name="branch_id">
                                                            <option value="">اختر فرع</option>
                                                            @foreach($branches as $branch)
                                                                <option value="{{$branch->id}}"
                                                                    {{ $branch->id == request('branch_id') ? 'selected' : '' }}
                                                                >{{$branch->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mt-2">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1">لعبه:</label>
                                                        <select class="form-control" name="sport_id">
                                                            <option value="">اختر لعبه</option>
                                                            @foreach($sports as $sport)
                                                                <option value="{{$sport->id}}"
                                                                    {{ $sport->id == request('sport_id') ? 'selected' : '' }}
                                                                >{{$sport->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1">من التاريخ:</label>
                                                        <input class="form-control" type="date" name="fromDate"
                                                               value="{{request('fromDate')}}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1"> الي التاريخ </label>
                                                        <input class="form-control" type="date" name="toDate"
                                                               value="{{request('toDate')}}">
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
                                    <table id="myTable" class="table table-hover table-xl mb-0 sortable">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0"> البند</th>
                                            <th class="border-top-0"> المبلغ</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                            <tr class="row1">
                                                <td>
                                                    <strong>الايردات</strong>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr class="row2">
                                                <td>
                                                   <strong>الاشتراكات</strong>
                                                </td>
                                                <td>
                                                    {{$subscriptionsSum}}
                                                </td>
                                            </tr>
                                            <tr class="row3">
                                                <td>
                                                   <strong>ايرادات اخري </strong>
                                                </td>
                                                <td>
                                                    {{$otherIncome}}
                                                </td>
                                            </tr>
                                            <tr class="row4">
                                                <td>
                                                  <strong>اجمالي ايرادات</strong>
                                                </td>
                                                <td>
                                                    {{$subscriptionsSum + $otherIncome }}
                                                </td>
                                            </tr>
                                            <tr class="row3">
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="row5">
                                                <td>
                                                    <strong>ايجارات و صيانه</strong>
                                                </td>
                                                <td>
                                                    {{$rentAndMaintance}}
                                                </td>
                                            </tr>
                                            <tr class="row6">
                                                <td>
                                                   <strong>الرواتب</strong>
                                                </td>
                                                <td>
                                                    {{$otherExpense}}
                                                </td>
                                            </tr>
                                            <tr class="row6">
                                                <td>
                                                    <strong>المصروفات</strong>
                                                </td>
                                                <td>
                                                    {{$otherExpense}}
                                                </td>
                                            </tr>

                                            <tr class="row7">
                                                <td>
                                                     <strong>الاجمالي</strong>
                                                </td>
                                                <td>
                                                    {{$otherExpense + $rentAndMaintance}}
                                                </td>
                                            </tr>
                                            <tr class="row3">
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr class="row8">
                                                <td>
                                                   <strong>صافي الربح</strong>
                                                </td>
                                                <td>
                                                    {{$total}}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            checkfromType();
            $('.from_type').change(function () {
                checkfromType();

            });

        });

        function checkfromType() {
            if ($('input[name="from_type"]:checked').val() == 'players') {
                $('#from_players').show();
                $('#from_others').hide();
                $('#others_id').val("");

            }
            if ($('input[name="from_type"]:checked').val() == 'others') {
                $('#from_others').show();

                $('#from_players').hide();
                $('#player_id').val("");

            }
        }
    </script>
@endsection
