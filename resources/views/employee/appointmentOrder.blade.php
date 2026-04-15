<!--
    1. Emp Name
    2. Emp Address
    3. Emp Code
    4. Department
    5. Designation
    6. Date of Applied
    7. Date of Interview
    8. Date Of Joining
    9. Salary Per Shift

-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emp->name . "(" . $emp->code . ")" }} - Appointment Order</title>

    <style>
        body {
            font-family: 'TamilFont';
            font-size: 13px;
            line-height: 1.2;
            text-transform: uppercase;
            margin-top: 0;
        }

        @page {
            size: A4;
            margin: 10mm;
        }

        @font-face {
            font-family: 'TamilFont';
            src: url("/fonts/NotoSansTamil-Regular.ttf")format('truetype');
        }

        .header {
            text-align: center;
            font-weight: bold;
            font-family: 'Times New Roman', Times, serif;
        }

        .header h2 {
            padding-top: 10px;
            border-bottom: 2px solid black;
            display: inline-block;
        }

        .sub td {
            padding-left: 20px;
        }

        .sub p {
            padding-left: 20px;
        }

        .sub {
            margin-bottom: 20px;
        }

        .sub p {
            padding-left: 20px;
        }

        .section {
            padding-left: 100px;
            padding-top: 10px;
        }

        .no-border span {
            display: inline-block;
            padding-right: 60px;
        }

        .no-border p {
            padding-left: 40px;
        }

        .esi-pf span {
            display: inline-block;
            padding-right: 120px;
        }

        .esi-pf p {
            padding-left: 40px;
        }

        .offers span {
            display: inline-block;
            padding-right: 30px;
        }

        .perc {
            display: inline-block;
        }

        .end span {
            display: inline-block;
            /* padding-right: 160px; */
            /* padding-top: 80px; */
            padding-top: 20px;
        }

        u {
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ Str::upper($company->name) }}</h1>
        <p>
            {{ Str::upper($company->address) . ", " . $company->district . " - " . $company->pincode . ", " . $company->state }}
        </p>
        <h2>APPOINTMENT ORDER / <span style="font-family: 'TamilFont';">பணி நியமன உத்தரவு</span></h2>
    </div>

    <div>
        <u>
            <strong>TO :</strong>
        </u>
    </div>

    <br>

    <table class="sub">
        <tr>
            <td><span style="font-family: 'Times New Roman', Times, serif;">Name</span> பெயர் / <span style="font-family: 'Times New Roman', Times, serif;">Address </span> முகவரி :</td>
            <td style="font-family: 'Times New Roman', Times, serif;"><strong>{{$emp->name}}</strong></td>
        </tr>
        <tr>
            <td></td>
            <td style="font-family: 'Times New Roman', Times, serif;"><strong>{{$emp->present_address}}</strong></td>
        </tr>
    </table>

    <br>

    <div>
        <u>
            <strong>பார்வை :</strong>
        </u>
    </div>
    <div class="section">
        <strong><u>{{ \Carbon\Carbon::parse($emp->d_o_j)->format('d-m-Y') }}</u> ம் தேதியிட்ட விண்ணப்பம்</strong>
    </div>
    <br>
    <div>
        <u>
            <strong>பொருள் :</strong>
        </u>
    </div>
    <div class="sub">
        <p>நியமன உத்தரவு தொடர்பாக</p>
        <p>பார்வையில் கண்ட <u><strong>{{ \Carbon\Carbon::parse($emp->d_o_j)->format('d-m-Y') }}</strong></u> தேதியில் உங்களது விண்ணப்பத்தின் அடிப்படையில்
            <u><strong>{{ \Carbon\Carbon::parse($emp->d_o_j)->format('d-m-Y') }}</strong></u> தேதியன்று உங்களுடன் நடைபெற்ற நேர்முகத் தேர்வின் அடிப்படையில் மேற்க்கண்ட நிபந்தனைகளுக்கு உட்பட்டு
            <u><strong>{{ \Carbon\Carbon::parse($emp->d_o_j)->format('d-m-Y') }}</strong></u> தேதி முதல் நமது கம்பெனியின் <u><strong>{{ $emp->department }}</strong>
            </u> பிரிவில் <u><strong>{{$emp->designation}}</strong></u>
            ஆக பணி நியமனம் செய்யப்படுகிறீர்கள்.உங்களுக்கு சட்டப்படியான சம்பளம் உபகாரத்தொகை <u><strong>{{number_format($emp->salary)}}/- PER SHIFT</strong></u>
            வழங்கப்படும்.உங்களது அடையாள அட்டை எண்<u><strong> {{$emp->code}} </strong></u> என்பதை இதன் மூலம் தங்களுக்கு தெரிவிக்கப்படுகிறது.
            இந்த ஒப்பந்த படிவம் மற்றும் நிபந்தனைகள் படிவம். இரண்டு நகலாக தயாரிக்கப்பட்டு ஒன்று கம்பெனிக்கும் மற்றொன்று உங்களுக்கும் கொடுக்கப்படும்.இத்துடன் அடையாள அட்டையும் வழங்கப்படும்.
        </p>
    </div>

    <div class="no-border">

        <p>தங்களது சம்பளம் மற்றும் இதர படிகள் கீழ்க்கண்டவாறு நிர்ணயிக்கப்பட்டுள்ளது.</p>
        <span>அடிப்படை ஊதியம் <br>
            பஞ்சப்படி <br>
            இதர படிகள் <br>
            ஆக மொத்தம்</span>
        <span>நாள் /மாதம் <br>
            நாள் /மாதம் <br>
            நாள் /மாதம் <br>
            நாள் /மாதம்</span>
        <span>ஒன்றுக்கு <br>
            ஒன்றுக்கு <br>
            ஒன்றுக்கு <br>
            ஒன்றுக்கு</span>
        <span>RS. <u><strong>{{number_format($emp->salary)}}/- PER SHIFT</strong></u> <br>
            RS._________________ <br>
            RS._________________ <br>
            RS. <u><strong>{{number_format($emp->salary)}}/- PER SHIFT</strong></u></span>
    </div>

    <div class="esi-pf">
        <p>தங்களது சம்பளத்தில் பிடித்தம் செய்யப்படும் விகிதாசாரங்கள் கீழ்க்கண்டவாறு</p>

            <span>தொழிலாளர் வருங்கால வைப்பு <br>
                தொழிலாளர் காப்பீடு <br>
                இதர பிடித்தங்கள்</span>
        <div style="display: inline-block; border-right: 1px solid #000;">
            <span style="padding-right: 60px;">
                12% <br>
                0.75% <br>
                -
            </span>
        </div>
        <span style="padding-left: 60px;">13.75%</span>
    </div>
    <div class="offers">
        <p><strong>சலுகைகள் :</strong></p>
        <span>1. நிறுவனத்தின் வருங்கால வைப்புநிதி பங்கு <br>
            2. நிறுவனத்தின் வருங்கால காப்பீடு பங்கு </span>
            <div style="display: inline-block; border-right: 1px solid #000;">
                <span style="padding-right: 60px;">
                    12% <br>
                    3.25%
                </span>
            </div>
        <p class="perc" style="padding-left: 60px;">16.75%</p>
    </div>
    3. ஊக்க ஊதியம் <span style="font-family: 'Times New Roman', Times, serif;">(BONUS) as applicable MINIMUM</span> <u><strong>8.33%</strong></u> <span style="font-family: 'Times New Roman', Times, serif;">PRESENTED</span> <br>
    <span style="font-family: 'Times New Roman', Times, serif;">4. LEAVE WITH BENEFIT : (IF YOU WORKED 20 DAYS ONE DAY LEAVE ALLOWED OR CASH ENCASHED)</span></span>

    <div class="end">
        <p>தங்களது உழைப்பை உண்மையுடன் நிறுவனத்திற்கு அளித்து மேன்மேலும் தாங்கள் வளர்ச்சி பெற வாழ்த்துக்கள்.</p>
        <span><span style="font-family: 'Times New Roman', Times, serif;">RECEIVED THE COPY </span><br>
        நகலை பெற்றுக்கொண்டேன்</span>
        <span style="padding-left: 330px;">இப்படிக்கு <br>
            நிறுவன மேலாளர்</span>
    </div>
</body>

</html>
