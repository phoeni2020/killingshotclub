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
    <h2> تقرير الحضور  </h2>
@endif

<table class="table  table-bordered">
    <thead>
    <tr>
        <th class="border-top-0">السيريال</th>
        <th class="border-top-0">اسم المدرب</th>
        <th class="border-top-0">اليوم</th>
        <th class="border-top-0">من</th>
        <th class="border-top-0">الي</th>
    </tr>
    </thead>
    <tbody>
    @forelse($allData as $player )
        @php
            $attendnce = [];

            $playerData = \App\Models\User::query()->where('id',$player['trainer_id'])->first();
            //dd($player->trainer_id);
        @endphp
        <tr class="row1" data-id="{{ $playerData->id }}" >
            <td>{{$playerData->id}}</td>
            <td>{{$playerData->name}}</td>
            <td>
                {{date('Y-m-d',strtotime($player['check_in']))}}

            </td>
            <td>
                {{date('D, H:i:s',strtotime($player['check_in']))}}
            </td>
            <td>
                {{date('D, H:i:s',strtotime($player['check_out']))}}
            </td>
        </tr>

    @empty
        <tr>
            لايوجد سجلات حضور حاليا
        </tr>
    @endforelse

    </tbody>
</table>
