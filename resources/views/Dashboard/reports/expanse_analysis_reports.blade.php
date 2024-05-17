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
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="" class="control-label mb-1">من التاريخ:</label>
                                    <input class="form-control" type="month" name="fromDate"
                                           value="{{request('fromDate')}}">
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
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li>
                                            <a class="btn btn-sm btn-primary   pull-right" href="{{route('lists.expenseAnalysis')}}"> <i class="ft-rotate-cw ft-md"></i> </a>
                                        </li>
                                        <li>
                                            <a class="btn btn-sm btn-danger box-shadow-2 round btn-min-width pull-right" href="{{route('lists.expenseAnalysis',['pdf'=>1,'filter'=>1, request()->fullUrl()])}}" target="_blank"> <i class="ft-pepper ft-md"></i> تحميل  ملف PDF</a>
                                        </li>
                                        <li>
                                            <a class="btn btn-sm btn-success box-shadow-2 round btn-min-width pull-right" href="{{route('lists.expenseAnalysis',['excel'=>1,'filter'=>1, request()->fullUrl()])}}"> <i class="ft-pepper ft-md"></i> تحميل  ملف Excel</a>
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <form action="{{route('lists.expenseAnalysis')}}" method="GET">

                                        <input type="hidden" name="filter" value="1">

                                        <div class="modal-body">


                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1">من التاريخ:</label>
                                                        <input class="form-control" type="month" name="fromDate"
                                                               value="{{request('fromDate')}}">
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
                                            <th class="border-top-0">الشهر</th>
                                            <th class="border-top-0">الاجمالي</th>
                                            <th class="border-top-0">إيجارات</th>
                                            <th class="border-top-0">أجور و مرتبات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($branchesSports as $key =>$branch)
                                                <tr>
                                                <td>{{$months[$key]}}</td>
                                                <td>{{$branch['totalExpense']}}</td>
                                                <td>{{$branch['rentAndMaintance']}}</td>
                                                <td>{{$branch['salary']}}</td>
                                                </tr>
                                            @endforeach
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
