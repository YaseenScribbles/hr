<!--
    1. Emp Name
    2. Date of Joining
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emp->name . "(" . $emp->code . ")" }} - Induction Training</title>
</head>
<style>
    body {
        font-family: 'TamilFont';
        font-size: 13px;
        line-height: 1.5;
        text-transform: uppercase;
        margin-top: 0;
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
        text-align: center;
        font-weight: bold;
        border-bottom: 2px solid black;
    }

    .sub p {
        margin-left: 10px;
    }

    .signature td {
        border: none;
        padding: 4px;
        padding-right: 80px;
    }

    .page-break {
        page-break-after: always;
    }

    .font {
        font-family: 'Times New Roman', Times, serif;
    }
</style>

<body>
    <div class="header">
        <h1>அறிமுக பயிற்சி பதிவேடு / <span class="font">INDUCTION TRAINING</span></h1>
    </div>

    <div class="sub">
        <p><u class="font"><strong>{{$emp->name}} </strong></u> ஆகிய நான் ____________________ அன்று நடந்த அறிமுகப்பயிற்சியில்<span class="font">(INDUCTION TRAINING)</span> கலந்து கொண்டு</p>
    </div>
    <div class="section">
        <span><strong>கீழ்க்கண்ட விபரங்கள்</strong></span>
        <p>
            1. நிறுவனம் மற்றும் அதன் ஆரம்பம், நிறுவனத்தில் உள்ள தலைமை அதிகாரிகள்.
        </p>
        <p>
            2. நிறுவனம் பெற்றுள்ள பல்வேறு சர்வதேச அங்கீகாரங்கள் <span class="font">(ISO9001:2008,SA8000:2008,OEKO-TEX100,WRAP,GOTS,CT-PAT)</span> அதன் நோக்கம் மற்றும் வழிமுறைகள்.
        </p>
        <p>
            3. நிறுவனம் ஏற்றுமதி செய்யும் நாடுகள் மற்றும் வாடிக்கையாளர்கள் மேலும் அவர்களின் அங்கீகாரம்.
        </p>
        <p>
            4. நிறுவனத்தின் பல்வேறு துறைகள் மற்றும் அதன் செயல்பாடுகள்.
        </p>
        <p>
            5. நிறுவன வளாகத்தில் பணியாளர்களுக்கு ஏற்படுத்தப்பட்டுள்ள பல்வேறு வசதிகள்.
        </p>
        <p>
            6. நிறுவனத்தில் ஏற்படத்தப்பட்டுள்ள அவசர கால கதவுகள் / <span class="font">emergency exit</span>, அவசர கால விளக்குகள் / <span class="font">emergency light</span>, அவசர காலங்களில் வெளியேற உதவும் வழிகாட்டும் படங்கள்/ <span class="font">evacuationplan</span>,
            ஒன்று கூடும் இடம் / <span class="font">Assembly Area</span>, அவசர கால மணி, தீயணைப்பான், தீ அணைப்பு குழாய், முதலுதவிப் பெட்டி, அவசரகாலங்களில் தொடர்பு கொள்ள வேண்டிய எண்கள்
            மற்றும் அவசரகால மீட்புக்குழு போன்ற பணியிடப் பாதுகாப்பு வசதிகள் மற்றும் உபரணங்கள், அதன் முக்கியத்துவம், மற்றும் விபத்து மற்றும்
            அபாயகரமான நேரங்களில் அபாயமணியை <span class="font">(FIRM ALARM)</span> எவ்வாறு இயக்குவது, வேலை செய்யும் இடங்களில் இருந்து அவசர காலங்களில் வெளியேறும்<span class="font">(evacuation methods)</span> முறைகளையும்,
            தீ அணைக்கும் கருவி / தீ அணைப்பு நீர் குழாய்களை<span class="font">(FIRE EXTINGUISER / Fire Hydrant)</span> கையாளும் முதலுதவி பெட்டி<span class="font">(FIRST AID BOX)</span> மற்றும் அதில் உள்ள மருந்து வகைகள்<span class="font">(MEDICINES)</span> அதன் பயன்கள்<span class="font">(USAGE)</span> முதலுதவி செய்யும் முறைகளையும்.
        </p>
        <p>
            7. ஒவ்வொரு உற்பத்தி நிலையிலும் பயன்படுத்த வேண்டிய பாதுகாப்பு முறைகள் மற்றும் கீழ்க்கண்ட கருவிகளை <span class="font">(PERSONALPRODUCTIVE EQUIPMENTS)</span>
            உலோக கையுறை / <span class="font">METAL GLOVES</span>(மெஷின் கட்டிங் மாஸ்டர்) முகக்கவசம் / <span class="font">FACE MASK</span> (கட்டிங் மாஸ்டர்கள், டெய்லர்கள், செக்கிங் செய்பவர்கள் மேலும்
            தூசி எழும்பும் பகுதிகளில் வேலை செய்பவர்கள்) நீடில் கார்டு புல்லி கார்டு / <span class="font">NEEDLE GUARD / PULLY GUARD</span> (தையல் மெஷினில் உள்ளது)
            கண்கவசம் / <span class="font">GOGGLES</span> (பட்டன் மெஷின் மற்றும் கட்டிங் மெஷினை இயக்குபவர்) ரப்பரமேட்டுகள் / <span class="font">RUBBER MAT (IRON</span> செய்பவர் மற்றும் மின்பொறியாளர்)
            காதுகவசம் / <span class="font">EAR PLUG</span> (அதிகமான சப்தம் எழும்பும் இயந்திரங்களில் வேலை செய்பவர்கள்) ரப்பர் கையுறை / <span class="font">INSULATED RUBBER GLOVES</span> (மின்பொறியாளர்)
            பயன்படுத்தும் முறைகள் மற்றும் அதன் முக்கியத்துவம். மேலும் ரசாயனங்கள் பயன்படுத்தும் முறைகள் அதன் பாதுகாப்பு வழிமுறைகள்.
        </p>
        <p>
            8. நிறுவனத்தில் செயல்படும் பல்வேறு குழுக்களான / <span class="font">VARIOUS COMMITTEE'S</span> தொழிலாளர் குழு / <span class="font">Works Committee</span>
            சுகாதாரம் மற்றும் பாதுகாப்பு குழு / <span class="font">Health And Safety Committee</span> பாலியல் தொந்தரவு எதிர்ப்பு குழு / <span class="font">Anti Sexual Harassment Committee</span>
            உணவுக் கூட நிர்வாக குழு / <span class="font">Canteen Managing Committee</span> அவசரகால மீட்புக்குழு / <span class="font">Emergency Rescue</span> Team ஆகியவற்றைப் பற்றியும் அதன்
            செயல்பாடுகள் மற்றும் பயன்கள் அந்த குழுவின் உறுப்பினர்கள் யார் என்பதையும் அறிந்து கொண்டோம்.
        </p>
        <div class="page-break"></div>
        <div class="header">
            <h1>அறிமுக பயிற்சி பதிவேடு / <span class="font">INDUCTION TRAINING</span></h1>
        </div>
        <p>
            9. நிறுவனத்தில் உலோக (உடைந்த ஊசிகள் உள்ளிட்ட) பொருட்களை கையாளும் முறைகள், கழிவுப் பொருட்களை கையாளும்
            மற்றும் அப்புறப்படுத்தும் முறைகளையும்.
        </p>
        <p>
            10. எங்களது பணி நியமன ஆணையில் உள்ள நிபந்தனைகள், பின்பற்ற வேண்டிய விதிமுறைகள், நிறுவனத்தின் நிலையானைகள்,
            நிறுவனத்தின் வலை / மிகைப்பணி நேரம். இடைவேளைகள், விடுமுறை, சம்பள நாள், விடுமுறை, அனுமதி கோரும் முறைகள், வருகை பதிவு செய்யும் முறைகள்,
            எங்களின் சம்பளம் மற்றும் மிகைப்பணி ஊதியம் கணக்கிடும் முறை ,பிடித்தங்கள், சம்பள சீட்டின் மாதிரி படிவம் மற்றும் அதில் உள்ள விபரங்கள்
            பணியில் சேரும் பொழுது நாங்கள் கொடுக்க வேண்டிய ஆவணங்கள் மற்றும் நிறுவனம் எங்களுக்கு தரும் ஆவணங்கள் என்ன என்பதையும்.
        </p>
        <p>
            11. வருங்கால வைப்புநிதி, இ.எஸ்.ஐ, அதன் பயன்கள் பெறும் முறைகள், நிறுவன வளாகத்தில் உள்ள மருத்துவ வசதிகள்.
        </p>
        <p>
            12. ஆலோசனை / புகார் பெட்டி மற்றும் அதற்குரிய வழிமுறைகள் அதன் வரிசை முறைகள், தகவல் பலகை.
        </p>
        <p>
            13. பல்வேறு தேவைகளுக்கு தொடர்பு கொள்ள வேண்டிய நபர்களையும் எங்கள் மேற்பார்வையாளர்கள் யார் என்பதை.
        </p>
        <p>
            14. நிறுவனத்தின் முக்கிய கொள்கைகளான தரக் கொள்கை / <span class="font">QUALITY POLICY</span> சமுதாய பொறுப்புடைமை கொள்கை / <span class="font">SOCIAL ACCOUNTABILITY POLICY</span>
            பணியமர்த்தும் கொள்கை / <span class="font">RECRUITMENT POLICY</span> சுகாதாரம் மற்றும் பாதுகாப்பு கொள்கை / <span class="font">HEALTH AND SAFETY POLICY</span> மிகைப் பணிக்கொள்கை / <span class="font">OVER TIME POLICY</span>
            பாகுபாடுகாட்டுவதற்கு எதிரான கொள்கை / <span class="font">ANTI DISCRIMINATION POLICY</span> வேலையில் இருந்து விடுபடுதல் தொடர்பான கொள்கை / <span class="font">RESIGNATION / TERMINATION / DISCHARGE POLICY</span>
            ஊசிகள் தொடர்பான கொள்கை / <span class="font">NEEDLE POLICY</span> பாதுகாப்பு கொள்கை / <span class="font">SECURITY POLICY</span> உள்ளிட்ட அனைத்து கொள்கைகளையும்,
            பல்வேறு வாடிக்கையாளர்களின் <span class="font">"CODE OF CONDUCT"</span> வரைமுறைகள் உள்ளிட்ட மேற்கண்ட அனைத்தையும் முழுமையாக புரிந்து கொண்டோம்,
            மேலும் இவை அனைத்தும் அடங்கிய தொழிலாளர் கையேடு புத்தகம் ஒன்றையும் பெற்றுக்கொண்டோம்.
        </p>
    </div>
    <table class="signature">
        <tr>
            <td>பயிற்சியில் கலந்து கொண்டவரின் பெயர் : <br>
                <span class="font">(NAME & SIGN OF THE TRAINEE)</span></td>
            <td>ஒப்பம் :</td>
            <td></td>
        </tr>
        <tr>
            <td>பயிற்சி அளித்தவரின் பெயர் : <br>
                <span class="font">(NAME & SIGN OF THE TRAINER)</span></td>
            <td>ஒப்பம் :</td>
            <td></td>
        </tr>
    </table>
</body>

</html>
