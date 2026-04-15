<!--
    1. Emp Name
    2. Father/Husband Name
    3. DOB
    4. Gender
    5. Marital Status
    6. Account No
    7. Address

    Nominee's Details
    1. Name
    2. Address
    3. Relationship
    4. DOB
-->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emp->name . "(" . $emp->code . ")" }} - Form-2</title>
</head>
<style>
    body {
        font-family: 'TamilFont';
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

    .font {
        font-family: 'Times New Roman', Times, serif;
    }

    .header {
        text-align: center;
        font-size: 11px;
    }

    .sub {
        text-align: center;
        font-size: 11px;
    }

    .bio td {
        border: none;
        padding: 4px;
        vertical-align: top;
    }

    .bio table {
        width: 100%;
        padding-top: 20px;
        border-bottom: 2px solid black;
    }

    .sub-header {
        text-align: center;
    }

    .family-details table {
        border-collapse: collapse;
        width: 100%;
    }

    .family-details table,
    th,
    td {
        border: 1px solid black;
    }

    .family-details th,
    td {
        padding: 6px;
        font-size: 10px;
        text-align: top;
        vertical-align: middle;
        height: 30%;
    }

    .nominee table {
        border-collapse: collapse;
        width: 100%;
    }

    .nominee table,
    th,
    td {
        border: 1px solid black;
    }

    .nominee th,
    td {
        padding: 6px;
        font-size: 10px;
        text-align: top;
        vertical-align: middle;
        height: 30%;
    }

    .border-all {
        border: 1px solid black;
        padding-bottom: 50px;
        margin-top: 20px;
    }

    .sign {
        font-size: 10px;
    }

    .page-break {
        page-break-after: always;
    }
</style>

<body>
    <div class="header">
        <p>
            <strong class="font">FORM - 2 (REVISED)</strong><br>
            படிவம் - 2
        </p>
        <p>
            <strong class="font">NOMINATION AND DECLARATION FORM</strong><br>
            (நியமனம் மற்றும் உறுதிமொழி படிவம்)
        </p>
        <p>
            <strong class="font">FOR UNEXEMPTED / EXEMPTED ESTABLISHMENTS</strong><br>
            (விதிவிலக்கு பெறாத / பெற்ற நிறுவனங்களுக்கு)
        </p>
    </div>
    <div class="sub">
        <p>
            <strong class="font">Declaration and Nomination Form Under the Employee Provident Funds & Employees Pension Scheme</strong> <br>
            (நியமனம் மற்றும் உறுதிமொழி படிவம் தொ.வ.வை.நி. மற்றும் தொ.ஓ.திட்டத்திற்குட்பட்டது)
        </p>
        <p>
            <strong class="font">(Paragraph 33 & 61(1) of the Employee Provident Fund Scheme, 1952 & Paragraph 18 of the Employee Pension Scheme, 1995)</strong><br>
            (தொழிலாளர் வருங்கால வைப்பு நிதித்திட்டம் 1952 ஷ பாரா(1)ன் படியும் தொ.ஓ.திட்ட 1995 பாரா 18ன் படிவம்)
        </p>
    </div>
    <div class="bio">
        <table>

            <tr>
                <td><span class="font">Name (in BLock Letters)</span> :<br>
                    (பெயர் தனித்தனி எழுத்துக்களில்)
                </td>
                <td><strong class="font">{{$emp->name}}</strong></td>
            </tr>
            <tr>
                <td><span class="font">Father's / Husband's Name</span> :<br>
                    (தந்தை / கணவர் பெயர்)
                </td>
                <td><strong class="font">{{$emp->parent_name}}</strong></td>
            </tr>
            <tr>
                <td><span class="font">Date of Birth</span> :<br>
                    (பிறந்த தேதி)
                </td>
                <td><strong class="font">{{carbon\carbon::parse($emp->d_o_b)->format('d-m-Y')}}</strong></td>
            </tr>
            <tr>
                <td><span class="font">Sex</span> :<br>
                    (இனம்)
                </td>
                <td><strong class="font">{{$emp->gender}}</strong></td>
            </tr>
            <tr>
                <td><span class="font">Marital Status</span> :<br>
                    (திருமணமானவரா / இல்லையா ?)
                </td>
                <td><strong class="font">{{$emp->marital_status}}</strong></td>
            </tr>
            <tr>
                <td><span class="font">Account No</span> :<br>
                    (கணக்கு எண்)
                </td>
                <td></td>
            </tr>
            <tr>
                <td><span class="font">Address</span> :<br>
                    (முகவரி)
                </td>
                <td><strong class="font">{{$emp->permanent_address}}</strong></td>
            </tr>

        </table>
    </div>
    <div class="sub-header">
        <p>
            <span class="font">Part - A</span> <br>
            பிரிவு அ (தொ.வை.நி)
        </p>
    </div>
    <span class="font">I hereby nominate the person(s) cancel the nomination made by me previously and nominate the person(s) mentioned below to receive the amount
        standing to my credit in the Employee Provident Fund, in the event of my death</span> <br>
    என் இழப்புக்கு பின்னால் என் கணக்கில் உள்ள வைப்பு நிதி தொகையை பெற்றிட கீழ்க்காணும் நபர்களை நியமிக்கிறேன்
    (நான் ஏற்கனவே முன்னால் நியமனத்தை ரத்து செய்கிறேன்)

    <div class="border-all">
        <table class="family-details font">
            <tr>
                <th>S.NO</th>
                <th>Name & Address of the Nominee / Nominee's</th>
                <th>Nominee Relationship with the member</th>
                <th>Date of Birth</th>
                <th>Total amount or share of accumulations in Provident fund
                    to be paid to each nominee
                </th>
                <th>If the Nominee is a Minor,Name & relationship & Address of the Guardian
                    who may Receive the Amount during the Minority of Nominee
                </th>
            </tr>
            @if (count($emp_nominees) > 0)
            @foreach ($emp_nominees as $nominee)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$nominee->name}}</td>
                <td>{{$nominee->relationship}}</td>
                <td style="width: 80px;">{{Carbon\Carbon::parse($nominee->d_o_b)->format('d-m-Y')}}</td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
            @endif

        </table>
    </div>
    <p>* &nbsp; <span class="font">Certified that i have no family as defined in para 2(g) of the Employees Provident Fund Scheme 1952 and should i
            acquire a family hereafter the above nomination should be deemed as cancelled.</span> <br>
        தொழிலாளர் வருங்கால வைப்பு நிதித்திட்டம் 1952 பாரா 2(1)ன் படி எனக்கு குடும்பம் இல்லையெனில் இனிமேல் குடும்பம் ஏற்பட்டால்
        மேற்காணும் நியமனம் ரத்தாகும் என சான்று அளிக்கிறேன்.
    </p>
    <p><span class="font">1. Name (in Block Letters)</span> <br>
        முகவரி
    </p>
    <br>
    <p>
        <span class="font">1. Name (in Block Letters)</span> <br>
        முகவரி
    </p>
    <div class="sign">
        <p style="display: inline-block;padding-right: 40px;padding-top: 20px;" class="font">Note : A fresh nomination shall be made by the member <br>
            on his marriage and any nomination made before such <br>
            marriage shall be deemd to be invalid
        </p>
        <p style="display: inline-block;"><span class="font">Signature Or Thumb Impression of the Subscriber</span> <br>
            உறுப்பினர் கையொப்பம் (அ) இடது கை பெருவிரல் ரேகை
        </p>
    </div>

    <div class="page-break"></div>

    <div class="header">
        <p>
            <strong class="font">PART - B (EPS) (PARA 18)</strong> <br>
            படிவம் - 2 (திருத்தியது)
        </p>
    </div>
    <p><span class="font">I hereby furnish below particulars of the members of my family who would be eligible to receive widow / Children Pension in the Event of my death</span> <br>
        என்னுடைய இழப்புக்கு பின்னால விதவை ஓய்வூதியம் குழந்தைகள் ஓய்வூதியம் பெற்றிட தகுதியுள்ள எனது குடும்ப உறுப்பினர்கள் விவரங்களை கீழே கொடுத்துள்ளேன்
    </p>

    <div class="border-all">
        <table class="nominee">
            <tr>
                <th style="width: 20%;"><span class="font">SI.NO</span> <br>
                    வ.எண்
                </th>
                <th style="width: 30%;">
                    <span class="font">Name of the Family Members</span> <br>
                    குடும்ப உறுப்பினர்களின் பெயர்
                </th>
                <th style="width: 20%;">
                    <span class="font">Address</span> <br>
                    முகவரி
                </th>
                <th style="width: 20%;">
                    <span class="font">Date Of Birth</span> <br>
                    பிறந்த தேதி
                </th>
                <th style="width: 20%;">
                    <span class="font">Relationship</span> <br>
                    உறவு
                </th>
            </tr>
            @if (count($emp_nominees) > 0)
            @foreach ($emp_nominees as $nominee)
            <tr class="font">
                <td>{{$loop->iteration}}</td>
                <td>{{$nominee->name}}</td>
                <td>{{$emp->permanent_address}}</td>
                <td>{{Carbon\Carbon::parse($nominee->d_o_b)->format('d-m-Y')}}</td>
                <td>{{$nominee->relationship}}</td>
            </tr>
            @endforeach
            @endif
        </table>
    </div>
    <p>*<span class="font"> Certified that I have no family as defined in para 2(vii) of Employee Pension Scheme 1995 and should i acquire a family hereafter I Shall
            furnish thereon in the above form.</span> <br>
        தொழிலாளர் ஓய்வூதியம் திட்டம் 1995ல் பாரா 2 ஆ ன் படி எனக்கொன்று குடும்பம் என்றும் இல்லை இனி ஏற்பட்டால் மேற்சொன்ன
        படிவத்தில் அதற்கேற்ப தகவல்களை தருவேன்.
    </p>
    <p>&nbsp; <span class="font">I hereby Nominate the following person for receiving the monthly widow pension(admissible under para 16(2) (g) (i) & (ii) in the
            event of my death without leaving any family members for receiving pension)</span> <br>
        என் இறப்புக்கு பின்னால் மாதாந்திர விதவை ஓய்வூதியம் பெற்றிட என் குடும்பத்தில் உரிய நபர் இல்லாத பட்சத்தில் கீழ்க்காணும்
        நபரை ஓய்வூதியம் பெற நியமனம் செய்கிறேன் பாரா 16(2) (ஜி) (1) & (11)ன் படி .
    </p>

    <div class="border-all">
        <table class="family-details font">
            <tr>
                <th>SI.NO</th>
                <th>Name & Address of the Nominee / Nominees</th>
                <th>Date of Birth</th>
                <th>Total amount or share of accumulations in Provident Fund to be paid to each nominee</th>
                <th>If the Nominee is a Minor,Name & Relationship & Address of the Guardian who may Receive the Amount during the Minority of Nominee</th>
            </tr>
            @if (count($emp_nominees) > 0)
            @foreach ($emp_nominees as $nominee)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$nominee->name}} <br>
                    {{$emp->permanent_address}}
                </td>
                <td style="width: 80px;">{{Carbon\Carbon::parse($nominee->d_o_b)->format('d-m-Y')}}</td>
                <td>100%</td>
                <td></td>
            </tr>
            @endforeach
            @endif
        </table>
    </div>
    <p style="padding-top: 5px;"><span class="font">Date</span> :<br>
        தேதி
    </p>
    <div style="border-bottom: 2px solid black;">
        <p style="display: inline-block;padding-left: 30px;"><span class="font">Strike out Whichever is not applicable</span> <br>
            தேவையற்றதை நீக்கிடுக
        </p>

        <p style="display: inline-block;padding-left: 30px;">
            <span class="font">Signature or Thumb Impression of the Subscriber</span><br>
            உறுப்பினர் கையொப்பம் / இடது கை பெருவிரல்
        </p>
    </div>
    <div style="text-align: center;font-size: 11px;">
        <p><span class="font">Certificate By Employer</span> <br>
            (நிறுவன உரிமைகளின் சான்று)
        </p>
    </div>
    <p><span class="font">certified that the above declaration and nomination has been signed / thumb impression before me by Shri/ Smt/ Kum&nbsp; <u><strong>{{$emp->name}}</strong></u> &nbsp;employed
            in my establishment after he / she has read the entries have been read over to him / her by me and got confirmed by him / her.</span> <br>
        எனது நிறுவனத்தில் பணிபுரியும் விளக்கி கூறப்படும் பின் அதனால் பொருள் புரிந்து மேற்காணும் உறுதிமொழி மற்றும் நியமனத்தில்
        கையொப்பம் கைரேகை என் முன்னால் இட்டுள்ளார் என்பதற்கு இதுவே சான்று.
    </p>
    <p>
        <span class="font">Place</span> &nbsp;: <br>
        இடம்
    </p>
    <p style="padding-left: 300px;">
        <span class="font">Signature of the employer or authorised officer of the establishment</span> <br>
        நிறுவன உரிமையாளரின் / நிறுவனத்தின் அதிகாரம் பெற்றவரின் கையொப்பம்
    </p>
    <p style="display: inline-block;padding-right: 250px;">
        <span class="font">Place</span> &nbsp;: <br>
        இடம்
    </p>
    <p style="display: inline-block;">
        <span class="font">Desgination</span> : <br>
        பதவி
    </p>
    <br>
    <br>
    <p style="padding-left: 300px;">
        <span class="font">Name & Address of the factory / Establishment or Rubber Stamp there on</span> <br>
        தொழிலகத்தின் / நிறுவனத்தின் பெயரும் முகவரியும் அல்லது முத்திரையும்
    </p>

</body>

</html>
