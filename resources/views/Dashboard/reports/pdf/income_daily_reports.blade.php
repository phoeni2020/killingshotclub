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
    <h2> تقرير الدخل اليومي</h2>
@endif
<table id="myTable" class="table table-hover table-xl mb-0 sortable">
    <thead>
    <tr>
        <th class="border-top-0">اسم الفرع</th>
        <th class="border-top-0">الاشتراكات</th>
        <th class="border-top-0">الايرادات الاخري</th>
        <th class="border-top-0">اجمالي الايرادات</th>
        <th class="border-top-0"></th>
        <th class="border-top-0">الايجار و الصيانه</th>
        <th class="border-top-0">المصروفات</th>
        <th class="border-top-0">المرتبات</th>
        <th class="border-top-0">اجمالي المصروفات</th>
        <th class="border-top-0"></th>
        <th class="border-top-0">صافي ربح / خسارة</th>
        <th class="border-top-0">مصاريف عموميه</th>
        <th class="border-top-0">رواتب عموميه</th>
        <th class="border-top-0">مصروفات نسب</th>
        <th class="border-top-0">صافي ربح</th>
    </tr>
    </thead>
    <tbody>
    @foreach($allData as $branch)
        <tr>
            @php
                $branch = array_pop($branch);
            @endphp
            <td>{{$branch['subscription'] > 0 ?$branch['branch'].'-'.$branch['sport_name']:$branch['branch']}}</td>
            <td>{{$branch['subscription']}}</td>
            <td>{{$branch['otherIncome']}}</td>
            <td>{{$branch['totalIncome']}}</td>
            <td></td>
            {{-- http://127.0.0.1:8000/admin/lists/income_list_month --}}
            <td>{{$branch['rentAndMaintance']}}</td>
            <td>{{$branch['expense']}}</td>
            <td>{{$branch['salary']}}</td>
            <td>{{$branch['totalExpense']}}</td>
            <td></td>
            <td>{{$branch['clearIncome']}}</td>
            <td>{{$branch['public_expnse']}}</td>
            <td>{{$branch['public_salary']}}</td>
            <td>0</td>
            <td>{{$branch['clearIncome']-($branch['public_salary']+$branch['public_expnse'])}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
