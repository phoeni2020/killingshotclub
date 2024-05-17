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
    <h2> تقرير التحليل المالي</h2>
@endif
<table id="myTable" class="table table-hover table-xl mb-0 sortable">
    <thead>
    <tr>
        <th class="border-top-0">الشهر</th>
        <th class="border-top-0">الاجمالي</th>
        <th class="border-top-0">إيجارات</th>
        <th class="border-top-0">أجور و مرتبات</th>
    </tr>
    </thead>
    <tbody>
    @foreach($allData['branchesSports'] as $key =>$branch)
        <tr>
            <td>{{$allData['months'][$key]}}</td>
            <td>{{$branch['totalExpense']}}</td>
            <td>{{$branch['rentAndMaintance']}}</td>
            <td>{{$branch['salary']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
