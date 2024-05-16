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
    <h2> تقرير الفواتير الملغيه</h2>
@endif
<table class="table  table-bordered">
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
        <th class="border-top-0">   تاريخ الحذف</th>
    </tr>
    </thead>
    <tbody>
    @forelse($allData as $receipt )

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
            <td>{{$receipt->deleted_at}}</td>
        </tr>

    @empty
        <tr>
            لايوجد ايصالات حاليا
        </tr>
    @endforelse

    </tbody>
</table>
