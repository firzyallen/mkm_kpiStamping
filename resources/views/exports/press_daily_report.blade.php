<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Shift</th>
            <th>PIC</th>
            <th>Shop Name</th>
            <th>Production Model Name</th>
            <th>Manpower</th>
            <th>Manpower Plan</th>
            <th>Working Hour</th>
            <th>Notes</th>
            <th>Production Process</th>
            <th>Status</th>
            <th>Type</th>
            <th>Incoming Material</th>
            <th>Machine</th>
            <th>Setting</th>
            <th>Hour From</th>
            <th>Hour To</th>
            <th>Plan Production</th>
            <th>OK</th>
            <th>NG OK</th>
            <th>Rework</th>
            <th>Damage Part</th>
            <th>Damage RM</th>
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
                <td>{{ $data->production_model_name }}</td>
                <td>{{ $data->manpower }}</td>
                <td>{{ $data->manpower_plan }}</td>
                <td>{{ $data->working_hour }}</td>
                <td>{{ $data->notes }}</td>
                <td>{{ $data->prod_process }}</td>
                <td>{{ $data->status }}</td>
                <td>{{ $data->type }}</td>
                <td>{{ $data->inc_material }}</td>
                <td>{{ $data->machine }}</td>
                <td>{{ $data->setting }}</td>
                <td>{{ $data->hour_from }}</td>
                <td>{{ $data->hour_to }}</td>
                <td>{{ $data->plan_prod }}</td>
                <td>{{ $data->OK }}</td>
                <td>{{ $data->ng_OK }}</td>
                <td>{{ $data->rework }}</td>
                <td>{{ $data->dmg_part }}</td>
                <td>{{ $data->dmg_rm }}</td>
                <td>{{ $data->ng_remarks }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
