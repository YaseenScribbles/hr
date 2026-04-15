<!--
    1. Insurance No
    2. Marital Satus
    3. Gender
    4. Emp Code
    5. Emp Name
    6. Father/Husband Name
    7. Present Address
    8. Permanent Address
    9. DOB
    10. Date of Appointment
    11. Local Office
    12. Dispensary
    13. Age
    14. Depatment
    15. Designation

    Family Details
    1. Name
    2. DOB
    3. Relationship
    4. Father/Husband Name
    4. Residing With Employee or Not
    5. Address

    ESI Corporation T.I.C Valid
    1. Is No
    2. Name
    3. Date of Appointment
    4. Local Office
    5. Dispensary
    6. Company Address
-->

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $emp->name . "(" . $emp->code . ")" }} - ESIC</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            line-height: 1;
            text-transform: uppercase;
        }

        @page {
            size: A4;
            margin: 10mm;
        }

        @font-face {
            font-family: 'TamilFont';
            src: url("/fonts/NotoSansTamil-Regular.ttf")format('truetype');
        }

        .container {
            width: 100%;
        }

        .header {
            text-align: center;
            padding-bottom: 10px;
        }

        .details td {
            padding: 10px;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 50px;
        }

        td,
        th {
            border: 1px solid black;
            padding: 4px;
            vertical-align: top;
        }

        .family table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 40px;
        }

        .border table {
            width: 100%;
            border: none;
            border-collapse: collapse;
        }

        .border {
            border: 1px solid black;
            border-top: none;
        }

        .page-break {
            page-break-after: always;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        td,
        th {
            border: 1px solid black;
            padding: 4px;
            vertical-align: top;
        }

        .no-border td {
            border: none;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .small {
            font-size: 11px;
        }

        .sign-box {
            height: 60px;
        }

        .space {
            height: 70px;
        }

        .particular {
            width: 100%;
            border-collapse: collapse;
            margin-top: 70px;
        }

        .border-all {
            border: 1px solid black;
            border-top: none;
            padding: 5px;
        }

        .section {
            border: 1px solid black;
            margin-top: 0;
            padding-bottom: 100px;
            border-top: none;
            margin-top: 60px;
        }

        .section table {
            border-left: none;
            border-right: none;
        }
        .section p{
            position: absolute;
            margin-top: 80px;
            padding-left: 530px;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="header">
            <h2>EMPLOYEES STATE INSURANCE CORPORATION</h2>
            <p>Declaration Form (Regulation 11&12) <br>
                (To be filled in only if the employee has not been insured earlier) <br>
                Serial No. in Return of declaration Form(Form No. 3)</p>
        </div>
        <div class="details" style="margin-top: 10px;">
            <table>
                <tr>
                    <td rowspan="2">1. Insurance No:</td>
                    <td rowspan="2" style="width: 250px;"></td>
                    <td rowspan="1">2. Marital Status &nbsp; : <strong>{{$emp->marital_status}}</strong></td>
                    <td rowspan="1">3. Sex &nbsp; : <strong>{{$emp->gender}}</strong></td>
                </tr>
                <tr>
                    <td colspan="2" rowspan="1">4. Employer's Code No. &nbsp; : <strong></strong></td>
                </tr>
                <tr>
                    <td>5. Name in Block (Capital)</td>
                    <td><strong>{{$emp->name}}</strong></td>
                    <td colspan="2">9. Year of Birth :&nbsp; <strong>{{Carbon\Carbon::parse($emp->d_o_b)->format('d-m-Y')}}</strong></td>
                </tr>
                <tr>
                    <td>6. Father or Husband Name :</td>
                    <td><strong>{{$emp->parent_name}}</strong></td>
                    <td colspan="2">10. Date of Appointment :&nbsp;<strong>{{Carbon\Carbon::parse($emp->d_o_j)->format('d-m-Y')}}</strong></td>
                </tr>
                <tr>
                    <td>7. Present Address :</td>
                    <td><strong>{{$emp->present_address}}</strong></td>
                    <td colspan="2">11. Local Office</td>
                </tr>
                <tr>
                    <td>8. Permanent Address :</td>
                    <td><strong>{{$emp->present_address}}</strong></td>
                    <td colspan="2">
                        12. Dispensary <br>
                        <br>
                        13. Age
                    </td>
                </tr>
                <tr>
                    <td colspan="4">State whether Bachelor,spinster,Married or Widower Married <br>
                        <br>
                        N.B.:please see overleaf for definition of family<br>
                    </td>
                </tr>
            </table>
        </div>
        <div class="family">
            <table>
                <td colspan="5"><strong>14. Particulars of family</strong></td>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Date of Birth / Age</th>
                    <th>Relationship with Insured Person</th>
                    <th>Residing with Employee or not</th>
                </tr>
                @if (count($emp_family) > 0)
                @foreach ($emp_family as $emp_data)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$emp_data->name}}</td>
                    <td>{{Carbon\Carbon::parse($emp_data->d_o_b)->format('d-m-Y')}} / {{$emp_data->age}}</td>
                    <td>{{$emp_data->relationship}}</td>
                    <td>{{ $emp_data->residing_with ? 'YES' : 'NO' }}</td>
                </tr>
                @endforeach
                @endif
                <tr>
                    <td colspan="5" style="height:80px;"></td>
                </tr>
            </table>
        </div>
        <p style="text-align: center;padding-bottom: 5px;">
            ESI Corporation T.I.C Valid for 13 weeks from the date of appointment
        </p>
        <div class="border">
            <table>
                <tr>
                    <td style="width: 70%;">Insurance No</td>
                    <td>Date of Appointment</td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td>Local Office</td>
                </tr>
                <tr>
                    <td style="border-bottom: none;">Name Address and code No. of Employer <b>{{Str::upper($company->name)}}</b></td>
                    <td>Dispensary</td>
                </tr>
                <tr>
                    <td style="border-top: none; border-right: none;">
                        <b>{{$company->address}} ,<br>
                            {{$company->city}} - {{$company->pincode}}</b>
                    </td>
                    <td style="border-left: none;"></td>
                </tr>
            </table>

            <p>Receipt of the identity Card</p>
            <p>Received the identity Card bearing ins.No. as above</p>
            <p style="display: inline-block;padding-right: 55px;padding-top: 40px; font-size :11px;">
                Signature or Thumb impression of insured Person
            </p>
            <p style="display: inline-block;font-size :11px;">
                Signature or Thumb impression of insured Person
            </p>
        </div>
    </div>
    <div class="page-break"></div>

    <div>
        <p><strong>Note : Family means all or the following relatives of an Insured person namely</strong></p>
        <p style="padding-left: 20px;">
            (i) a spouse <br>
            <br>
            (ii) a minor legitimate or adopted child dependent upon the insured person <br>
            <br>
            (iii) a child who is wholly dependent on the earnings of the insured person and who is: <br>
            <br>
            (iv) a child who is infirm by reason of my physicalor mental abnormality or injury and is wholly dependent on the earning of the insured person <br>
            <br>
            (v) Dependent parents:
        </p>
    </div>
    <div class="particular">
        <table>
            <tr>
                <td style="width:33%;">14.Particulars of Employment</td>
                <td style="width:33%;">Whether employed directly or though contractor</td>
                <td style="width:34%;"></td>
            </tr>

            <tr>
                <td></td>
                <td>
                    Department :<br><br>
                    <strong>{{$emp->department}}</strong>
                </td>
                <td>
                    Nature of Work :<br><br>
                    <strong>{{$emp->designation}}</strong>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    15. Nomination under sec. 50(2)(in case of female only ) and 71 (in case of both males&females ) of the E S I Act,
                    payment of any benefit that be due as specified in those sections in the event of the death of the insured person.
                </td>
            </tr>

            <tr>
                <td style="width:50%" colspan="2">a) name of Nominee :&nbsp;&nbsp; <strong>{{$emp_nominees[0]->name}}</strong></td>
                <td style="width:50%" colspan="2">b) Age &nbsp; <strong>{{$emp_nominees[0]->age}}</strong></td>
            </tr>

            <tr>
                <td colspan="2">c) Father's /Husband's Name :</td>
                <td rowspan="2">
                    d) Address <br><br>
                    <strong>{{$emp_nominees[0]->address}}</strong>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    Relationship of the Nominee with the insured Person &nbsp; : <strong>{{$emp_nominees[0]->relationship}}</strong>
                </td>
            </tr>
        </table>
    </div>
    <div class="border-all">
        <p class="small">
            I affirm that I have not been previously insured under the Act and identity card has been issued to me.<br>
            I hereby declare that the above particulars have been give by me and are correct to the best of my knowledge and belief<br>
            I also undertake to intimate to the Corporation any change in the membership of my family within 15 days of such change having accured.
        </p>

        <!-- SIGNATURE AREA -->
        <table class="no-border">
            <tr>
                <td style="padding-top: 30px;">Place &nbsp;&nbsp;.........................................
                <td class="right" style="padding-top: 50px;">Signature/Thumb impression of the Employee</td>
            </tr>

            <tr>
                <td>Date of signing the from ...................................</td>
                <td class="right" style="padding-top: 80px;">counter Signature of the employer<br>Designation</td>
            </tr>

            <br>
        </table>

        <p class="small">
            Name & address of the Employer &nbsp; {{ $company->name }} <br>
            {{ $company->address }} , {{ $company->city }} - {{ $company->pincode }}
        </p>
    </div>
    <div class="section">
        <table>
            <tr>
                <th>S.NO</th>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Relationship with Insured Person</th>
                <th>Residing with Employee or not</th>
            </tr>
            @if (count($emp_nominees)>0)
            @foreach ($emp_nominees as $emp_data)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$emp_data->name}}</td>
                <td>{{Carbon\Carbon::parse($emp_data->d_o_b)->format('d-m-Y')}}</td>
                <td>{{$emp_data->relationship}}</td>
                <td>{{ $emp_data->residing_with ? "YES" : "NO" }}</td>
            </tr>
            @endforeach
            @endif
        </table>
    </div>
</body>

</html>
