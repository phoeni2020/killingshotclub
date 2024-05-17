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
    <h2> تقرير المقارنات </h2>
@endif
@php
extract($allData);
@endphp
<table id="tablecontents" class="table table-hover table-xl mb-0 sortable">
    <thead>
    <tr>
        <th class="border-top-0">الايردات</th>
        <th class="border-top-0">الفرع/الشهر</th>
        @foreach($months as $month)
            <th class="border-top-0">{{$month}}</th>
        @endforeach
        <th>الاجمالي</th>
    </tr>
    </thead>
    <tbody>
    @php
        $branchsTotal = [];
        $monthsTotal = [];
    @endphp
    @foreach($branchsRecive as $branchRecive)
        @php
            $total = 0;
        @endphp
        <tr>
            <td></td>
            <td>{{$branchRecive['name']}}</td>
            @foreach($months as $month)
                @php
                    $total +=$branchRecive[date_parse($month)['month']];
                    $amount = $branchRecive[date_parse($month)['month']];
                    if(!isset($monthsTotal[date_parse($month)['month']])){
                       $monthsTotal[date_parse($month)['month']] = $amount;
                    }else{
                       $monthsTotal[date_parse($month)['month']] += $amount;
                    }

                @endphp
                <td class="border-top-0">
                    {{$branchRecive[date_parse($month)['month']]}}
                </td>
            @endforeach
            <td>
                {{$total}}

            </td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td>الاجمالي</td>
        @foreach($monthsTotal as $monthTotal)
            <td>{{$monthTotal}}</td>
        @endforeach
    </tr>
    </tbody>
</table>
<table id="tablecontents" class="table table-hover table-xl mb-0 sortable">
    <thead>
    <tr>
        <th class="border-top-0">المصروفات</th>
        <th class="border-top-0">الفرع/الشهر</th>
        @foreach($months as $month)
            <th class="border-top-0">{{$month}}</th>
        @endforeach
        <th>الاجمالي</th>
    </tr>
    </thead>
    <tbody>
    @php
        $branchsTotal = [];
        $monthsPayTotal = [];
    @endphp
    @foreach($branchsPay as $branchPay)
        @php
            $total = 0;
        @endphp
        <tr>
            <td></td>
            <td>{{$branchPay['name']}}</td>
            @foreach($months as $month)
                @php
                    $total +=$branchPay[date_parse($month)['month']];
                    $amount = $branchPay[date_parse($month)['month']];
                    if(!isset($monthsPayTotal[date_parse($month)['month']])){
                       $monthsPayTotal[date_parse($month)['month']] = $amount;
                    }else{
                       $monthsPayTotal[date_parse($month)['month']] += $amount;
                    }

                @endphp
                <td class="border-top-0">
                    {{$branchPay[date_parse($month)['month']]}}
                </td>
            @endforeach
            <td>
                {{$total}}

            </td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td>الاجمالي</td>
        @foreach($monthsPayTotal as $monthTotal)
            <td>{{$monthTotal}}</td>
        @endforeach
    </tr>
    </tbody>
</table>
<table id="tablecontents" class="table table-hover table-xl mb-0 sortable">
    <thead>
    <tr>
        <th class="border-top-0">الصافي</th>
        <th class="border-top-0">الفرع/الشهر</th>
        @foreach($months as $month)
            <th class="border-top-0">{{$month}}</th>
        @endforeach
        <th>الاجمالي</th>
    </tr>
    </thead>
    <tbody>
    @php
        $branchsTotal = [];
        $monthsClearTotal = [];
    @endphp
    @foreach($branchsClear as $branchClear)
        @php
            $total = 0;
        @endphp
        <tr>
            <td></td>
            <td>{{$branchClear['name']}}</td>
            @foreach($months as $month)
                @php
                    $total +=$branchClear[date_parse($month)['month']];
                    $amount = $branchClear[date_parse($month)['month']];
                    if(!isset($monthsClearTotal[date_parse($month)['month']])){
                       $monthsClearTotal[date_parse($month)['month']] = $amount;
                    }else{
                       $monthsClearTotal[date_parse($month)['month']] += $amount;
                    }

                @endphp
                <td class="border-top-0">
                    {{$branchClear[date_parse($month)['month']]}}
                </td>
            @endforeach
            <td>
                {{$total}}

            </td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td>الاجمالي</td>
        @foreach($monthsClearTotal as $monthTotal)
            <td>{{$monthTotal}}</td>
        @endforeach
    </tr>
    </tbody>
</table>
<table id="tablecontents" class="table table-hover table-xl mb-0 sortable">
    <thead>
    <tr>
        <th class="border-top-0">الصافي</th>
        <th class="border-top-0">الفرع/الشهر</th>
        @foreach($months as $month)
            <th class="border-top-0">{{$month}}</th>
        @endforeach
        <th>الاجمالي</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        @php
            $total = 0;
            $totalPublicSalary = [];
        @endphp

        <td>رواتب عموميه</td>
        <td></td>
        @foreach($months as $month)
            @php
                $branchsTotal = [];
                $monthsTotal = [0=>0];
                $reciptsPublicSalary = \App\Models\Receipts::query()->whereMonth('due_date', date_parse($month)['month'])->where('to', 56)->sum('amount');

                $total += $reciptsPublicSalary;

                array_push($monthsTotal,$reciptsPublicSalary);
                $totalPublicSalary[ date_parse($month)['month']] = $reciptsPublicSalary;

            @endphp
            <td class="border-top-0">
                {{$reciptsPublicSalary}}
            </td>
        @endforeach
        <td>
            {{$total}}
        </td>
    </tr>
    <tr>
        @php
            $total = 0;
            $totalPublic = [];
        @endphp

        <td>مصاريف عموميه</td>
        <td></td>
        @foreach($months as $month)
            @php
                $branchsTotal = [];
                $reciptsPublicPay = \App\Models\Receipts::query()->whereMonth('due_date', date_parse($month)['month'])->where('to', 55)->sum('amount');

                $total += $reciptsPublicPay;
                $totalPublic[ date_parse($month)['month']] = $reciptsPublicPay;
            @endphp
            <td class="border-top-0">
                {{$reciptsPublicPay}}
            </td>
        @endforeach
        <td>
            {{$total}}
        </td>
    </tr>
    <tr>
        <td>صافي الربح</td>
        <td></td>
        @foreach($monthsClearTotal as $month => $monthClearTotal)
            <td class="border-top-0">
                {{$monthClearTotal - ($totalPublic[$month]+$totalPublicSalary[$month])}}
            </td>
        @endforeach
        <td>
            {{$total}}
        </td>
    </tr>
    </tbody>
</table>
