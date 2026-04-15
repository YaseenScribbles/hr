<!--
    1. Emp Code
    2. Emp Name
    3. Department
    4. Designation
    5. PF Number
    6. ESI Number
    7. Salary for Period
    8. Date of Joining
    9. DOB
    10. Prepared Date
    11. Date of Payment
    12. Salary Per Day
    13. Deduction Amount (ESI,PF,Advance)
    14. Working Days
    15. NFH
    16. OT Hours
    17. OT Wages
    18. Total Sal.Days
    19. Total Earnings
    20. Net Wages
    21. Salary in Rupees
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emp->name . "(" . $emp->code . ")" }} - Form-25B</title>
</head>
<style>
    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12px;
        line-height: 1;
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

    .border-all {
        border: 2px solid black;
    }

    .header {
        text-align: center;
        margin-bottom: 30px;
    }

    .sub {
        font-size: 9px;
        padding-left: 10px;
    }

    .details table {
        width: 100%;
        border-collapse: collapse;
    }

    .details td,
    tr {
        border: 1px solid black;
        padding: 10px;
    }

    .wages table {
        width: 100%;
        border-collapse: collapse;
    }

    .wages td,
    tr {
        border: 1px solid black;
        padding: 10px;
    }

    .wagesDetails p {
        display: inline-block;
    }

    .wagesDetails {
        border-bottom: 2px solid black;
        padding-left: 5px;
    }

    .sign p {
        margin-top: 130px;
        display: inline-block;
        padding-left: 5px;
    }
</style>

