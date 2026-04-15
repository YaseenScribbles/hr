<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - {{ \Carbon\Carbon::parse($startDate)->format('M-y') }}</title>
</head>
<style>
    body {
        font-size: 10px;
        line-height: 1;
        text-transform: uppercase;
        margin-top: 0;
        font-family: 'Times New Roman', Times, serif;
    }

    @page {
        size: A4 landscape;
        margin: 5mm;
    }

    @font-face {
        font-family: 'Times New Roman', Times, serif;
        src: url("/fonts/NotoSansTamil-Regular.ttf")format('truetype');
    }

    .header {
        text-align: center;
        font-size: 11px;
    }

    .sub table {
        width: 100%;
        margin-top: 20px;
        font-size: 11px;
        border: none;
    }

    .sub {
        margin-bottom: 10px;
    }

    .sub td {
        border: none;
    }

    .section table {
        border: 1px solid black;
        width: 100%;
        border-collapse: collapse;
    }

    .section th,
    td {
        border: 1px solid black;
        padding: 4px;
        border-bottom: none;
    }

    .section th {
        font-size: 8px;
    }

    .end {
        padding-top: 30px;
        font-size: 10px;
    }
</style>

<body>
    <div class="header">
        <p style="display: flex; justify-content: center; align-items: center;">Register of Adult Workers <span style="display: flex; flex-direction: column; margin-inline: 5px;"><span>Men</span><span style="border-top: 1px solid #000;">Women</span></span>
            <span style="display: inline-block;">(Form No.12) Combined with Muster Roll (Form No.25) Rule (No 80 & 103) <br>
                Wages for monthly paid workers for the month of <b>{{$startDate->format('d/m/Y')}} - {{$endDate->format('d/m/Y')}}</b>
            </span>
        </p>
    </div>
    <div class="sub">
        <table>
            <tr>
                <td>Name of Factory : <b>{{$company->name}}</b></td>
                <td>Address : <b>{{$company->address . ', ' . $company->district . ' - ' . $company->pincode}}</b></td>
                <td>Place : <b>{{$company->district}}</b></td>
                <td>District : <b>{{$company->district}}</b></td>
                <td>Holiday : <b>Sunday</b></td>
            </tr>
        </table>
    </div>
    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>S.no</th>
                    <th>Emp Code</th>
                    <th>Emp Name</th>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    <th>5</th>
                    <th>6</th>
                    <th>7</th>
                    <th>8</th>
                    <th>9</th>
                    <th>10</th>
                    <th>11</th>
                    <th>12</th>
                    <th>13</th>
                    <th>14</th>
                    <th>15</th>
                    <th>16</th>
                    <th>17</th>
                    <th>18</th>
                    <th>19</th>
                    <th>20</th>
                    <th>21</th>
                    <th>22</th>
                    <th>23</th>
                    <th>24</th>
                    <th>25</th>
                    <th>26</th>
                    <th>27</th>
                    <th>28</th>
                    <th>29</th>
                    <th>30</th>
                    <th>31</th>
                    <th>Wages(M/D)</th>
                    <th>W.Days</th>
                    <th>P.Days</th>
                    <th>CL.Days</th>
                    <th>Total Wages</th>
                    <th>OT Hours</th>
                    <th>OT Wages</th>
                    <th>Gross Wages</th>
                    <th>ESI</th>
                    <th>PF</th>
                    <th>TotalDed</th>
                    <th>Net Wages</th>
                    <th>Signature</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    @php
                        $categoryEmployees = $attendanceData->where('category_id', $category->id);
                    @endphp
                    @if ($categoryEmployees->isNotEmpty())
                        <tr>
                            <td colspan="47">Category : <strong>{{ $category->name }}</strong></td>
                        </tr>
                        @foreach ($departments as $department)
                            @php
                                $departmentEmployees = $categoryEmployees->where('department_id', $department->id);
                            @endphp
                            @if ($departmentEmployees->isNotEmpty())
                                <tr>
                                    <td colspan="47" style="padding-left: 300px;">Department : <strong>{{ $department->name }}</strong></td>
                                </tr>
                                @foreach ($designations as $designation)
                                    @php
                                        $designationEmployees = $departmentEmployees->where('designation_id', $designation->id);
                                    @endphp
                                    @if ($designationEmployees->isNotEmpty())
                                        <tr>
                                            <td colspan="47" style="text-align: center;">Designation : <strong>{{ $designation->name }}</strong></td>
                                        </tr>
                                        @foreach ($designationEmployees as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->code }}</td>
                                                <td style="text-align: left; padding-left: 5px; max-width: 100px; overflow: hidden; text-wrap: nowrap; text-overflow: ellipsis;">{{ $data->name }}</td>
                                                @for ($i = 1; $i <= 31; $i++)
                                                    <td>{{ $data->daily_statuses[$i] ?? '' }}</td>
                                                @endfor
                                                <td>{{ $data->wages ?? 0 }}</td>
                                                <td>{{ $data->worked_shift ?? 0 }}</td>
                                                <td>{{ $data->present_days ?? 0 }}</td>
                                                <td>{{ $data->casual_leave ?? 0 }}</td>
                                                <td>{{ number_format($data->gross_salary ?? 0, 2) }}</td>
                                                <td>{{ $data->ot_hours ?? 0 }}</td>
                                                <td>{{ number_format($data->ot_wages ?? 0, 2) }}</td>
                                                <td>{{ number_format($data->gross_salary ?? 0, 2) }}</td>
                                                <td>{{ number_format($data->esi ?? 0, 2) }}</td>
                                                <td>{{ number_format($data->pf ?? 0, 2) }}
                                                <td>{{ number_format(($data->esi ?? 0) + ($data->pf ?? 0) + ($data->advance ?? 0), 2) }}</td>
                                                <td>{{ number_format($data->net_salary ?? 0, 2) }}</td>
                                                <td style="min-width: 80px; overflow: hidden;"></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="end">
        <p>Pre - No. of Present Days, Abs - No.of Absent, P.days - Paid Holidays, X - Present, A - Absent,
            A/ - First Half Absent, /A - Second Half Absent, WH - Holidays
        </p>
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
