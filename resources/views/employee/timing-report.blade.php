<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emp->name . "(" . $emp->code . ")" }} - Timing Report</title>
</head>
<style>
    body {
        font-size: 10px;
        line-height: 1;
        text-transform: uppercase;
        margin-top: 0;
    }

    @page {
        size: A4;
        margin: 10mm;
    }

    @font-face {
        font-family: 'Times New Roman', Times, serif;
        src: url("/fonts/NotoSansTamil-Regular.ttf")format('truetype');
    }

    .header {
        text-align: center;
    }

    .header h1 {
        margin-bottom: 2px;
    }

    .sub h3,
    p {
        display: inline-block;
    }

    .section table {
        width: 100%;
        font-size: 11px;
        border: 1px solid black;
        padding: 4px;
        border-collapse: collapse;
    }

    .shift table {
        border: 1px solid black;
        width: 100%;
        border-collapse: collapse;
    }

    .shift th,
    td {
        padding: 4px;
        border: 1px solid black;
        font-size: 12px;
    }

        .summary table {
        border: 1px solid black;
        margin-top: 10px;
        border-collapse: collapse;
        width: 100%;
    }

    .summary th,
    td {
        padding: 4px;
        border: 1px solid black;
    }
</style>

<body>
    <div class="header">
            <h1>{{ Str::upper($company->name) }}</h1>
            <p>{{ Str::upper($company->address) . ", " . $company->district . " - " . $company->pincode . ", " . $company->state }}</p>
    </div>

    <div class="sub">
        <h3>Monthly Consolidate Report&nbsp;</h3>
        <p style="font-size: 12px;">&nbsp;From <b>{{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}</b> to <b>{{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</b></p>
    </div>
    <div class="section">
        <table>
            <tr>
                <td>Department : &nbsp;&nbsp; <b>{{$emp->department}}</b></td>
                <td>DOJ : &nbsp;&nbsp; <b>{{\Carbon\Carbon::parse($emp->d_o_j)->format('d-m-Y')}}</b></td>
                <td>Designation : &nbsp;&nbsp; <b>{{$emp->designation}}</b></td>
            </tr>
        </table>
        <table>
            <tr>
                <td>Employee Code : &nbsp;<b>{{$emp->code}}</b></td>
                <td>Employee Name : &nbsp;<b>{{$emp->name}}</b></td>
                <td>Gender : &nbsp; <b>{{$emp->gender}}</b></td>
            </tr>
        </table>
    </div>
    <div class="shift">
        <table>
            <tr>
                <th>Report On</th>
                <th>Shift</th>
                <th>Log In</th>
                <th>Lunch Out</th>
                <th>Lunch In</th>
                <th>Log Out</th>
                <th>Actual Hrs</th>
                <th>OT In</th>
                <th>OT Out</th>
                <th>OT Hrs</th>
                <th>Total Hrs</th>
                <th>Remarks</th>
            </tr>
            @foreach ($timings as $timing)
                <tr>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($timing->date)->format('d-m-Y') }}</td>
                    <td style="text-align: center;">{{ $timing->shift_code }}</td>
                    <!-- Format just hour and minutes -->
                    <td style="text-align: center;">{{ $timing->log_in ? \Carbon\Carbon::parse($timing->log_in)->format('H:i') : '' }}</td>
                    <td style="text-align: center;">{{ $timing->lunch_out ? \Carbon\Carbon::parse($timing->lunch_out)->format('H:i') : '' }}</td>
                    <td style="text-align: center;">{{ $timing->lunch_in ? \Carbon\Carbon::parse($timing->lunch_in)->format('H:i') : '' }}</td>
                    <td style="text-align: center;">{{ $timing->log_out ? \Carbon\Carbon::parse($timing->log_out)->format('H:i') : '' }}</td>
                    <td style="text-align: center;">{{ $timing->actual_hours ? \Carbon\Carbon::parse($timing->actual_hours)->format('H:i') : '' }}</td>
                    <td style="text-align: center;">{{ $timing->ot_in ? \Carbon\Carbon::parse($timing->ot_in)->format('H:i') : '' }}</td>
                    <td style="text-align: center;">{{ $timing->ot_out ? \Carbon\Carbon::parse($timing->ot_out)->format('H:i') : '' }}</td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;">{{ $timing->total_hours ? \Carbon\Carbon::parse($timing->total_hours)->format('H:i') : '' }}</td>
                    <td style="text-align: center;">{{ $timing->status }}</td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="summary">
        <table>
            <tr>
                <th rowspan="5" style="text-align: center;">CUMULATIVE REPORT</th>
            </tr>
            <tr>
                <th>Days in a Month</th>
                <th>Weekly Off</th>
                <th>Present Days</th>
                <th>Absent Days</th>
                <th>Half Days</th>
                <th>/L</th>
                <th></th>
            </tr>
            <tr>
                <td style="text-align: center;">{{$daysInMonth}}</td>
                <td style="text-align: center;">{{$weeklyOff}}</td>
                <td style="text-align: center;">{{$presentDays}}</td>
                <td style="text-align: center;">{{$absentDays}}</td>
                <td style="text-align: center;">{{ $halfDays }}</td>
                <td style="text-align: center;"></td>
                <td></td>
            </tr>
            <tr>
                <th>OT Days</th>
                <th>OT Hours</th>
                <th>Late Days</th>
                <th>Late Hours</th>
                <th>HWP</th>
                <th>HOP</th>
                <th>Left Days</th>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
    <p>X - Present, / - Half Day Present, A - Absent, HWP - Holiday with Payment, HOP - Holiday without Payment, WH - Weekly Off,
        (-) - Left, CL - Casual Leave, EL - Earn Leave, SL - Sick Leave
    </p>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
