<!--
    1. Emp Name
    2. Date
    3. Date Of Joining
    4. Salary Per Shift
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $emp->name . "(" . $emp->code . ")" }} - Form V</title>

    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            line-height: 1.6;
            text-transform: uppercase;
        }

        @page {
            size: A4;
            margin: 15mm;
        }

        @font-face {
            font-family: 'TamilFont';
            src: url("/fonts/NotoSansTamil-Regular.ttf")format('truetype');
        }

        .tamil-font {
            font-family: 'TamilFont';
            font-size: 12px;
            line-height: 1.7;
        }

        .header {
            margin-top: 0;
            text-align: center;
            font-weight: bold;
            border-bottom: 2px solid black;
            padding-bottom: 5px;
        }

        .sub {
            text-align: center;
            padding-top: 5px;
            padding-bottom: 20px;
        }

        .container {
            width: 100%;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .small {
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 6px;
            font-size: 12px;
            text-align: center;
            vertical-align: middle;
        }
/*
        .no-border {
            border: none !important;
        } */

        .left {
            text-align: left;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            <h1>{{ Str::upper($company->name) }}</h1>
            <p>{{ Str::upper($company->address) . ", " . $company->district . " - " . $company->pincode . ", " . $company->state }}</p>
        </div>
        <div class="sub">
            <h2>FORM V <br>
                THE INDUSTRIAL EXPLOYMENT (STANDING ORDERS) <br>
                CENTRAL RULES, 1946, STANDING ORDER I, SCHEDULE-B</h2>
        </div>

        <table>
            <tr>
                <td class="center bold" colspan="9">
                    SERVICE RECORD / सेवा रिकॉर्ड
                </td>
            </tr>

            <tr>
                <td colspan="9" class="left bold">
                    NAME / नाम &nbsp;&nbsp;&nbsp;&nbsp; : <strong>{{$emp->name}}</strong>
                </td>
            </tr>

            <tr>
                <th rowspan="2">DATE<br><span class="small">तारीख</span></th>
                <th rowspan="2">JOIN DATE<br><span class="small">प्रवेश की तारीख</span></th>
                <th rowspan="2">WAGES FIX<br><span class="small">मजदूरी तय</span></th>
                <th rowspan="2">INCREMENT<br><span class="small">वेतन वृद्धि</span></th>
                <th rowspan="2">BONUS<br><span class="small">बोनस</span></th>
                <th colspan="2">ADVANCE<br><span class="small">अग्रिम</span></th>
                <th rowspan="2">OTHERS<br><span class="small">अन्य</span></th>
            </tr>

            <tr>
                <th>CR.</th>
                <th>DR.</th>
            </tr>

            <tr>
                <td>
                <strong>
                        <!-- Add 10 days from join date -->
                        {{ \Carbon\Carbon::parse($emp->d_o_j)->addDays(10)->format('d-m-Y') }}
                </strong>
                </td>
                <td>
                    <strong>
                        {{ \Carbon\Carbon::parse($emp->d_o_j)->format('d-m-Y') }}</td>
                    </strong>
                <td>
                    <strong>
                        {{$emp->salary}}
                    </strong>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>0</td>
            </tr>

            @for($i=0; $i<4; $i++)
                <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                </tr>
            @endfor

        </table>

    </div>

</body>

</html>
