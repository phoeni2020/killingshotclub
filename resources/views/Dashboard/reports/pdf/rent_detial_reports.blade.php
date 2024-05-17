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
@forelse($allData as $key=>$reportData)
    <div class="table-responsive">
        <h6 class="text-center mt-5">@lang('validation.'.$key)</h6>
        <table class="table  table-bordered">
            <thead>
            <tr>
                <th class="border-top-0"> التاريخ</th>
                <th class="border-top-0"> اليوم</th>
                <th class="border-top-0"> الملعب</th>
                <th class="border-top-0"> أسم المدرب</th>
                <th class="border-top-0">السعر</th>
                <th class="border-top-0">الحاله</th>
                <th class="border-top-0">متكرر</th>
                <th class="border-top-0"> من</th>
                <th class="border-top-0">الي</th>
            </tr>
            </thead>
            <tbody>
            @foreach($reportData as $report)
                <tr class="row1">
                    <td>{{\Carbon\Carbon::parse($report->time_from)->format('d/m/Y')}}</td>
                    <td>@lang('validation.'.$key)</td>
                    <td>{{$report->stadiums->name}}</td>
                    <td>{{$report->name}}</td>
                    <td>{{  $report->price }}</td>
                    <td>{{  is_null($report->recipt_id) ? 'لم يتم الدفع': 'تم الدفع' }}</td>
                    <td>{{  is_null($report->event_repeated) ? 'غير متكرر': 'متكرر' }}</td>
                    <td>{{\Carbon\Carbon::parse($report->time_from)->format('h:i A')}}</td>
                    <td>{{\Carbon\Carbon::parse($report->time_to)->format('h:i A')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endforeach
