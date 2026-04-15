<!--
    1. Emp Name
    2. Notice Date
    3. Gender
    4. Religion
    5. Marital Status
    6. Department
    7. Emp Code
    8. Date Of Appointment
    9. Permanent Address

    Family Details
    1. Name
    2. Address
    3. Relationship
    4. Age
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emp->name . "(" . $emp->code . ")" }} - Form-F</title>
</head>
<style>
    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 12px;
        line-height: 1.3;
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

    .header {
        margin-top: 0;
        text-align: center;
        font-weight: bold;
        border-bottom: 2px solid black;
        /* padding-bottom: 5px; */
    }

    .sub-header {
        text-align: center;
        border-bottom: 2px solid black;
    }

    .sub {
        padding-top: 10px;
    }

    .sub p {
        padding-left: 20px;
    }

    .details table {
        width: 100%;
        border-collapse: collapse;
        /* margin-top: 30px; */
    }

    .details table,
    th,
    td {
        border: 1px solid black;
    }

    .details th,
    td {
        padding: 6px;
        font-size: 12px;
        text-align: top;
        vertical-align: middle;
    }

    .border-all {
        border: 1px solid black;
        padding-bottom: 100px;
        margin-top: 60px;
    }

    .page-break {
        page-break-after: always;
    }

    .header-2 {
        text-align: center;
    }

    .place {
        display: inline-block;
        padding-right: 400px;
    }

    .signature {
        display: inline-block;
    }

    .declaration p {
        display: inline-block;
    }
</style>

<body>
    <div class="header">
            <h1>{{ Str::upper($company->name) }}</h1>
            <p>{{ Str::upper($company->address) . ", " . $company->district . " - " . $company->pincode . ", " . $company->state }}</p>
    </div>
    <div class="sub-header">
        <h2>The TamilNadu Payment Of Gratuity Rules, 1973</h2>
        <h3><strong>FORM - "F" <br>
                see Sub - rule(1) of rule (6) <br>
                Nomination</strong></h3>
    </div>
    <div class="sub">
        <strong>TO</strong>
        <p>{{ Str::upper($company->name) }}, <br>
            {{ Str::upper($company->address) }}, <br>
            {{ Str::upper($company->district) }}, <br>
            {{ Str::upper($company->state) }}, INDIA. <br>
            Pin Code - {{ $company->pincode }} </p>
    </div>
    <br>
    <span>( give here name or description of the establishment with full address )</span>
    <br>
    <div class="section">
        <p>
            1) Shri / Shrimathi / Kumari &nbsp;<u><strong>{{$emp->name}}</strong></u> &nbsp;(Name in full here) whose particulars are given in the statement below, hereby nominate
            the person(s) mentioned below to receive the gratuity payable after my death and also the gratuity standing to my credit in the event
            of my death before that amount has become payable, or having become payable has not been paid and direct that the said amount of gratuity
            shall be paid in portion indicated against the name(s) of the nominee(s).
        </p>
        <p>
            2) I hereby certify that the person(s) mentioned is a / are member(s) of my family within the meaning of clause(h) of section 2 of the
            payment of gratuity Act, 1972.
        </p>
        <p>
            3) I hereby declare that I have no family within the meaning of clause(h) of section 2 of the said Act. <br>
            <span style="padding-left: 30px;"> (a) My Father / Mother / Parents is not dependent me.</span> <br>
            <span style="padding-left: 30px;"> (b) My husband's Father / Mother /Parents is not dependent on my husband.</span>
        </p>
        <p>
            4) I have excluded my husband from my family by a notice dated the&nbsp; <strong>{{ \Carbon\Carbon::parse($emp->d_o_j)->format('d-m-Y') }}</strong>&nbsp; to the controlling
            authority in terms of the provision to clause(h) of section 2 of the said Act.
        </p>
        <p>
            5) Nomination made here in validates my previous nomination.
        </p>
    </div>
    <div class="border-all">
        <div class="details">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2">S.No</th>
                        <th rowspan="2">NAME IN FULL WITH ADDRESS OF THE NOMINEE(S)</th>
                        <th rowspan="2">RELATIONSHIP WITH THE EMPLOYEE</th>
                        <th rowspan="2">AGE OF THE NOMINEE</th>
                        <th rowspan="2">PROPORTION BY WHICH THE GRATUITY WILL BE SHARED</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($emp_nominees))
                    @foreach ($emp_nominees as $nominee)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$nominee->name}} <br>
                            {{$nominee->address}}
                        </td>
                        <td>{{$nominee->relationship}}</td>
                        <td>{{$nominee->age}}</td>
                        <td>100%</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="page-break"></div>

    <div class="header-2">
        <h2><u>STATEMENT</u></h2>
    </div>
    <div class="section-2">
        <p>
            1) Name of the employee in full : <u><strong>{{$emp->name}}</strong></u>
        </p>
        <p>
            2) Sex : <u><strong>{{$emp->gender}}</strong></u>
        </p>
        <p>
            3) Religion : <u><strong>{{$emp->religion}}</strong></u>
        </p>
        <p>
            4) Whether unmarried / Married / Widow / Widower : <u><strong>{{$emp->marital_status}}</strong></u>
        </p>
        <p>
            5) Department / Branch / Section where employed : <u><strong>{{$emp->department}}</strong></u>
        </p>
        <p>
            6) Emp No : <u><strong>{{$emp->code}}</strong></u>
        </p>
        <p>
            7) Date of appointment : <u><strong>{{Carbon\Carbon::parse($emp->d_o_j)->format('d-m-Y')}}</strong></u>
        </p>
        <p>
            8) Permanent Address : <u><strong>{{$emp->permanent_address}}</strong></u>
        </p>
    </div>
    <br>
    <div class="place">
        <p>Place : </p>
        <p>Date : </p>
    </div>
    <div class="signature">
        Signature / Thumb impression <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;of the employee
    </div>
    <br>
    <div class="header-2">
        <h2><u>DECLARATION BY WITNESS</u></h2>
    </div>
    <div class="declaration">
        <p style="padding-right: 140px;">Nominated signed / thumb impression before me. <br>
            Name and address of witness Signature of witness <br>
            <br>
            1. <br>
            <br>
            2.
        </p>
        <p>Signature of witness</p>
    </div>
    <p style="border-bottom: 2px solid black;padding-bottom: 5px;">Place : <br>
        <br>
        Date :
    </p>
    <div class="header-2">
        <h2><u>CERTIFICATE BY THE EMPLOYER</u></h2>
    </div>
    certified that the particulars of the above nomination have been verified and recorded in this establishment.
    <br>
    <br>
    Employer's reference if any.
    <p style="padding-left: 350px;padding-top: 70px;">Signature of the employer <br>
        Officer Authorised designation
    </p>
    <p style="display: inline-block;padding-top: 80px;">Date :</p>
    <p style="display: inline-block;padding-left: 310px;">Name and address of the Establishment <br>
        or rubber stamp there of
    </p>

    <div class="page-break"></div>

    <div class="header-2">
        <h2><u>ACKNOWLEDGE BY THE EMPLOYEE</u></h2>
    </div>
    <p>Received the duplicate copy of nomination in Form "F" filled by me and duly certified by the employer.</p>
    <p style="display: inline-block;padding-top: 100px;">Date :</p>
    <p style="display: inline-block;padding-left: 400px;padding: bottom 50px;">Signature of the employee</p>
    <p>* Strike out the words / paragraphs not applicable</p>
</body>

</html>
