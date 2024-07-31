<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Shift</th>
            <th>Created By</th>
            <th>Shop Name</th>
            <th>Production Model Name</th>
            <th>Detail Manpower</th>
            <th>Detail Manpower Plan</th>
            <th>Working Hour</th>
            <th>OT Hour</th>
            <th>OT Hour Plan</th>
            <th>Detail Notes</th>
            <th>Production Hour</th>
            <th>Production Output8</th>
            <th>Production Output2</th>
            <th>Production Output1</th>
            <th>Production Plan Prod</th>
            <th>Production Total Prod</th>
            <th>Production Cabin</th>
            <th>Production PPM</th>
            <th>NG Reject</th>
            <th>NG Rework</th>
            <th>NG Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dailyReportData as $data)
            <tr>
                <td>{{ $data->date }}</td>
                <td>{{ $data->shift }}</td>
                <td>{{ $data->created_by }}</td>
                <td>{{ $data->shop_name }}</td>
                <td>{{ $data->production_model_name }}</td>
                <td>{{ $data->detail_manpower }}</td>
                <td>{{ $data->detail_manpower_plan }}</td>
                <td>{{ $data->working_hour }}</td>
                <td>{{ $data->ot_hour }}</td>
                <td>{{ $data->ot_hour_plan }}</td>
                <td>{{ $data->detail_notes }}</td>
                <td>{{ $data->production_hour }}</td>
                <td>{{ $data->output8 }}</td>
                <td>{{ $data->output2 }}</td>
                <td>{{ $data->output1 }}</td>
                <td>{{ $data->production_plan_prod }}</td>
                <td>{{ $data->production_total_prod }}</td>
                <td>{{ $data->cabin }}</td>
                <td>{{ $data->PPM }}</td>
                <td>{{ $data->ng_reject }}</td>
                <td>{{ $data->ng_rework }}</td>
                <td>{{ $data->ng_remarks }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