<body>
    <div class="border-all">
        <div class="header">
            <h1>{{ Str::upper($company->name) }}</h1>
            <p>{{ Str::upper($company->address) . ", " . $company->district . " - " . $company->pincode . ", " . $company->state }}</p>
            <h2>FORM 25B - WAGE (or) SALARY SLIP / <span style="font-family: 'TamilFont';"> ஊதிய ரசீது</span></h2>
        </div>
        <div class="sub">
            <p>Factory Reg No :</p>
            <p>Rule No : 103 (Under rule 27 (2) of the minimum wages Tamilnadu rules 1953) / <span style="font-family: 'TamilFont';">குறைந்த பட்ச கூலி விதி 27(2) தமிழ்நாடு 1953ன் படி</span></p>
        </div>
        <div class="details">
            <table>
                <tr>
                    <td>E.ID / <span style="font-family: 'TamilFont';">தொ.எண்</span> : &nbsp;<b>{{$emp->code}}</b></td>
                    <td>E.Name / <span style="font-family: 'TamilFont';">தொ.பெயர்</span> : &nbsp;<b>{{$emp->name}}</b></td>
                    <td>Department / <span style="font-family: 'TamilFont';">பிரிவு</span> : &nbsp; <b>{{$emp->department}}</b></td>
                </tr>
                <tr>
                    <td>Designation / <span style="font-family: 'TamilFont';">பதவி</span> : &nbsp; <b>{{$emp->designation}}</b></td>
                    <td>PF Number : &nbsp; <b>{{$emp->pf_number}}</b> </td>
                    <td>ESI Number : &nbsp; <b>{{$emp->esi_number}}</b> </td>
                </tr>
                <tr>
                    <td>
                        Salary For Period / <span style="font-family: 'TamilFont';">ஊதியம்</span> <br>
                        <br>
                        <b>{{Carbon\Carbon::parse($startDate)->format('d-m-Y')}}</b>&nbsp; - &nbsp;<b>{{Carbon\Carbon::parse($endDate)->format('d-m-Y')}}</b>
                    </td>
                    <td>DOJ : &nbsp; <b>{{Carbon\Carbon::parse($emp->d_o_j)->format('d-m-Y')}}</b> <br>
                        <br>
                        DOB : &nbsp; <b>{{Carbon\Carbon::parse($emp->d_o_b)->format('d-m-Y')}}</b>
                    </td>
                    <td>
                        Prepared Date : &nbsp; <b>{{ Carbon\Carbon::parse($salary[0]->created_at)->format('d-m-Y') }}</b><br>
                        <br>
                        Date of Payment : &nbsp; <b>{{ Carbon\Carbon::parse($salary[0]->created_at)->format('d-m-Y') }}</b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="wages">
            <table>
                <tr style="text-align: center;">
                    <td colspan="3">Wage or Salary / <span style="font-family: 'TamilFont';">சம்பளம்</span></td>
                    <td colspan="2">Deduction / <span style="font-family: 'TamilFont';">பிடித்தம்</span></td>
                </tr>
                <tr>
                    <td>
                        Wages / salary Details <br>
                        <span style="font-family: 'TamilFont';">ஊதியங்கள்</span>
                    </td>
                    <td>
                        Mothly / Daily Wages <br>
                        <span style="font-family: 'TamilFont';">மாத / தினசரி ஊதியம்</span>
                    </td>
                    <td>
                        Earn Salary (Rs.) <br>
                        <span style="font-family: 'TamilFont';">ஈட்டிய ஊதியம்</span>
                    </td>
                    <td>
                        Deduction Type
                    </td>
                    <td>
                        Ded. Amt (Rs.)
                    </td>
                </tr>
                <tr>
                    <td>BASIC + DA</td>
                    <td style="text-align: right;">{{$salary[0]->wages}}</td>
                    <td style="text-align: right;">{{$salary[0]->gross_salary}}</td>
                    <td>ESI</td>
                    <td style="text-align: right;">{{$salary[0]->esi}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>PF</td>
                    <td style="text-align: right;">{{$salary[0]->pf}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>ADVANCE</td>
                    <td style="text-align: right;">{{$salary[0]->advance}}</td>
                </tr>
                <tr style="height: 60px;text-align:right;font-weight: bold;">
                    <td>Total Salary</td>
                    <td>{{number_format($salary[0]->wages, 2)}}</td>
                    <td>{{number_format($salary[0]->gross_salary, 2)}}</td>
                    <td>Total Deduction</td>
                    <td>{{number_format($salary[0]->esi + $salary[0]->pf + $salary[0]->advance, 2)}}</td>
                </tr>
            </table>
        </div>
        <div class="wagesDetails">
            <p style="padding-right: 90px;padding-top: 5px;">
                Worked.days / <span style="font-family: 'TamilFont';">வே.செ.நாட்கள்</span> : <b>{{$salary[0]->worked_shift}}</b> <br>
                <br>
                NFH / <span style="font-family: 'TamilFont';">தே.ப.வி.நாட்கள்</span> : <br>
                <br>
                OT HOURS / <span style="font-family: 'TamilFont';">மி.நே</span> : <b>0.00</b> <br>
                <br>
                <br>
            </p>
            <p>
                OT Wages / <span style="font-family: 'TamilFont';">மி.நே.ஊ.தொகை</span> : <b>0.00</b><br>
                <br>
                Total Sal.Days / <span style="font-family: 'TamilFont';">மொ.ஊ.நாட்கள்</span> : <b>{{$salary[0]->worked_shift}}</b><br>
                <br>
                Total Earnings / <span style="font-family: 'TamilFont';">மொத்தம் ஈட்டிய தொகை</span> : <b>{{$salary[0]->gross_salary}}</b><br>
                <br>
                Net Wages / <span style="font-family: 'TamilFont';">கொடுக்கப்பட்ட நிகர தொகை</span> : <b>{{$salary[0]->net_salary}}</b>
            </p>
            <br>
            <!-- convert net salary to words -->
            <p>Wages / Salary in Rupees : <b>{{ $amountInWords }}</b></p>
        </div>
        <div class="sign">
            <p style="padding-right: 170px;">
                Employee's Signatory / <span style="font-family: 'TamilFont';">தொழிலாளர் கையொப்பம்</span>
            </p>
            <p>
                Manager / <span style="font-family: 'TamilFont';">மேலாளர்</span>
            </p>
        </div>
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
