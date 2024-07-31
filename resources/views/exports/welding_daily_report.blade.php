<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Shift</th>
            <th>PIC</th>
            <th>Shop Name</th>
            <th>Station Name</th>
            <th>Production Model Name</th>
            <th>Manpower</th>
            <th>Manpower Plan</th>
            <th>Working Hour</th>
            <th>OT Hour</th>
            <th>OT Hour Plan</th>
            <th>Notes</th>
            <th>Hour</th>
            <th>Output 8</th>
            <th>Output 2</th>
            <th>Output 1</th>
            <th>Plan Prod</th>
            <th>Total Prod</th>
            <th>Cabin</th>
            <th>PPM</th>
            <th>Reject</th>
            <th>Rework</th>
            <th>NG Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dailyReportData as $data)
            <tr>
                <td>{{ $data->date }}</td>
                <td>{{ $data->shift }}</td>
                <td>{{ $data->PIC }}</td>
                <td>{{ $data->shop_name }}</td>
                <td>{{ $data->station_name }}</td>
                <td>{{ $data->production_model_name }}</td>
                <td>{{ $data->manpower }}</td>
                <td>{{ $data->manpower_plan }}</td>
                <td>{{ $data->working_hour }}</td>
                <td>{{ $data->ot_hour }}</td>
                <td>{{ $data->ot_hour_plan }}</td>
                <td>{{ $data->notes }}</td>
                <td>{{ $data->hour }}</td>
                <td>{{ $data->output8 }}</td>
                <td>{{ $data->output2 }}</td>
                <td>{{ $data->output1 }}</td>
                <td>{{ $data->plan_prod }}</td>
                <td>{{ $data->total_prod }}</td>
                <td>{{ $data->cabin }}</td>
                <td>{{ $data->PPM }}</td>
                <td>{{ $data->reject }}</td>
                <td>{{ $data->rework }}</td>
                <td>{{ $data->ng_remarks }}</td>
            </tr>
        @endforeach
    </tbody>
</table>