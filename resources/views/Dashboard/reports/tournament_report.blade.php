@extends('Dashboard.includes.admin')
@section('content')

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">قسم المسابقات  </h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/admin')}}">لوحة التحكم</a></li>
                                <li class="breadcrumb-item active">كل  المسابقات </li>
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
                                <div class="card-header">
                                    <h4 class="card-title">  المسابقات ({{$tournaments->count()}})</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li>
                                                <a class="btn btn-sm btn-primary   pull-right" href="{{route('lists.tournament_reports')}}"> <i class="ft-rotate-cw ft-md"></i> </a>
                                            </li>
                                            <li>
                                                <a class="btn btn-sm btn-danger box-shadow-2 round btn-min-width pull-right" href="{{route('lists.tournament_reports',['pdf'=>1,'filter'=>1, request()->fullUrl()])}}" target="_blank"> <i class="ft-pepper ft-md"></i> تحميل  ملف PDF</a>
                                            </li>
                                            <li>
                                                <a class="btn btn-sm btn-success box-shadow-2 round btn-min-width pull-right" href="{{route('lists.tournament_reports',['excel'=>1,'filter'=>1, request()->fullUrl()])}}"> <i class="ft-pepper ft-md"></i> تحميل  ملف Excel</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="table-responsive">
                                    <table id="myTable" class="table table-hover table-xl mb-0 sortable">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0">  اسم المسابقه</th>
                                            <th class="border-top-0"> تاريخ المسابقه</th>
                                            <th class="border-top-0"> اشتراك </th>
                                            <th class="border-top-0"> التكلفه</th>
                                            <th class="border-top-0"> اسم اللاعب</th>
                                            <th class="border-top-0"> المركز</th>
                                            <th class="border-top-0"> تم الدفع</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $totalSubscribeValue = $cost = 0;
                                        @endphp
                                        @forelse($tournaments as $tournament )
                                            @php
                                                $tournamentInfo = \App\Models\Tournaments::find($tournament->tournament_id);
                                                $tournamentInfoPlayer = \App\Models\TournamentPlayersDetails::
                                                where('tournament_id',$tournament->tournament_id)
                                                ->where('player_id',$tournament->player_id)->get()->toArray();
                                                $player = \App\Models\Players::find($tournament->player_id);
                                                $totalSubscribeValue += $tournamentInfo->subscribe_value;
                                                $cost += $tournamentInfo->cost;
                                            @endphp
                                            <tr class="row1" data-id="{{ $tournament->id }}" >
                                                <td>{{$tournamentInfo->name}}</td>
                                                <td>{{$tournamentInfo->date}}</td>
                                                <td>{{$tournamentInfo->subscribe_value}}</td>
                                                <td>{{$tournamentInfo->cost}}</td>
                                                <td>{{$player?->name}}</td>
                                                <td>{{!empty($tournamentInfoPlayer)? $tournamentInfoPlayer->place:'لا توجد معلومات' }}</td>
                                                <td>{{$tournamentInfo->paid? 'تم الدفع':'لم يتم الدفع'}}</td>
                                            </tr>

                                        @empty
                                        <tr>
                                            <td colspan="6">
                                                لايوجد  مسابقات  حاليا

                                            </td>
                                        </tr>
                                        @endforelse
                                        <tr>
                                            <td colspan="2">المجموع</td>
                                            <td>{{$totalSubscribeValue}}</td>
                                            <td>{{$cost}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--                @if($tournaments->hasPages())--}}
{{--                    {{$tournaments->appends(request()->input())->links('pagination::bootstrap-4')}}--}}
{{--                @endif--}}
                <!--/ Recent Transactions -->
            </div>
        </div>
    </div>
@endsection
@section('script')

    <script>
        let table = new DataTable('#myTable');
    </script>
@endsection

