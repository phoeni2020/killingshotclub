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
                <form action="{{route('receipt.index')}}" method="GET">
                    @csrf
                    <input type="hidden" name="filter" value="1">

                    <div class="modal-body">

                        <div class="row">
                            <div class="col-md-12 mt-2" >
                                <div class="form-group">
                                    <label> تاريخ الانشاء</label>
                                    <input class="from_type " type="radio"  checked  id="" name="type_date" value="created_at">
                                    <label> تاريخ الايصال</label>
                                    <input class="from_type " type="radio"    id="" name="type_date" value="date_receipt">
                                    <label>تاريخ التعديل </label>
                                    <input class=" from_type" type="radio" id="" name="type_date" value="updated_at">

                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="" class="control-label mb-1">من التاريخ:</label>
                                    <input   class="form-control" type="date" name="fromDate" >
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="" class="control-label mb-1"> الي التاريخ </label>
                                    <input   class="form-control" type="date" name="toDate" >
                                </div>
                            </div>
                                <div class="col-md-2 mt-2" >
                                    <div class="form-group">
                                        <label>من الاعبين</label>
                                        <input class="from_type " type="radio"  checked  id="players" name="from_type" value="players">
                                        <label>اخري </label>
                                        <input class=" from_type" type="radio" id="others" name="from_type" value="others">

                                    </div>
                                </div>
                                <div class="col-md-4"  style="" id="from_players">
                                    <div class="form-group">
                                        <label for="projectinput2">  من  </label>
                                        <select class=" form-control"  name="from_player" id="player_id" >
                                            <option value=""> اختار </option>

                                            @foreach($players as $player)
                                                <option   value="{{$player->id}}">{{$player->name}}</option>

                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-4" style="display: none" id="from_others">
                                    <div class="form-group">
                                        <label for="projectinput2">  من  </label>
                                        <select class="form-control"  name="from_others" id="others_id">
                                            <option value=""> اختار </option>

                                            @foreach($receiptTypes as $type)
                                                <option value="{{$type->id}}">{{$type->name}}</option>

                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="projectinput2">  الي   </label>
                                    <select class=" form-control"  name="to" >
                                        <option value=""> اختار </option>

                                        @foreach($receiptTypes as $type)
                                            <option value="{{$type->id}}">{{$type->name}}</option>

                                        @endforeach
                                    </select>

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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <input type="submit" value="Confirm"  class="btn btn-primary">
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
                    <h3 class="content-header-title">قسم الايصالات</h3>
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{url('/admin')}}">لوحة التحكم</a></li>
                                <li class="breadcrumb-item active">كل الايصالات</li>
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
                                <h4 class="card-title">الايصالات ({{$receipts->total()}})</h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        @if(request('filter') && !$receipts->isEmpty())
                                            <li>
                                                <a class="btn btn-sm btn-primary   pull-right" href="{{route('receipt.index')}}"> <i class="ft-rotate-cw ft-md"></i> </a>
                                            </li>
                                        <li>
                                            <a class="btn btn-sm btn-danger box-shadow-2 round btn-min-width pull-right" href="{{route('receipt.index',['pdf'=>1, request()->fullUrl()])}}" target="_blank"> <i class="ft-pepper ft-md"></i> تحميل  ملف PDF</a>
                                        </li>
                                            <li>
                                                <a class="btn btn-sm btn-success box-shadow-2 round btn-min-width pull-right" href="{{route('receipt.index',['excel'=>1, request()->fullUrl()])}}"> <i class="ft-pepper ft-md"></i> تحميل  ملف Excel</a>
                                            </li>

                                        @endif
                                        <li>

                                            <button type="button" class="btn btn-sm btn-warning  box-shadow-2 round btn-min-width pull-right" data-toggle="modal" data-target="#mediumModal">
                                                <i class="ft-report ft-md"></i>
                                                تقرير
                                            </button>
                                        </li>
                                        <li>
                                            <a class="btn btn-sm btn-success box-shadow-2 round btn-min-width pull-right" href="{{route('receipt.create')}}"> <i class="ft-plus ft-md"></i> اضافة ايصال جديد</a>
                                        </li>
                                </div>
                                <h4 class="card-title">الفلتر </h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div>
                                    <form action="{{route('receipt.index')}}" method="GET">

                                        <input type="hidden" name="filter" value="1">

                                        <div class="modal-body">


                                            <div class="row">
                                                <div class="col-md-3 mt-2">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1">بحث برقم الاذن</label>
                                                       <input type="text" name="recipt_id">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mt-2">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1">بحث باسم اللاعب</label>
                                                       <input type="text" name="name">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mt-2">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1">بحث بايميل اللاعب</label>
                                                       <input type="email" name="email">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mt-2">
                                                    <div class="form-group">
                                                        <label for="" class="control-label mb-1">بحث برقم هاتف اللاعب</label>
                                                       <input type="text" name="phone">
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
                                            <th class="border-top-0">   تاريخ الايصال</th>
                                            <th class="border-top-0">   تاريخ الانشاء</th>
                                            <th class="border-top-0">   تاريخ التعديل</th>

                                            <th class="border-top-0">التحكم</th>
                                        </tr>
                                        </thead>
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

                                                    <td>{{ $receipt->paid ?? $receipt->amount }}</td>


                                                    <td>
                                                     {{ $remain }}
                                                    </td>
                                                    <td>
                                                        @if($receipt->discount)
                                                            {{ $receipt->paid ?? $receipt->amount }}
                                                        @else
                                                            {{ $receipt->paid ?? $receipt->amount }}
                                                        @endif
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


                                                    <td class="text-truncate">
                                                        <div class="btn-group" role="group" aria-label="Basic example">
                                                            @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('Incoming-receipts-update')  )

                                                            <a href="{{route('receipt.edit', $receipt->id)}}" class="btn btn-info btn-sm round"> تعديل</a>
                                                            @endif
                                                                @if( auth()->user()->hasRole(['administrator','superadministrator']) || auth()->user()->hasPermission('Incoming-receipts-delete')  )

                                                            <form action="{{route('receipt.destroy' ,$receipt->id)}}" method="POST" class="btn-group">
                                                                @csrf @method('delete')
                                                                <button

                                                                    class="btn btn-danger btn-sm round"
                                                                    onclick="return confirm('حذف هذا الايصال سيقوم بحذف جميع الفروع و الالعاب المتعلقه به!! هل انت متاكد من الحذف ؟')"
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
                @if($receipts->hasPages())
                    {{$receipts->appends(request()->input())->links('pagination::bootstrap-4')}}
                @endif
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
