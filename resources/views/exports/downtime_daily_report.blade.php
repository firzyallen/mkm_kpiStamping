<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Shift</th>
            <th>Section</th>
            <th>Reporter</th>
            <th>Shop</th>
            <th>Machine</th>
            <th>Category</th>
            <th>Problem</th>
            <th>Cause</th>
            <th>Action</th>
            <th>Judgement</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Balance</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dailyReportData as $data)
            <tr>
                <td>{{ $data->date }}</td>
                <td>{{ $data->shift }}</td>
                <td>{{ $data->section_name }}</td>
                <td>{{ $data->reporter }}</td>
                <td>{{ $data->shop_name }}</td>
                <td>{{ $data->machine_name }}</td>
                <td>{{ $data->category }}</td>
                <td>{{ $data->problem }}</td>
                <td>{{ $data->cause }}</td>
                <td>{{ $data->action }}</td>
                <td>{{ $data->judgement }}</td>
                <td>{{ $data->start_time }}</td>
                <td>{{ $data->end_time }}</td>
                <td>{{ $data->balance }}</td>
                <td>{{ $data->downtime_percent }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

