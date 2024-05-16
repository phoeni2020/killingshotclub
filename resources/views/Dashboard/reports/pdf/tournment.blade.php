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
        <th class="border-top-0">  اسم المسابقه</th>
        <th class="border-top-0"> تاريخ المسابقه</th>
        <th class="border-top-0"> اشتراك </th>
        <th class="border-top-0"> التكلفه</th>
        <th class="border-top-0"> اسم اللاعب</th>
        <th class="border-top-0"> المركز</th>
        <th class="border-top-0"> تم الدفع</th>
    </tr>
    </thead>
    <tbody>
    @php
        $totalSubscribeValue = $cost = 0;
    @endphp
    @forelse($allData as $tournament )
        @php
            $tournamentInfo = \App\Models\Tournaments::find($tournament->tournament_id);
            $tournamentInfoPlayer = \App\Models\TournamentPlayersDetails::
            where('tournament_id',$tournament->tournament_id)
            ->where('player_id',$tournament->player_id)->get()->toArray();
            $player = \App\Models\Players::find($tournament->player_id);
            $totalSubscribeValue += $tournamentInfo->subscribe_value;
            $cost += $tournamentInfo->cost;
        @endphp
        <tr class="row1" data-id="{{ $tournament->id }}" >
            <td>{{$tournamentInfo->name}}</td>
            <td>{{$tournamentInfo->date}}</td>
            <td>{{$tournamentInfo->subscribe_value}}</td>
            <td>{{$tournamentInfo->cost}}</td>
            <td>{{$player?->name}}</td>
            <td>{{!empty($tournamentInfoPlayer)? $tournamentInfoPlayer->place:'لا توجد معلومات' }}</td>
            <td>{{$tournamentInfo->paid? 'تم الدفع':'لم يتم الدفع'}}</td>
        </tr>

    @empty

        <tr>
            <td colspan="6">
                لايوجد  مسابقات  حاليا
            </td>
        </tr>
    @endforelse
    <tr>
        <td colspan="2">المجموع</td>
        <td>{{$totalSubscribeValue}}</td>
        <td>{{$cost}}</td>
    </tr>
    </tbody>
</table>
