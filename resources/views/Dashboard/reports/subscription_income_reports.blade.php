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
                <form action="{{route('reports.subscription_reports')}}" method="GET">
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
                    <h3 class="content-header-title">قسم التقارير</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/admin')}}">لوحة التحكم</a></li>
                                <li class="breadcrumb-item active">كل التقارير</li>
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
                                <h4 class="card-title">التقارير</h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div>
                                    <form action="{{route('reports.subscription_reports')}}" method="GET">

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
                                                        <label for="" class="control-label mb-1"> اللاعب </label>
                                                        <select class="form-control" name="player">
                                                            <option value="">اختر</option>
                                                            @foreach($players as $player)
                                                                <option value="{{$player->id}}"
                                                                    {{ $player->id == request('player') ? 'selected' : '' }}
                                                                >{{$player->name}}</option>
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
                                    <table id="tablecontents" class="table table-hover table-xl mb-0 sortable">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0 px-4">التاريخ</th>
                                            <th class="border-top-0 px-4">اليوم</th>
                                            <th class="border-top-0">الفرع</th>
                                            <th class="border-top-0"> أسم المدرب</th>
                                            <th class="border-top-0"> أسم اللاعب</th>
                                            <th class="border-top-0"> المستوي</th>
                                            <th class="border-top-0"> الاشتراك المقرر</th>
                                            <th class="border-top-0"> حاله الاشتراك</th>
                                            <th class="border-top-0">الاشتراك المسدد</th>
                                            <th class="border-top-0">عدد مرات التسجيل</th>
                                            <th class="border-top-0">الاشتراك المتبقي</th>
                                            <th class="border-top-0">رقم التليفون</th>
                                            <th class="border-top-0">تاريخ السداد</th>
                                            <th class="border-top-0">رقم الايصال</th>
                                            <th class="border-top-0">ملاحظات</th>
                                            <th class="border-top-0">تاريخ الميلاد</th>
                                            <th class="border-top-0">تاريخ الالتحاق</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                        $totalPaid = $totalSubs = $totalRemain = 0;
                                        @endphp
                                        @forelse($reportsData as $reportData)

                                            @foreach($reportData->players as $player)
                                                <tr class="row1">
                                                    @php
                                                        $countManyTimesForPLayer = \App\Models\EventTrainerPlayers::query()
                                                        ->where('player_id',$player->player_id)->get()->toArray();

                                                            $player_price_list = $player->players?->playerPriceLists?->where('sport_id',$reportData->sport_id)
                                                        ->where('level_id',$reportData->level_id)->first();

                                                            $amount = $player_price_list?->price;

                                                            $paid = $player->players?->receipts
                                                            ->where('package_id',$player_price_list?->id)
                                                            ->whereNotNull('paid')
                                                            ->sum('paid');

                                                            $paidAmount = $player->players?->receipts
                                                            ->where('package_id',$player_price_list?->id)
                                                            ->whereNull('paid')
                                                            ->sum('amount');
                                                            $paid = $paidAmount + $paid;

                                                            $totalNeeded = $player_price_list?->price*count($countManyTimesForPLayer);
                                                            $totalRemain = $totalNeeded - $paid;
                                                    @endphp
                                                    <td>{{$reportData->date}}</td>
                                                    <td>@lang('validation.'.$reportData->day)</td>
                                                    <td>{{$reportData->stadiums->name}}</td>
                                                    <td>{{$reportData->traniers->name}}</td>
                                                    <td>{{$player->players?->name}}</td>
                                                    <td>{{$reportData->level->name}}</td>
                                                    <td>{{$player_price_list?->price}}</td>
                                                    <td>{{$player->players?->receipts->where('package_id',$player_price_list?->id)->first() ? 'مشترك' : 'لم يسدد'}}</td>
                                                    <td>{{is_null($paid)? 0:$paid}}</td>
                                                    <td>{{count($countManyTimesForPLayer)}}</td>
                                                    <td>{{$totalRemain}}</td>
                                                    <td>{{$player->players?->father_phone}}</td>
                                                    <td>{{$player->players?->receipts->where('package_id',$player_price_list?->id)->first()?->created_at}}</td>
                                                    <td>{{$player->players?->receipts->where('package_id',$player_price_list?->id)->first()?->id}}</td>
                                                    <td>{{$player->players?->receipts->where('package_id',$player_price_list?->id)->first()?->statement}}</td>
                                                    <td>{{\Carbon\Carbon::parse($player->players?->birth_day)->format('d/m/Y')}}</td>
                                                    <td>{{\Carbon\Carbon::parse($reportData->created_at)->format('d/m/Y')}}</td>

                                                </tr>
                                            @endforeach

                                        @empty
                                            <tr>
                                                لايوجد ايصالات حاليا
                                            </tr>
                                        @endforelse

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