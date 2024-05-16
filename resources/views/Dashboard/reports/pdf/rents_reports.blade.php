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
    <h2> تقرير الايجار</h2>
@endif
<table class="table  table-bordered">
    <thead>
    <tr>
        <th class="border-top-0">اسم الاستاد</th>
        <th class="border-top-0">اجمالي ايردات الحجوزات</th>
        <th class="border-top-0">عدد مرات الحجوزات</th>
        <th class="border-top-0">عدد مرات الحجوزات الملغيه</th>
    </tr>
    </thead>
    <tbody>
    @php
        $totalAmount = 0;
    @endphp
    @foreach($allData as $staduimInfo)
        <tr>
            <td>{{$staduimInfo[0]->name}}</td>
            <td>
                @if(isset($staduimInfo['total']))
                    {{$staduimInfo['total']}}
                    @php
                        $totalAmount+=$staduimInfo['total']
                    @endphp
                @else
                    {{'0'}}
                @endif
            </td>
            <td>{{isset($staduimInfo['rent_times'])?$staduimInfo['rent_times']:'--'}}</td>
            <td>{{isset($staduimInfo['canceltion_rent_times'])? $staduimInfo['canceltion_rent_times']:'--'}}</td>
        </tr>
    @endforeach
    <tr>
        <td>اجمالي الايجارات</td>
        <td>{{$totalAmount}}</td>
    </tr>
    </tbody>
</table>
