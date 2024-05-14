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
                                                    {{ $receipt->amount }}
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

                                                            <a href="{{route('receipt.discount_approved', $receipt->id)}}" class="btn btn-info btn-sm round"> موافقه علي الخصم</a>

                                                           |

                                                            <a href="{{route('receipt.discount_disapproved', $receipt->id)}}" class="btn btn-info btn-sm round"> رفض الخصم</a>
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
                <!--/ Recent Transactions -->
            </div>
        </div>
    </div>
@endsection
