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
    <h2> قائمة الدخل  </h2>
@endif
<table id="myTable" class="table table-hover table-xl mb-0 sortable">
    <thead>
    <tr>
        <th class="border-top-0"> البند</th>
        <th class="border-top-0"> المبلغ</th>
    </tr>
    </thead>
    <tbody>

    <tr class="row1">
        <td>
            <strong>الايردات</strong>
        </td>
        <td></td>
    </tr>
    <tr class="row2">
        <td>
            <strong>الاشتراكات</strong>
        </td>
        <td>
            {{$allData['subscriptionsSum']}}
        </td>
    </tr>
    <tr class="row3">
        <td>
            <strong>ايرادات اخري </strong>
        </td>
        <td>
            {{$allData['otherIncome']}}
        </td>
    </tr>
    <tr class="row4">
        <td>
            <strong>اجمالي ايرادات</strong>
        </td>
        <td>
            {{$allData['subscriptionsSum'] + $allData['otherIncome'] }}
        </td>
    </tr>
    <tr class="row3">
        <td></td>
        <td></td>
    </tr>
    <tr class="row5">
        <td>
            <strong>ايجارات و صيانه</strong>
        </td>
        <td>
            {{$allData['rentAndMaintance']}}
        </td>
    </tr>
    <tr class="row6">
        <td>
            <strong>الرواتب</strong>
        </td>
        <td>
            {{$allData['otherExpense']}}
        </td>
    </tr>
    <tr class="row6">
        <td>
            <strong>المصروفات</strong>
        </td>
        <td>
            {{$allData['otherExpense']}}
        </td>
    </tr>

    <tr class="row7">
        <td>
            <strong>الاجمالي</strong>
        </td>
        <td>
            {{$allData['otherExpense'] + $allData['rentAndMaintance']}}
        </td>
    </tr>
    <tr class="row3">
        <td></td>
        <td></td>
    </tr>
    <tr class="row8">
        <td>
            <strong>صافي الربح</strong>
        </td>
        <td>
            {{$allData['total']}}
        </td>
    </tr>
    </tbody>
</table>
