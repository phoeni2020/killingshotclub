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
    <h2> تقرير الاشتراكات </h2>
@endif
<table class="table  table-bordered">
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
    @forelse($allData as $reportData)

        @foreach($reportData->players as $player)
            <tr class="row1">
                @php
                    $countManyTimesForPLayer = \App\Models\EventTrainerPlayers::query()
                    ->where('player_id',$player->player_id)->get()->toArray();

                        $player_price_list = $player->players?->playerPriceLists?->where('sport_id',$reportData->sport_id)
                    ->where('level_id',$reportData->level_id)->first();

                        $amount = $player_price_list?->price;

                        $paid = $player->players?->receipts
                        ->whereNotNull('paid')
                        ->sum('paid');

                        $totalNeeded = $player->players?->receipts
                        ->whereNotNull('paid')
                        ->sum('amount');
                        //dd($totalNeeded);

                        $paidAmount = $player->players?->receipts
                        ->whereNull('paid')
                        ->sum('amount');
                        $paid = $paidAmount + $paid;
                        if($totalNeeded == 0){
                            $totalRemain = 0;
                        }else{
                            $totalRemain = $paid - $totalNeeded;
                        }
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
