<!--
    1. Emp Post
    2. Emp Code and Name
    3. Emp Father / Husband Name and Occupation
    4. Emp DOB and Age
    5. Gender
    6. Present Address
    7. Permanent Address
    8. Phone No
    9. Marital Status

    FAMILY PARTICULARS
    1. Name
    2. DOB and Age
    3. Relationship
    4. Occupation

    Educational Qualification
    1. Course
    2. School/College
    3. Place
    4. Year

    Experience Details
    1. Name of the Organization
    2. Nature of Work
    3. Period

    E.S.I Number
    P.F Account
-->

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $emp->name . "(" . $emp->code . ")" }} - Bio Data</title>
    <style>
        /* body {
            font-family: 'TamilFont';
            font-size: 12px;
            line-height: 1.3;
            text-transform: uppercase;
        } */

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

        .header h1 {
            margin: 0;
        }

        .header p {
            margin: 3px 0;
            font-size: 11px;
        }

        .sub {
            text-align: center;
            text-decoration: underline;
            padding-top: 5px;
            padding-bottom: 15px;
        }

        .photo {
            position: absolute;
            right: 3mm;
            top: 30mm;
            width: 30mm;
            height: 35mm;
            border: 1px solid #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .no-border td {
            border: none;
            padding: 4px;
            vertical-align: top;
            text-transform: uppercase;
        }

        .no-bordered td {
            border: none;
            padding-top: 15px;
            vertical-align: top;
            text-transform: uppercase;
        }

        .border-table th,
        td {
            border: 1px solid black;
            padding: 5px;
            font-size: 11px;
            height: 30%;
            text-transform: uppercase;
        }

        .border-all {
            border: 1px solid black;
            padding-bottom: 100px;
        }

        .section {
            margin-top: 20px;
            font-weight: bold;
            /* border-top: 2px solid black;
            padding-top: 5px; */
        }

        .page-break {
            page-break-after: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        .page {
            height: 260mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="header">
            <h1>{{ Str::upper($company->name) }}</h1>
            <p>{{ Str::upper($company->address) . ", " . $company->district . " - " . $company->pincode . ", " . $company->state }}</p>
        </div>

        <div class="sub">
            BIO - DATA<br>
            <span class="tamil-font">சுயவிவரப் பதிவு</span>
        </div>

        <img src="{{ Storage::url($emp->img_path) }}" class="photo">

        <table class="no-border">

            <tr>
                <td width="45%">
                    Application for the Post of :<br>
                    <span class="tamil-font">
                        பணிக்கு விண்ணப்பிக்கும் பதவி
                    </span>
                </td>
                <td width="55%"><strong>{{$emp->designation}}</strong></td>
            </tr>

            <tr>
                <td>
                    Employee Code and Name :<br>
                    <span class="tamil-font">
                        ஊழியர் குறியீடு மற்றும் பெயர்
                    </span>
                </td>
                <td><strong>{{$emp->code}} / {{$emp->name}}</strong></td>
            </tr>

            <tr>
                <td>
                    Father's Name / Husband Name :<br>
                    <span class="tamil-font">
                        தந்தை பெயர் / கணவர் பெயர்
                    </span>
                </td>
                <td><strong>{{$emp->parent_name}} </strong></td>
            </tr>

            <tr>
                <td>
                    Date of Birth and Age :<br>
                    <span class="tamil-font">
                        பிறந்த தேதி மற்றும் வயது
                    </span>
                </td>
                <td><strong>{{Carbon\Carbon::parse($emp->d_o_b)->format('d-m-Y')}} / {{$emp->age}}</strong></td>
            </tr>

            <tr>
                <td>
                    Sex :<br>
                    <span class="tamil-font">
                        பாலினம்
                    </span>
                </td>
                <td><strong>{{$emp->gender}}</strong></td>
            </tr>

            <tr>
                <td>
                    Present Address (Capital Letters) :<br>
                    <span class="tamil-font">
                        தற்போதைய முகவரி
                    </span>
                </td>
                <td><strong>{{$emp->present_address}}</strong></td>
            </tr>

            <tr>
                <td>
                    Permanent Address (Capital Letters) :<br>
                    <span class="tamil-font">
                        நிலையான முகவரி
                    </span>
                </td>
                <td><strong>{{$emp->permanent_address}}</strong></td>
            </tr>

            <tr>
                <td>
                    Phone No (if any) :<br>
                    <span class="tamil-font">
                        தொலைபேசி எண்
                    </span>
                </td>
                <td><strong>{{$emp->mobile}}</strong></td>
            </tr>

            <tr>
                <td>
                    Marital Status :<br>
                    <span class="tamil-font">
                        திருமணமானவரா ?
                    </span>
                </td>
                <td><strong>{{$emp->marital_status}}</strong></td>
            </tr>

        </table>

        <div class="section">
            Family Particulars<br>
            <span class="tamil-font">
                குடும்ப விவரங்கள்
            </span>
        </div>
        <br>
        <div class="border-all">
            <table class="border-table">
                <tr>
                    <th>S.No<br><span class="tamil-font">வ.எண்</span></th>
                    <th>Name<br><span class="tamil-font">பெயர்</span></th>
                    <th>Date of Birth / Age<br><span class="tamil-font">பிறந்த தேதி</span></th>
                    <th>Relationship<br><span class="tamil-font">உறவு</span></th>
                    <th>Occupation<br><span class="tamil-font">தொழில்</span></th>
                </tr>
                @if (count($emp_family) > 0)
                @foreach ($emp_family as $emp_data )

                <tr>
                    <td><strong>{{$loop->iteration}}</strong></td>
                    <td><strong>{{$emp_data->name}}</strong></td>
                    <td><strong>{{Carbon\Carbon::parse($emp_data->d_o_b)->format('d-m-Y')}} / {{$emp_data->age}}</strong></td>
                    <td><strong>{{$emp_data->relationship}}</strong></td>
                    <td><strong>{{$emp_data->profession}}</strong></td>
                </tr>

                @endforeach
                @endif
            </table>
        </div>

        <div class="section">
            Educational Qualification<br>
            <span class="tamil-font">
                கல்வித் தகுதி
            </span>
        </div>
        <br>

        <div class="border-all">
            <table class="border-table">
                <tr>
                    <th>S.No<br><span class="tamil-font">வ.எண்</span></th>
                    <th>Course<br><span class="tamil-font">படிப்பு</span></th>
                    <th>School / College<br><span class="tamil-font">பள்ளி / கல்லூரி</span></th>
                    <th>Place<br><span class="tamil-font">இடம்</span></th>
                    <th>Year<br><span class="tamil-font">ஆண்டு</span></th>
                </tr>

                <!-- <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> -->
            </table>
        </div>

        <div class="page-break"></div>

        <div class="section">
            Experience Details<br>
            <span class="tamil-font">
                அனுபவ விவரங்கள்
            </span>
        </div>
        <br>
        <div class="border-all">
            <table class="border-table">
                <tr>
                    <th>S.No<br><span class="tamil-font">வ.எண்</span></th>
                    <th>Name of the Organization<br><span class="tamil-font">நிறுவனத்தின் பெயர்</span></th>
                    <th>Nature of Work<br><span class="tamil-font">வேலையின் தன்மை</span></th>
                    <th>Period<br><span class="tamil-font">வேலை செய்த காலம் </span></th>
                </tr>

                <!-- <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> -->

                <!-- <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> -->
            </table>
        </div>

        <div class="section">
            Expected Salary<br>
            <span class="tamil-font">
                எதிர்பார்க்கும் சம்பளம்
            </span>
        </div>
        <br>
        <table class="no-border">
            <tr>
                <td width="50%">1. E.S.I.Number -</td>
                <td width="50%">{{$emp->esi_number}}</td>
            </tr>

            <tr>
                <td>2. P.F.Account -</td>
                <td>{{$emp->pf_number}}</td>
            </tr>
        </table>


        <div class="section">
            Reference<br>
            <span class="tamil-font">
                தங்களை பற்றி தெரிந்த நபர்கள் இருவர்
            </span>
        </div>
        <br>
        <table class="no-border">
            <tr>
                <td width="10%">1.</td>
                <td width="90%"></td>
            </tr>

            <tr>
                <td>2.</td>
                <td></td>
            </tr>
        </table>

        <br>
        <div class="section">
            DECLARATION
        </div>

        <p>
            I hereby declare that the particulars furnished above are correct to the best of my knowledge.<br>
        </p>

        <p>
            If found wrong in future I will obey the decision taken by the management.<br>
        </p>

        <p style="margin-bottom: 40px;">
            I hereby authorize you to deduct E.S.I, P.F., Income Tax etc, as per the Acts.<br>
        </p>


        <table class="no-border" style="margin-top:30px;">
            <tr>
                <td width="50%">Place : __________________ <br>
                    <span class="tamil-font">இடம்</span>
                </td>
            </tr>

            <tr>
                <td>Date : __________________ <br>
                    <span class="tamil-font">தேதி</span>
                </td>
                <td width="50%" style="text-align:right;">Signature of Applicant <br>
                    <span class="tamil-font">விண்ணப்பதாரர் கையொப்பம்</span>
                </td>
                <!-- <td style="text-align:right;"><span class="tamil-font">விண்ணப்பதாரர் கையொப்பம்</span></td> -->
            </tr>
        </table>


        <div class="section" style="text-align:center; margin: top 15px; border-top: 2px solid black; padding-top: 15px;">
            FOR OFFICE USE ONLY
            <p style="margin-top: 0px;">Age Proof Certificate Submitted .. .. .. .. .. .. .. .. .. .. .. .. .. .. ..</p>
        </div>

        <table class="no-bordered">
            <tr>
                <td>Date Of Joining</td>
                <td>________________________________________</td>
                <td>Appointed by</td>
                <td>________________________________________</td>
            </tr>

            <tr>
                <td>Fixed Salary</td>
                <td>________________________________________</td>
                <td>Designation</td>
                <td>________________________________________</td>
            </tr>

            <tr>
                <td>Department</td>
                <td>________________________________________</td>
                <td>Authorized by</td>
                <td>________________________________________</td>
            </tr>
        </table>
    </div>
    <script>
    </script>
</body>

</html>
