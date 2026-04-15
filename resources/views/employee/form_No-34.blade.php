<!--
    1. Emp Name
    2. Emp Code
    3. Designation

    Family Details
    1. Name
    2. Relationship
    3. Address
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emp->name . "(" . $emp->code . ")" }} -Form - 34</title>
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

    .border-all {
        border: 1px solid black;
        padding: 10px;
    }

    .header {
        text-align: center;
    }

    .sub-header {
        text-align: center;
    }

    .sub-header h1 {
        font-size: 18px
    }

    .sub-header p {
        margin: 3px;
    }

    .sub-header h2 {
        margin-top: 3px;
    }

    .bio td {
        padding: 15px;
    }

    .tamil {
        text-align: center;
        margin-top: 90px;
        margin-bottom: 40px;
    }

    .tamilFont {
        font-family: 'TamilFont';
    }
</style>

<body>
    <div class="header">
           <h1>{{ Str::upper($company->name) }}</h1>
            <p>{{ Str::upper($company->address) . ", " . $company->district . " - " . $company->pincode . ", " . $company->state }}</p>
    </div>
    <div class="border-all">
        <div class="sub-header">
            <u>
                <h1>THE FACTORIES ACT 1948 & THE TAMILNADU FACTORIES RULES 1950</h1>
                <p>FORM NO. 34</p>
            </u>
            (Prescribed Under Rule 93)
            <u>
                <h2>NOMINATION</h2>
            </u>
        </div>
        <table class="bio">
            <tr>
                <td>Worker Name / कार्यकर्ता का नाम :</td>
                <td><strong>{{$emp->name}}</strong></td>
            </tr>
            <tr>
                <td>ID Number / आईडी नंबर :</td>
                <td><strong>{{$emp->code}}</strong></td>
            </tr>
            <tr>
                <td>Designation / पद :</td>
                <td><strong>{{$emp->designation}}</strong></td>
            </tr>
        </table>
        <p>
            I here by require that in the event of the death before resuming work, the balance of my pay due for the period of leave with
            wages and pending payments / salaries not available of shall be paid to <u><strong>{{$emp_nominees[0]->name}}</strong></u> who is my <u><strong>{{$emp_nominees[0]->relationship}}</strong></u>
            and Resides at <strong>{{$emp_nominees[0]->address}}</strong>
        </p>
        <div class="tamil tamilFont">
            <p><b>படிவம் எண். 34 <br>
                    (நிர்ணயிக்கப்பட்ட விதி 93 சி)
                </b></p>
        </div>
        <p style="margin-bottom: 200px;" class="tamilFont">
            நான் பணியிலுள்ள போது இறக்க நேரிடின் எனது ஊதிய நிலுவை மற்றும் விடுப்பு கால ஊதியத்தை <b style="font-family: 'Times New Roman', Times, serif;">{{$emp_nominees[0]->address}}</b> முகவரியில் வசிக்கும்
            எனது <b style="font-family: 'Times New Roman', Times, serif;">{{$emp_nominees[0]->relationship}}</b> திரு/திருமதி <b style="font-family: 'Times New Roman', Times, serif;">{{$emp_nominees[0]->name}}</b> அவர்களிடம் கொடுக்குமாறு கேட்டுக்கொள்கிறேன்.
        </p>
        <p style="display: inline-block;">
            Witness : <br>
            <span class="tamilFont">சாட்சிகள் :</span> <br>
            गवाह :
            <br>
            1. <br>
            <br>
            2.

        </p>
        <p style="display: inline-block;padding-left: 350px;">
            Signature of the Worker <br>
            <br>
            कर्मचारी का हस्ताक्षर <br>
            <br>
            <span class="tamilFont">தொழிலாளர் கையொப்பம் <br>
                (அ) இடது கை பெருவிரல் ரேகை</span>
        </p>
    </div>
</body>

</html>
