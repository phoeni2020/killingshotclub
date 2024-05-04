@extends('Dashboard.includes.admin')
@section('content')

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
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">

                                        <li>
                                            {{--                                                <a class="btn btn-sm btn-success box-shadow-2 round btn-min-width pull-right" href="{{route('receipt-type.create')}}"> <i class="ft-plus ft-md"></i> اضافة نوع ايصال</a>--}}
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
                                        @forelse($settlements as $settlement )

                                            <tr class="row1" data-id="{{ $settlement->id }}" >
                                                <td>{{$settlement->user->name }}</td>

                                                <td>
                                                    @dd($settlement->receipt_pay)
                                                    {{$settlement->receipt_pay->receiptType->name }}
                                                </td>

                                                <td>
                                                    {{$settlement->price }}
                                                </td>

                                                <td>
                                                    @php
                                                       $expenses = \App\Models\CustodyExpense::where('custody_id',$settlement->id)->sum('price');
                                                       $branch = \App\Models\Branchs::find($settlement->receipt_pay->branch_id );
                                                    @endphp
                                                    {{$expenses}}
                                                </td>
                                                <td>
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

