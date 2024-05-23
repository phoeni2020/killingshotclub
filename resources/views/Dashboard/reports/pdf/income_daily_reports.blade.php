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
        <th class="border-top-0">اسم الخزنه</th>
        <th class="border-top-0">صافي رصيد الخزنه</th>
    </tr>
    </thead>
    <tbody>
    @php
        $clearIncome =0;
    @endphp
    @foreach($allData as $branch)
        @php
            $clearIncome += $branch[0]['income'] - $branch[0]['expense'];
        @endphp
        <tr>
            <td>{{$branch[0]['branch']}}</td>
            <td>{{$branch[0]['safe_name']}}</td>
            <td>{{$branch[0]['income'] - $branch[0]['expense']}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="2">المجموع</td>
        <td>{{$clearIncome}}</td>
    </tr>
    </tbody>
</table>
