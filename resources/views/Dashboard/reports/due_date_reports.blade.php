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
                                    <form action="{{route('lists.due_date_reports')}}" method="GET">

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
                                                        <select class="form-control" name="safe_id">
                                                            <option value="">اختر خزنة</option>
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
                                                        <input class="form-control" type="date" name="toDate"
                                                               value="{{request('toDate')}}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1"> انواع الايصالات </label>

                                                        <select class=" form-control"  id="" name="type" >
                                                            <option value=""> اختار </option>
                                                            <option value="Save_money"> خزنه </option>
                                                            <option value="bank"> بنك </option>
                                                            <option value="anther_contact"> جهات اخري </option>
                                                            <option value="Custody"> عهده </option>
                                                        </select>


                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1"> انواع الايصالات </label>
                                                        <select class=" form-control"  id="" name="type_income" >
                                                            <option value=""> اختار </option>
                                                            <option value="1"> صرف </option>
                                                            <option value="2"> وارد </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1"> بحث برقم الايصال </label>
                                                        <input type="number" name="recipt_id" class="form-control" >
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
                                <ul class="list-inline mb-0">
                                    <li>
                                        <a class="btn btn-sm btn-primary   pull-right" href="{{route('lists.due_date_reports')}}"> <i class="ft-rotate-cw ft-md"></i> </a>
                                    </li>
                                    <li>
                                        <a class="btn btn-sm btn-danger box-shadow-2 round btn-min-width pull-right" href="{{route('lists.due_date_reports',['pdf'=>1,'filter'=>1, request()->fullUrl()])}}" target="_blank"> <i class="ft-pepper ft-md"></i> تحميل  ملف PDF</a>
                                    </li>
                                    <li>
                                        <a class="btn btn-sm btn-success box-shadow-2 round btn-min-width pull-right" href="{{route('lists.due_date_reports',['excel'=>1,'filter'=>1, request()->fullUrl()])}}"> <i class="ft-pepper ft-md"></i> تحميل  ملف Excel</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-content">
                                <div class="table-responsive">
                                    <table id="tablecontents" class="table table-hover table-xl mb-0 sortable">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0">سيريال</th>
                                            <th class="border-top-0">  اسم المستلم</th>
                                            <th class="border-top-0"> من </th>
                                            <th class="border-top-0"> الي </th>

                                            <th class="border-top-0">   كلي\جزئي</th>
                                            <th class="border-top-0">    نوع الخصم</th>
                                            <th class="border-top-0">   نسبة الخصم</th>
                                            <th class="border-top-0">   المبلغ قبل الخصم</th>
                                            <th class="border-top-0">   المدفوع</th>
                                            <th class="border-top-0">   المتبقي</th>
                                            <th class="border-top-0">   المبلغ</th>
                                            <th class="border-top-0">   تاريخ الاستحقاق</th>
                                            <th class="border-top-0">   تاريخ الايصال</th>
                                            <th class="border-top-0">   تاريخ الانشاء</th>
                                            <th class="border-top-0">   تاريخ التعديل</th>
                                        </tr>
                                        </thead>
                                        @php
                                            $total = $totalRecived = $totalPaid = 0;
                                            $savesBalance = [];
                                            $totalBalance = 0;
                                            $totalRemain = 0;
                                            $totalAmount = 0;
                                            $totalAmount2 = 0;
                                            $totalDiscount = 0;
                                        @endphp
                                        <tbody>
                                        @forelse($receipts as $receipt )

                                            <tr class="row1" data-id="{{ $receipt->id }}" >
                                                <td>{{$receipt->id}}</td>
                                                <td>{{$receipt->user->name}}</td>

                                                @php
                                                    $name ='';
                                                    if($receipt->type_of=='players'){
                                                       $name = is_null($receipt->player)?'--':$receipt->player->name;
                                                    }
                                                    else{
                                                        $name = $receipt->receiptTypeFrom?->name;
                                                    }


                                                    $remain = 0;
                                                    if($receipt->type_of_amount == 'part'){
                                                      $remain =  $receipt->amount - $receipt->paid;
                                                    }
                                                @endphp
                                                <td>{{$name ?? "---"}}</td>
                                                <td>{{$receipt->receiptType->name ?? '---'}}</td>

                                                <td>{{ $receipt->type_of_amount == '' ? 'كلي ' : 'جزئي' }}</td>
                                                @php
                                                    switch ($receipt->discount_type){
                                                        case 'none' :
                                                            $discountType = 'لا يوجد خصم';
                                                            $discount = 'لا يوجد خصم';
                                                            $discountAmount = 'لا يوجد خصم';
                                                            break;
                                                        case 'amount':
                                                            $discountType = 'خصم مبلغ مباشر';
                                                            $discount = $receipt->discount . ' EGP/ جنيه';
                                                            $discountAmount = $receipt->discount_amount_value . ' EGP/ جنيه';
                                                            break;
                                                        case 'percentage' :
                                                             $discountType = 'خصم نسبة مئوية';
                                                             $discount = $receipt->discount . '%';
                                                             $discountAmount = $receipt->discount_amount_value . ' EGP/ جنيه';
                                                            break;
                                                    }
                                                @endphp
                                                <td>
                                                    {{$discountType}}
                                                </td>
                                                <td>{{ $discount }}</td>

                                                <td>{{ $discountAmount }}</td>

                                                <td>
                                                    @php
                                                        $totalAmount += $receipt->paid ?? $receipt->amount;
                                                        $totalDiscount += !is_null($receipt->discount_amount_value) ?$receipt->discount_amount_value :0;
                                                        $totalRemain += $remain;
                                                        $totalAmount2 += $receipt->amount;
                                                    @endphp
                                                    {{ $receipt->paid ?? $receipt->amount }}
                                                </td>


                                                <td>

                                                    {{ $remain }}
                                                </td>
                                                <td>
                                                    {{ $receipt->amount }}
                                                </td>
                                                <td>
                                                    {{ $receipt->due_date}}
                                                </td>
                                                <td>
                                                    {{ $receipt->date_receipt->format('Y-m-d') }}
                                                </td>
                                                <td>
                                                    {{ $receipt->created_at->format('Y-m-d') }}
                                                </td>
                                                <td>
                                                    {{ $receipt->updated_at->format('Y-m-d') }}
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                لايوجد ايصالات حاليا
                                            </tr>
                                        @endforelse
                                            <tr>
                                                <td colspan="8">الاجمالي</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>{{$totalDiscount}}</td>
                                                <td>{{$totalRemain}}</td>
                                                <td>{{$totalAmount}}</td>
                                                <td>{{$totalAmount2}}</td>
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
