@extends('Dashboard.includes.admin')
@section('content')

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
                <form action="{{route('lists.custody_reports')}}" method="GET">
                    @csrf
                    <input type="hidden" name="filter" value="1">

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <input type="submit" value="Confirm" class="btn btn-primary">
                        {{-- <button type="button"  class="btn btn-primary">Confirm</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <h3 class="content-header-title">قسم التقارير</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/admin')}}">لوحة التحكم</a></li>
                                <li class="breadcrumb-item active">تفرير العهد</li>
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
                                <h4 class="card-title">تقرير الاشتراكات</h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        @if(request('filter'))
                                            <li>
                                                <a class="btn btn-sm btn-primary   pull-right" href="{{route('lists.custody_reports')}}"> <i class="ft-rotate-cw ft-md"></i> </a>
                                            </li>
                                            <li>
                                                <a class="btn btn-sm btn-danger box-shadow-2 round btn-min-width pull-right" href="{{route('lists.custody_reports',['pdf'=>1, request()->fullUrl()])}}" target="_blank"> <i class="ft-pepper ft-md"></i> تحميل  ملف PDF</a>
                                            </li>
                                            <li>
                                                <a class="btn btn-sm btn-success box-shadow-2 round btn-min-width pull-right" href="{{route('lists.custody_reports',['excel'=>1, request()->fullUrl()])}}"> <i class="ft-pepper ft-md"></i> تحميل  ملف Excel</a>
                                            </li>

                                        @endif

                                        <li>

                                            <button type="button" class="btn btn-sm btn-warning  box-shadow-2 round btn-min-width pull-right" data-toggle="modal" data-target="#mediumModal">
                                                <i class="ft-report ft-md"></i>
                                                تقرير
                                            </button>
                                        </li>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="table-responsive">
                                    <table id="tablecontents" class="table table-hover table-xl mb-0 sortable">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0">  اسم مستلم العهده  </th>
                                            <th class="border-top-0">  العهده </th>
                                            <th class="border-top-0"> اجمالي العهده</th>
                                            <th class="border-top-0">  اجمالي المصروفات من العهده</th>
                                            <th class="border-top-0">  المتبقي من العهده</th>
                                            <th class="border-top-0">  الي  </th>
                                            <th class="border-top-0">  تمت تسوية العهده ؟  </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $expensesTotal = 0;
                                            $priceTotal = 0;
                                            $clearTotal = 0;
                                        @endphp
                                        @forelse($settlements as $settlement )

                                            <tr class="row1" data-id="{{ $settlement->id }}" >
                                                <td>{{$settlement->user->name }}</td>

                                                <td>
                                                    {{$settlement->receipt_pay->receiptType->name }}
                                                </td>

                                                <td>
                                                    @php
                                                        $priceTotal+=$settlement->price;
                                                    @endphp
                                                    {{$settlement->price }}
                                                </td>

                                                <td>
                                                    @php
                                                       $expenses = \App\Models\CustodyExpense::where('custody_id',$settlement->id)->sum('price');
                                                       $branch = \App\Models\Branchs::find($settlement->receipt_pay->branch_id );
                                                       $expensesTotal+=$expenses;
                                                    @endphp
                                                    {{$expenses}}
                                                </td>
                                                <td>
                                                    @php
                                                        $clearTotal+= $settlement->price -  $expenses;
                                                    @endphp
                                                    {{$settlement->price -  $expenses }}
                                                </td>
                                                <td>

                                                    {{$branch->name}}
                                                </td>
                                                <td>
                                                    {{$settlement->requested ? 'Yes' : 'No'}}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                لايوجد اي عهد
                                            </tr>
                                        @endforelse
                                        <tr>
                                            <td colspan="2" >مجموع</td>
                                            <td colspan="1">{{$priceTotal}}</td>
                                            <td colspan="1">{{$expensesTotal}}</td>
                                            <td colspan="1">{{$clearTotal}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($settlements->hasPages())
                    {{$settlements->appends(request()->input())->links('pagination::bootstrap-4')}}
                @endif
                <!--/ Recent Transactions -->
            </div>
        </div>
    </div>
@endsection

