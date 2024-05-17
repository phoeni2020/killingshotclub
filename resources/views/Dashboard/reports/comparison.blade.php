@extends('Dashboard.includes.admin')
@section('content')
    <!-- modal medium -->
    <div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediumModalLabel">Filter </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
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
                                <li class="breadcrumb-item active">تقرير الخزن</li>
                            </ol>
                        </div>
                        <ul class="list-inline mb-0">
                            <li>
                                <a class="btn btn-sm btn-primary   pull-right" href="{{route('lists.income_reports_comparison')}}"> <i class="ft-rotate-cw ft-md"></i> </a>
                            </li>
                            <li>
                                <a class="btn btn-sm btn-danger box-shadow-2 round btn-min-width pull-right" href="{{route('lists.income_reports_comparison',['pdf'=>1,'filter'=>1, request()->fullUrl()])}}" target="_blank"> <i class="ft-pepper ft-md"></i> تحميل  ملف PDF</a>
                            </li>
                            <li>
                                <a class="btn btn-sm btn-success box-shadow-2 round btn-min-width pull-right" href="{{route('lists.income_reports_comparison',['excel'=>1,'filter'=>1, request()->fullUrl()])}}"> <i class="ft-pepper ft-md"></i> تحميل  ملف Excel</a>
                            </li>
                        </ul>
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
                                <div>
                                    <form action="{{route('lists.income_reports_comparison')}}" method="GET">

                                        <input type="hidden" name="filter" value="1">

                                        <div class="modal-body">
                                            <div class="row">
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
                                    <table id="tablecontents" class="table table-hover table-xl mb-0 sortable">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0">الايردات</th>
                                            <th class="border-top-0">الفرع/الشهر</th>
                                            @foreach($months as $month)
                                                <th class="border-top-0">{{$month}}</th>
                                            @endforeach
                                            <th>الاجمالي</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $branchsTotal = [];
                                                $monthsTotal = [];
                                            @endphp
                                            @foreach($branchsRecive as $branchRecive)
                                                @php
                                                    $total = 0;
                                                @endphp
                                                <tr>
                                                    <td></td>
                                                    <td>{{$branchRecive['name']}}</td>
                                                    @foreach($months as $month)
                                                        @php
                                                            $total +=$branchRecive[date_parse($month)['month']];
                                                            $amount = $branchRecive[date_parse($month)['month']];
                                                            if(!isset($monthsTotal[date_parse($month)['month']])){
                                                               $monthsTotal[date_parse($month)['month']] = $amount;
                                                            }else{
                                                               $monthsTotal[date_parse($month)['month']] += $amount;
                                                            }

                                                        @endphp
                                                        <td class="border-top-0">
                                                            {{$branchRecive[date_parse($month)['month']]}}
                                                        </td>
                                                    @endforeach
                                                    <td>
                                                        {{$total}}

                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td></td>
                                                <td>الاجمالي</td>
                                            @foreach($monthsTotal as $monthTotal)
                                                <td>{{$monthTotal}}</td>
                                            @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="tablecontents" class="table table-hover table-xl mb-0 sortable">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0">المصروفات</th>
                                            <th class="border-top-0">الفرع/الشهر</th>
                                            @foreach($months as $month)
                                                <th class="border-top-0">{{$month}}</th>
                                            @endforeach
                                            <th>الاجمالي</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $branchsTotal = [];
                                            $monthsPayTotal = [];
                                        @endphp
                                        @foreach($branchsPay as $branchPay)
                                            @php
                                                $total = 0;
                                            @endphp
                                            <tr>
                                                <td></td>
                                                <td>{{$branchPay['name']}}</td>
                                                @foreach($months as $month)
                                                    @php
                                                        $total +=$branchPay[date_parse($month)['month']];
                                                        $amount = $branchPay[date_parse($month)['month']];
                                                        if(!isset($monthsPayTotal[date_parse($month)['month']])){
                                                           $monthsPayTotal[date_parse($month)['month']] = $amount;
                                                        }else{
                                                           $monthsPayTotal[date_parse($month)['month']] += $amount;
                                                        }

                                                    @endphp
                                                    <td class="border-top-0">
                                                        {{$branchPay[date_parse($month)['month']]}}
                                                    </td>
                                                @endforeach
                                                <td>
                                                    {{$total}}

                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td>الاجمالي</td>
                                            @foreach($monthsPayTotal as $monthTotal)
                                                <td>{{$monthTotal}}</td>
                                            @endforeach
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table id="tablecontents" class="table table-hover table-xl mb-0 sortable">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0">الصافي</th>
                                            <th class="border-top-0">الفرع/الشهر</th>
                                            @foreach($months as $month)
                                                <th class="border-top-0">{{$month}}</th>
                                            @endforeach
                                            <th>الاجمالي</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $branchsTotal = [];
                                            $monthsClearTotal = [];
                                        @endphp
                                        @foreach($branchsClear as $branchClear)
                                            @php
                                                $total = 0;
                                            @endphp
                                            <tr>
                                                <td></td>
                                                <td>{{$branchClear['name']}}</td>
                                                @foreach($months as $month)
                                                    @php
                                                        $total +=$branchClear[date_parse($month)['month']];
                                                        $amount = $branchClear[date_parse($month)['month']];
                                                        if(!isset($monthsClearTotal[date_parse($month)['month']])){
                                                           $monthsClearTotal[date_parse($month)['month']] = $amount;
                                                        }else{
                                                           $monthsClearTotal[date_parse($month)['month']] += $amount;
                                                        }

                                                    @endphp
                                                    <td class="border-top-0">
                                                        {{$branchClear[date_parse($month)['month']]}}
                                                    </td>
                                                @endforeach
                                                <td>
                                                    {{$total}}

                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td>الاجمالي</td>
                                            @foreach($monthsClearTotal as $monthTotal)
                                                <td>{{$monthTotal}}</td>
                                            @endforeach
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table id="tablecontents" class="table table-hover table-xl mb-0 sortable">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0">الصافي</th>
                                            <th class="border-top-0">الفرع/الشهر</th>
                                            @foreach($months as $month)
                                                <th class="border-top-0">{{$month}}</th>
                                            @endforeach
                                            <th>الاجمالي</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            @php
                                                $total = 0;
                                                $totalPublicSalary = [];
                                            @endphp

                                                <td>رواتب عموميه</td>
                                                <td></td>
                                                @foreach($months as $month)
                                                    @php
                                                        $branchsTotal = [];
                                                        $monthsTotal = [0=>0];
                                                        $reciptsPublicSalary = \App\Models\Receipts::query()->whereMonth('due_date', date_parse($month)['month'])->where('to', 56)->sum('amount');

                                                        $total += $reciptsPublicSalary;

                                                        array_push($monthsTotal,$reciptsPublicSalary);
                                                        $totalPublicSalary[ date_parse($month)['month']] = $reciptsPublicSalary;

                                                    @endphp
                                                    <td class="border-top-0">
                                                        {{$reciptsPublicSalary}}
                                                    </td>
                                                @endforeach
                                                <td>
                                                    {{$total}}
                                                </td>
                                        </tr>
                                        <tr>
                                            @php
                                                $total = 0;
                                                $totalPublic = [];
                                            @endphp

                                            <td>مصاريف عموميه</td>
                                            <td></td>
                                            @foreach($months as $month)
                                                @php
                                                    $branchsTotal = [];
                                                    $reciptsPublicPay = \App\Models\Receipts::query()->whereMonth('due_date', date_parse($month)['month'])->where('to', 55)->sum('amount');

                                                    $total += $reciptsPublicPay;
                                                    $totalPublic[ date_parse($month)['month']] = $reciptsPublicPay;
                                                @endphp
                                                <td class="border-top-0">
                                                    {{$reciptsPublicPay}}
                                                </td>
                                            @endforeach
                                            <td>
                                                {{$total}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>صافي الربح</td>
                                            <td></td>
                                            @foreach($monthsClearTotal as $month => $monthClearTotal)
                                                <td class="border-top-0">
                                                    {{$monthClearTotal - ($totalPublic[$month]+$totalPublicSalary[$month])}}
                                                </td>
                                            @endforeach
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
