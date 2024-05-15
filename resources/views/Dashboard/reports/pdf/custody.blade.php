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
    <h2> تقرير المسابقات </h2>
@endif
<table class="table  table-bordered">
    <thead>
    <tr>
        <th class="border-top-0">  اسم مستلم العهده  </th>
        <th class="border-top-0">  العهده </th>
        <th class="border-top-0"> اجمالي العهده</th>
        <th class="border-top-0">  اجمالي المصروفات من العهده</th>
        <th class="border-top-0">  المتبقي من العهده</th>
        <th class="border-top-0">  الي  </th>
        <th class="border-top-0">  تمت تسوية العهده ؟  </th>
    </tr>
    </thead>
    <tbody>
    @forelse($settlements as $settlement )

        <tr class="row1" data-id="{{ $settlement->id }}" >
            <td>{{$settlement->user->name }}</td>

            <td>
                {{$settlement->receipt_pay->receiptType->name }}
            </td>

            <td>
                {{$settlement->price }}
            </td>

            <td>
                @php
                    $expenses = \App\Models\CustodyExpense::where('custody_id',$settlement->id)->sum('price');
                    $branch = \App\Models\Branchs::find($settlement->receipt_pay->branch_id );
                @endphp
                {{$expenses}}
            </td>
            <td>
                {{$settlement->price -  $expenses }}
            </td>

            <td>

                {{$branch->name}}
            </td>
            <td>
                {{$settlement->requested ? 'Yes' : 'No'}}
            </td>
        </tr>
    @empty
        <tr>
            لايوجد اي عهد
        </tr>
    @endforelse
    </tbody>
</table>

