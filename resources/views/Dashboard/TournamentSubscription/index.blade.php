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
                                <h4 class="card-title">  المسابقات ({{ $tournaments ->count() }})</h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">

                                            <li>
                                                <a class="btn btn-sm btn-success box-shadow-2 round btn-min-width pull-right" href="{{route('tournament-subscription.create')}}"> <i class="ft-plus ft-md"></i> اضافة  مسابقه جديد</a>
                                            </li>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="table-responsive">
                                    <table id="myTable" class="table table-hover table-xl mb-0 sortable">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0">  اسم المسابقه</th>
                                            <th class="border-top-0"> الفرع</th>

                                            <th class="border-top-0">  الاعبين</th>


                                            <th class="border-top-0">التحكم</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($tournaments as $tournament )

                                            <tr class="row1" data-id="{{ $tournament->id }}" >
                                                <td>
                                                    {{$tournament->name}}
                                                </td>
                                                <td>

                                                    @foreach($tournament->tournament_branches as $b)

                                                        <strong> ,{{$b->branches->name}}  </strong>
                                                    @endforeach
                                                </td>





                                                <td>

                                                    @foreach($tournament->tournament_subscriptions as $player)
                                                        <strong> ,{{isset($player->players[0])?$player->players[0]->name:'Deleted Player'}}  </strong>
                                                    @endforeach
                                                </td>

                                                <td class="text-truncate">
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('tournament-update') )

                                                        <a href="{{route('tournament-subscription.edit', $tournament->id)}}" class="btn btn-info btn-sm round"> تعديل</a>
                                                        @endif
                                                            @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('tournament-delete') )

                                                            <form action="{{route('tournament-subscription.destroy' ,$tournament->id)}}" method="POST" class="btn-group">
                                                                @csrf @method('delete')
                                                                <button

                                                                    class="btn btn-danger btn-sm round"
                                                                    onclick="return confirm(' هل انت متاكد من الحذف ؟')"
                                                                >
                                                                    حذف
                                                                </button>
                                                            </form>
                                                            @endif
                                                    </div>
                                                </td>
                                            </tr>

                                        @empty
                                        <tr>
                                            <td colspan="6">
                                                لايوجد  مسابقات  حاليا

                                            </td>
                                        </tr>
                                        @endforelse

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

