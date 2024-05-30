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
@php
    extract($allData);
@endphp
<table class="table  table-bordered">
                                        <thead>
                                        <tr>
                                            <th class="border-top-0">السيريال</th>
                                            <th class="border-top-0">اسم الموظف</th>
                                            <th class="border-top-0">اليوم</th>
                                            <th class="border-top-0">من</th>
                                            <th class="border-top-0">الي</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($allData as $employee )
                                            @php
                                                $attendnce = [];
                                                $attendnceRecord = \App\Models\EmpolyeeAttendance::where('user_id',$employee['id'])->whereNotIn('id',$attendnce)->first();
                                            @endphp
                                            <tr class="row1" data-id="{{ $employee['id'] }}" >
                                                <td>{{$employee['id']}}</td>
                                                <td>{{$employee['name']}}</td>
                                                <td>
                                                    @php
                                                    if($attendnceRecord){
                                                        echo date('Y-m-d',strtotime($attendnceRecord->check_in));
                                                    }else{
                                                        echo 'لايوجد سجلات حضور حاليا';
                                                    }
                                                    @endphp
                                                </td>
                                                <td>
                                                    @php
                                                        if($attendnceRecord){
                                                            echo date('D, H:i:s',strtotime($attendnceRecord->check_in));
                                                        }else{
                                                            echo 'لايوجد سجلات حضور حاليا';
                                                        }
                                                    @endphp
                                                </td>
                                                <td>
                                                    @php
                                                        if($attendnceRecord){
                                                            echo date('D, H:i:s',strtotime($attendnceRecord->check_out));
                                                        }else{
                                                            echo 'لايوجد سجلات حضور حاليا';
                                                        }
                                                    @endphp
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                لايوجد سجلات حضور حاليا
                                            </tr>
                                        @endforelse

                                        </tbody>
                                    </table>
