<!DOCTYPE html>
<html>
<head>
    <title>Analytics Report</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Analytics Report - {{ $community->name }}</h1>
    <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>Event Title</th>
                <th>Start Date</th>
                <th>Total Participants</th>
                <th>Attended</th>
                <th>Attendance Rate</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
            <tr>
                <td>{{ $row['Event Title'] }}</td>
                <td>{{ $row['Start Date'] }}</td>
                <td>{{ $row['Total Participants'] }}</td>
                <td>{{ $row['Attended'] }}</td>
                <td>{{ $row['Attendance Rate (%)'] }}%</td>
                <td>{{ $row['Status'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No data available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
