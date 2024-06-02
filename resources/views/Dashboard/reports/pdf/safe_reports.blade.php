<head>
    <style>
        table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        table td,table th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        table tr:nth-child(even){background-color: #f2f2f2;}

        table tr:hover {background-color: #ddd;}

        table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>
@if(request('pdf'))
    <h2> تقرير الخزنه</h2>
@endif
    <div class="table-responsive">
        <table class="table  table-bordered">
            <thead>
            <tr>
                <th class="border-top-0">رقم</th>
                <th class="border-top-0">نوع</th>
                <th class="border-top-0">  اسم المحرر</th>
                <th class="border-top-0"> من </th>
                <th class="border-top-0"> الي </th>
                <th class="border-top-0"> الفرع </th>
                <th class="border-top-0"> اسم اللاعب </th>
                <th class="border-top-0"> اسم المدرب </th>
                <th class="border-top-0"> نوع النشاط </th>
                <th class="border-top-0"> المستوي \ مده الايجار </th>
                <th class="border-top-0">   المبلغ</th>
                <th class="border-top-0">   نوع الايصال</th>
                <th class="border-top-0"> Visa Batch No</th>
                <th class="border-top-0">   رصيد الخزنه</th>
                <th class="border-top-0">   البيان</th>
                <th class="border-top-0">   تاريخ الانشاء</th>
            </tr>
            </thead>
            @php
                $total = $totalRecived = $totalPaid = 0;
                $savesBalance = [];
                $bankBalance = $visaBalance =0;
            @endphp
            <tbody>
            @forelse($allData as $receipt )
                @php
                    if($receipt->receipt_type == 1)
                    {
                       // dd($receipt);
                       $totalPaid -=  $receipt->amount;
                        if(!array_key_exists(1,$savesBalance)){
                            $savesBalance[$receipt->receiptTypeFrom?->id] = $receipt->amount;
                        }else{
                            $savesBalance[$receipt->receiptTypeFrom?->id] =$savesBalance[$receipt->receiptTypeFrom?->id]+$receipt->amount;
                        }
                    }
                    else
                    {
                        $totalRecived +=  $receipt->amount;
                        if($receipt->type_of_amount == 'part')
                        {
                            if(!array_key_exists($receipt->receiptTypeFrom?->id,$savesBalance)){

                                $savesBalance[$receipt->receiptTypeFrom?->id]= $receipt->paid;
                            }
                            else{
                                $savesBalance[$receipt->receiptTypeFrom?->id]+=$receipt->paid;
                            }
                            if($receipt->payment_type == 2){
                                $bankBalance += $receipt->amount;
                            }
                            elseif ($receipt->payment_type == 3){
                                $visaBalance += $receipt->amount;
                            }
                        }
                        else
                        {
                            if(!array_key_exists($receipt->to,$savesBalance)){
                                if($receipt->discount){
                                $savesBalance[$receipt->to]=$receipt->paid;
                                }else{
                                $savesBalance[$receipt->to]=$receipt->amount;
                                }
                            }
                            else{
                                if($receipt->discount){
                                $savesBalance[$receipt->to]+=$receipt->paid;
                                }else{
                                $savesBalance[$receipt->to]+=$receipt->amount;
                                }
                            }

                            if($receipt->payment_type == 2){
                                $bankBalance += $receipt->amount;
                            }
                            elseif ($receipt->payment_type == 3){
                                $visaBalance += $receipt->amount;
                            }
                        }
                    }
                    if(!is_null($receipt->trinar_id)){
                        $trinaName = \App\Models\User::find($receipt->trinar_id)->name;
                    }
                @endphp
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
                           $namePayer = $receipt->payer;
                           $namePlayer = is_null($receipt->player)?'لاعبين':$receipt->player->name;
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
                    <td>
                        @if(isset($name))
                            {{$name}}
                        @else
                            {{$namePayer ??'--'}}
                        @endif
                    </td>
                    <td>
                        @if($receipt->type_of=='players'&&$receipt->receipt_type == 1)
                            لاعبين
                        @else
                            {{$receipt->receiptType->name ?? '---'}}
                        @endif

                    </td>
                    <td>
                        @php
                            $branch = \App\Models\Branchs::query()->find($receipt->branch_id);

                                if(is_null($branch)){
                                    echo 'فرع محذوف';
                                }else{
                                    echo $branch->name;
                                }
                        @endphp
                    </td>

                    <td>
                        {{isset($namePlayer) ? $namePlayer : '---'}}
                    </td>
                    <td>
                        {{!is_null($receipt->trinar_id) ? $trinaName : '---'}}
                    </td>
                    <td>
                        @php
                            $name = null;
                              if(!is_null($receipt->package_id)&&!empty($receipt->package_id)){
                                   $pack = \App\Models\PriceList::query()->find($receipt->package_id);
                                     if(is_null($pack))
                                     {
                                         $name = 'الباكدج غير موجوده';
                                     }else{
                                         $name = $pack->name;
                                         echo \App\Models\Sports::query()->find($pack->sport_id)->name;
                                     }
                              }
                        @endphp
                    </td>
                    <td>
                        @if(!is_null($name))
                            {{$name}}
                        @endif
                    </td>
                    <td>
                        @if($receipt->discount)
                            {{ $receipt->paid ?? $receipt->amount }}
                        @else
                            {{ $receipt->paid ?? $receipt->amount }}
                        @endif
                    </td>

                    <td>
                        @if($receipt->payment_type == 1)
                            {{'دفع خزنة'}}
                        @elseif($receipt->payment_type == 2)
                            {{'بنك'}}
                        @else
                            {{'فيزا'}}
                        @endif
                    </td>

                    <td>
                        {{!is_null($receipt->serial_number) ? $receipt->serial_number :'--'}}
                    </td>

                    <td>
                        @if($receipt->receipt_type == 1)
                            {{  $savesBalance[$receipt->receiptTypeFrom?->id] }}
                        @else
                            {{$savesBalance[$receipt->to]}}
                        @endif
                    </td>

                    <td>
                        {{ $receipt->statement }}
                    </td>

                    <td>
                        {{ $receipt->created_at->format('Y-m-d') }}
                    </td>

                </tr>
            @empty
                <tr>
                    لايوجد ايصالات حاليا
                </tr>
            @endforelse
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-6">
                <h3>اجمالي رصيد الوارد :<i>{{$totalRecived}}</i></h3>
                <h3>اجمالي الرصيد المصروفات : <i>{{$totalPaid}}</i></h3>
                <h3>اجمالي رصيد الخزن : <i>{{$total}}</i></h3>
            </div>
            <div class="col-md-6">
                <h3>اجمالي البنك :<i>{{$bankBalance}}</i></h3>
                <h3>اجمالي الفيزا : <i>{{$visaBalance}}</i></h3>
            </div>
        </div>
    </div>

