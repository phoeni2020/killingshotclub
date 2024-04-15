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
                                    <form action="{{route('lists.recipt_report')}}" method="GET">

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
                                            <th class="border-top-0">رقم</th>
                                            <th class="border-top-0">نوع</th>
                                            <th class="border-top-0">  اسم المستلم</th>
                                            <th class="border-top-0"> من </th>
                                            <th class="border-top-0"> الي </th>
                                            <th class="border-top-0">   المبلغ</th>
                                            <th class="border-top-0">   البيان</th>
                                            <th class="border-top-0">   رصيد الخزنه</th>

                                            <th class="border-top-0">   تاريخ العملية</th>
                                            <th class="border-top-0">   تاريخ الانشاء</th>
                                            <th class="border-top-0">   تاريخ التعديل</th>
                                        </tr>
                                        </thead>
                                        @php
                                            $total = 0;
                                            $savesBalance = [];
                                        @endphp
                                        <tbody>
                                        @forelse($receipts as $receipt )
                                            @php
                                            if($receipt->receipt_type == 1)
                                            {
                                                if(!array_key_exists($receipt->receiptTypeFrom?->id,$savesBalance)){
                                                    $savesBalance[$receipt->receiptTypeFrom?->id]= $receipt->amount;
                                                }else{
                                                    $savesBalance[$receipt->receiptTypeFrom?->id]+=$receipt->amount;
                                                }
                                            }
                                            else
                                            {
                                                if($receipt->type_of_amount == 'part')
                                                {
                                                    if(!array_key_exists($receipt->receiptTypeFrom?->id,$savesBalance)){
                                                        $savesBalance[$receipt->receiptTypeFrom?->id]= $receipt->paid;
                                                    }
                                                    else{
                                                        $savesBalance[$receipt->receiptTypeFrom?->id]+=$receipt->paid;
                                                    }
                                                }
                                                else
                                                {
                                                    if(!array_key_exists($receipt->receiptTypeFrom?->id,$savesBalance)){
                                                        $savesBalance[$receipt->receiptTypeFrom?->id]=$receipt->amount;
                                                    }
                                                    else{
                                                        $savesBalance[$receipt->receiptTypeFrom?->id]+=$receipt->amount;
                                                    }
                                                }
                                            }
                                            @endphp
                                            @if($receipt->receipt_type == 1)
                                                صرف
                                            @else
                                                وارد
                                            @endif
                                            <tr class="row1" data-id="{{ $receipt->id }}" >
                                                <td>{{$receipt->id}}</td>
                                                <td>
                                                    @if($receipt->receipt_type == 1)
                                                        صرف
                                                    @else
                                                        وارد
                                                    @endif
                                                </td>
                                                <td>{{$receipt->user->name}}</td>

                                                @php
                                                    $name ='';
                                                    if($receipt->type_of=='players'&&$receipt->receipt_type != 1){
                                                       $name = 'لاعبين';
                                                       //$name = is_null($receipt->player)?'--':$receipt->player->name;
                                                    }
                                                    else{
                                                        $name = $receipt->receiptTypeFrom?->name;
                                                    }
                                                    $remain = 0;
                                                    if($receipt->type_of_amount == 'part'){
                                                      $remain =  $receipt->amount - $receipt->paid;
                                                      $total+=$receipt->paid;
                                                    }else{
                                                         $total+=$receipt->amount;
                                                    }
                                                @endphp

                                                <td>{{$name ?? "---"}}</td>

                                                <td>
                                                    @if($receipt->type_of=='players'&&$receipt->receipt_type == 1)
                                                        لاعبين
                                                    @else
                                                        {{$receipt->receiptType->name ?? '---'}}
                                                    @endif

                                                </td>

                                                <td>
                                                    {{ $receipt->amount }}
                                                </td>
                                                <td>
                                                    {{ $receipt->statement }}
                                                </td>
                                                <td>
                                                    {{  $savesBalance[$receipt->receiptTypeFrom?->id] }}
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
                                        </tbody>
                                    </table>
                                    <center>
                                        <b>اجمالي قيمة الخزن :{{$total}}</b>
                                    </center>
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
