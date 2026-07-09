<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AppointmentOrder</title>
    <link rel="preconnect" href="">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@100..900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            line-height: 1.6;
            margin-top: 0;
        }

        @page {
            size: A4;
            margin: 15mm;
        }

        @font-face {
            font-family: 'TamilFont';
            src: url("/NotoSansTamil-Regular.ttf")format('truetype');
        }

        .header {
            margin-top: 0;
            text-align: center;
            font-weight: bold;
            border-bottom: 2px solid black;
        }

        .TamilFont {
            font-family: 'TamilFont';
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ Str::upper($company->name)  }}</h1>
        <p>{{ Str::upper($company->address) . ", " . $company->district . " - " . $company->pincode . ", " . $company->state }}</p>
    </div>
    <p class="TamilFont">
        தொழில் தகராறு சட்டம் பிரிவு 18(1)ன் படி நிர்வாகத்திற்கும் தொழிலாளருக்கும் இடையே ஏற்பட்ட பணி நியமனம் மற்றும் ஊதிய
        ஒப்பந்தம்.
    </p>
    <p class="TamilFont">
        The Agreement has been made between Employee and Employer as per section 18 (1) of industrial Dispute act. 1947
    </p>
    <p>
        यह समझौता औद्योगिक विवाद अधिनियम, 1947 की धारा 18(1) के प्रावधानों के अनुसार कर्मचारी एवं नियोजक के मध्य संपादित
        किया गया है।
    </p>
    <p class="TamilFont">
        தாங்கள் <strong><u>{{ \Carbon\Carbon::parse($emp->d_o_j)->format('d-m-Y') }}</u></strong> தேதி முதல் எங்கள்
        நிறுவனத்தில் <u><strong>{{ $emp->designation }}</strong></u> பணியிலமர்த்தப்படுகிறீர்கள்
        தாங்கள் பணிபுரியும் நாட்களுக்கு ஊதியமாக ரூ <u><strong>{{ number_format($emp->salary) }}</strong></u>/- நாள்
        ஒன்றுக்கு
        விகிதாசார முறையில் மாத சம்பளம் அடிப்படையில் கணக்கிடப்பட்டு ஊதியமானது பிரதி மாதம் என்ற முறையில் கணக்கிடப்பட்டு,
        பிரதி மாதம் 7ம் தேதிக்குள் வழங்கப்படும். தங்களுக்கு வழங்கப்படும் ஊதியத்திற்கு சம்பள பட்டுவாடா சீட்டு
        வழங்கப்படும்.
    </p>
    <p>
        You have been appointed as a <u><strong>{{ $emp->designation }}</strong></u> W.E.F
        <strong><u>{{ \Carbon\Carbon::parse($emp->d_o_j)->format('d-m-Y') }}</u></strong>
        Your wage will be Rs <u><strong>{{ number_format($emp->salary) }}</strong></u>/- per shift and will be
        calculated based on your working days and Salary
        will be calculated on a monthly basis and distributed on or Before 7th day of Every Month along with the payslip
        notifying as per the wage structure.
    </p>
    <p>
        आपको दिनांक <strong><u>{{ \Carbon\Carbon::parse($emp->d_o_j)->format('d-m-Y') }}</u></strong> से दर्जी
        <u><strong>{{ $emp->designation }}</strong></u> के पद पर नियुक्त किया जाता है।

        आपका वेतन <u><strong>{{ number_format($emp->salary) }}</strong></u>/- प्रति शिफ्ट होगा, जिसकी गणना आपके कार्य
        दिवसों के आधार पर की जाएगी।

        आपका वेतन मासिक आधार पर निर्धारित किया जाएगा तथा प्रत्येक माह की 7 तारीख या उससे पहले वेतन पर्ची (Payslip) सहित,
        वेतन संरचना (Wage Structure) के अनुसार भुगतान किया जाएगा।
    </p>
    <p>
        WORKING HOURS / <span class="TamilFont">வேலை நேரம்</span> / काम करने के घंटे : <strong>9:00 AM TO 6:00 PM</strong>
    </p>
    <p>
        TEA BREAK / <span class="TamilFont">தேனீர் இடைவேளை </span> / चाय ब्रेक : <strong>10:30 AM - 10:45 AM / 3:30 PM - 3:45 PM</strong>
    </p>
    <p>
        LUNCH TIME / <span class="TamilFont">மதிய உணவு நேரம்</span> / भोजन अवकाश: <strong>1:00 PM - 2:00 PM</strong>
    </p>
    <p>
        OVERTIME IF REQUIRED / <span class="TamilFont">கூடுதல் நேரம் தேவைப்பட்டால்</span> / आवश्यकता होने पर ओवरटाइम
        करना होगा। :
        <strong>6:15 PM - 8:15 PM</strong>
    </p>
    <p class="TamilFont">
        வார விடுமுறை ஞாயிற்றுக்கிழமையாகும்
    </p>
    <p>
        Weekly Holiday will be on Sunday
    </p>
    <p>
        हर रविवार साप्ताहिक अवकाश रहेगा।
    </p>
    <p class="TamilFont">
        மிகை பணி செய்ய நேரிடும்போது ஊதியம் பணிபுரிந்த நேரத்தை கணக்கில் கொண்டு இரட்டிப்பாக வழங்கப்படும்.
    </p>
    <p>
        Overtime will be paid on the basic per day wage in double wages for working Extra Hours and will be calculated
        on Basic,
        Dearness Allowance and Conveyance.
    </p>
    <p>
        यदि अतिरिक्त कार्य (ओवरटाइम) किया गया हो, तो उसका भुगतान वेतन के अनुसार किया जाएगा।
    </p>
    <p class="TamilFont">
        தாங்கள் ஈட்டிய ஊதியத்தில் 0.75%க்கு ESI பிடித்தம் செய்யப்படும் மற்றும் தங்களது அடிப்படை ஊதியத்திலிருந்து 12%க்கு
        PF
        பிடித்தம் செய்யப்படும்.
    </p>
    <p>
        Based on the Earning ESI 0.75% and PF 12% will be deducted from your wage.
    </p>
    <p>
        आपकी अर्जित मजदूरी के अनुसार ईएसआई (ESI) 0.75% एवं पीएफ (PF) 12% की राशि आपके वेतन से नियमानुसार काटी जाएगी।
    </p>
    <p class="TamilFont">
        அனைவருக்கும் அடையாள அட்டை ESI அடையாள அட்டை வழங்கப்படும்.
    </p>
    <p>
        ESI Card and Identity Card will be issued for all employees.
    </p>
    <p>
        सभी कर्मचारियों के लिए ईएसआई (ESI) कार्ड तथा पहचान पत्र (Identity Card) जारी किए जाएंगे।
    </p>
    <p class="TamilFont">
        தாங்கள் பணிபுரிந்த காலண்டர் வருடத்தை கணக்கில் கொண்டு பணிபுரிந்த நாட்களை கணக்கில் கொண்டு 20 நாட்களுக்கு
        1 நாள் வீதம் ஈட்டிய விடுப்பாக கருதப்டும்.
    </p>
    <p>
        Privilege leave will be allowed based on 1 day for every 20 Working days (Proceeding Year) and amount will be
        paid during
        October of every year of your last drawn wage.
    </p>
    <p>
        पूर्ववर्ती वर्ष में प्रत्येक 20 कार्य दिवसों पर 1 दिन का विशेषाधिकार अवकाश (Privilege Leave) देय होगा। इस अवकाश
        की राशि का भुगतान प्रत्येक वर्ष अक्टूबर माह में आपके अंतिम प्राप्त वेतन के आधार पर किया जाएगा।
    </p>
    <p>
        <strong>GRATUITY BENEFIT:</strong>
    </p>
    <p>
        IF YOU HAVE COMPLETED FIVE YEARS OF SERVICE IN OUR COMPANY, YOU WILL BE ELIGIBLE FOR GRATUITY PAYMENT @ 15 DAYS
        PAYMENT FOR ONE YEAR
        OF SERVICE.
    </p>
    <p class="TamilFont">
        நீங்கள் எங்கள் நிறுவனத்தில் ஐந்தாண்டுகள் சேவையை முடித்திருந்தால், ஒரு வருட சேவைக்கான 15 நாட்களுக்கான
        கிராட்யுட்டி
        பேமெண்ட்டுக்கு நீங்கள் தகுதி பெறுவீர்கள்.
    </p>
    <p>
        यदि आप हमारी कंपनी में लगातार पाँच वर्ष की सेवा पूर्ण करते हैं, तो आप प्रत्येक पूर्ण सेवा वर्ष के लिए 15 दिनों
        के वेतन की दर से ग्रेच्युटी (Gratuity) के भुगतान के पात्र होंगे।
    </p>
    <p>
        <strong>MATERNITY BENEFIT:</strong>
    </P>
    <p>
        IF A FEMALE EMPLOYEE BECOMES PREGNANT WHILE WORKING IN OUR COMPANY SHE IS ENTITLED TO AVAIL MATERNITY BENEFITS
        FROM ESI @ 6 MONTHS
        HOLIDAYS WITH FIFTY PERCENT PAYMENT OF THE LAST DRAWN SALARY
    </p>
    <p class="TamilFont">
        எங்கள் நிறுவனத்தில் பணிபுரியும் போது ஒரு பெண் ஊழியர் கர்ப்பமாகிவிட்டால், அவர்கள் மகப்பேறுக்கான பலன்களை
        பெறுவதற்கு
        அவர்களுக்கு உரிமை உண்டு. கடைசியாக கொடுக்கப்பட்ட சம்பளத்திலிருந்து ஐம்பது சதவீதத்துடன் 6 மாத விடுமுறை
        வழங்கப்படும்.
    </p>
    <p>
        यदि हमारी कंपनी में कार्यरत कोई महिला कर्मचारी गर्भवती होती है, तो वह ईएसआई (ESI) के प्रावधानों के अनुसार 6 माह
        के मातृत्व अवकाश तथा अंतिम आहरित वेतन के 50% भुगतान सहित मातृत्व लाभ प्राप्त करने की पात्र होगी।
    </p>
    <p class="TamilFont">
        தங்களுக்கு விடுப்பு தேவைப்படும்போது விடுப்பு விண்ணப்பம் மேலாளர் அனுமதி பெற்று விடுப்பு பெற்றுக் கொள்ளலாம்.
    </p>
    <p>
        You can avail leave only after duly authorised by the Manager / Supervisor. In case of any emergency you can
        intimate over
        Phone Call or through any Communication will be accepted.
    </p>
    <p>
        आप प्रबंधक/पर्यवेक्षक की पूर्व अनुमति प्राप्त करने के बाद ही अवकाश ले सकते हैं। किसी भी आपातकालीन स्थिति में आप
        फोन कॉल या किसी अन्य संचार माध्यम से सूचना दे सकते हैं, जिसे मान्य माना जाएगा।
    </p>
    <p class="TamilFont">
        தாங்கள் ஈட்டிய ஊதியத்தில் அடிப்படை ஊதியத்தை கணக்கில் கொண்டு அக்டோபர் மாதம் முதல் செப்டம்பர் மாதம் வரை
        கணக்கிட்டு போனஸானது குறைந்தபட்சம் 8.33% வழங்கப்படும்.
    </p>
    <p>
        Minimum Bonus of 8.33% will be paid during Deepavali of every year and the period will be calculated between
        October to September.
    </p>
    <p>
        प्रत्येक वर्ष दीपावली के अवसर पर न्यूनतम 8.33% बोनस का भुगतान किया जाएगा। बोनस की गणना हेतु अवधि प्रत्येक वर्ष
        अक्टूबर माह से सितंबर माह तक निर्धारित होगी।
    </p>
    <p class="TamilFont">
        தேசிய மற்றும் பண்டிகை விடுமுறை நாட்களுக்கு சட்டப்படி ஊதியம் வழங்கப்படும்.
    </p>
    <p>
        National & Festival Holidays will be paid as per the act.
    </p>
    <p>
        राष्ट्रीय एवं त्यौहार संबंधी अवकाश लागू अधिनियम के प्रावधानों के अनुसार प्रदान किए जाएंगे तथा उनका वेतन
        नियमानुसार देय होगा।
    </p>
    <p class="TamilFont">
        பணியிலுருந்து விலக நேரிடும்போது தாங்கள் 30 நாட்களுக்கு முன்னதாக தாங்கள் எழுத்து மூலம் மேலாளரிடம் சமர்ப்பிக்க
        வேண்டும்.
        தவறுபவர்கள் மேற்குறிய நாட்களை கணக்கில் கொண்டு வழங்கும் செட்டில்மென்ட் தொகையில் பிடித்தம் செய்யப்படும்.
        பணி விலகிய பின் பணி விலகல் சான்றிதழ்கள் வழங்கப்படும்.மற்ற படி பணி விலக நேரிடும்போது தொழிற்தகராறு சட்டப்படி
        பணப்பயன்கள் 30 நாட்களுக்குள் வழங்கப்படும்.
    </p>
    <p>
        In case you may relieve from company, you have to submit the Resignation letter by writing to the manager 30
        days in advance, Service
        Certificate and relieving order Will be issued at the time of leaving. All legal benefits will be settled within
        30 days from your
        leaving of service.
    </p>
    <p>
        यदि आप कंपनी की सेवा छोड़ना चाहते हैं, तो आपको प्रबंधक (Manager) को 30 दिन पूर्व लिखित रूप में त्यागपत्र
        (Resignation Letter) प्रस्तुत करना होगा। सेवा समाप्ति के समय आपको सेवा प्रमाण-पत्र (Service Certificate) एवं
        कार्यमुक्ति आदेश (Relieving Order) जारी किए जाएंगे। सेवा छोड़ने की तिथि से 30 दिनों के भीतर आपके सभी वैधानिक
        लाभों (Legal Benefits) का नियमानुसार भुगतान/निपटान कर दिया जाएगा।

    </p>
    <p class="TamilFont">
        பணி விலகிய 60 நாட்களுக்கு பின் பி.எப். சேமிப்புத்தொகை வருங்கால வைப்பு நிதி அலுவலகத்தின் மூலம் தங்கள் சேமிப்பு
        கணக்கில் வரவு வைக்கப்படும்.
    </p>
    <p>
        Provident Fund Settlement form will be forwarded to the P.F. Office after 60 days of your relieving and amount
        will be credited in
        your Bank Account by the Provident Office.
    </p>
    <p>
        आपकी सेवा समाप्ति/कार्यमुक्ति के 60 दिनों पश्चात् भविष्य निधि (Provident Fund) निपटान प्रपत्र संबंधित पी.एफ.
        कार्यालय को भेजा जाएगा। भविष्य निधि कार्यालय द्वारा स्वीकृति के उपरांत राशि आपके बैंक खाते में जमा कर दी जाएगी।
    </p>
    <p class="TamilFont">
        தங்கள் வருகை நேரம். பணி திறன் செயல்பாடு ஒழுங்கின்மை. கீழ்ப்படிதல் மற்றும் இதர வகைகள் தொழிற்சாலை நிலையானை
        சட்டத்திற்கு கட்டுப்பட்டதாகும்.
    </p>
    <p>
        Your Attendance, Behaviour , Discipline and other Activities and other misconduct will be governed as per the
        standing order.
    </p>
    <p>
        आपकी उपस्थिति, आचरण, अनुशासन, अन्य कार्यकलापों एवं किसी भी प्रकार के कदाचार से संबंधित मामले स्थायी आदेश
        (Standing Order) के प्रावधानों के अनुसार संचालित किए जाएंगे।
    </p>
    <p class="TamilFont">
        தங்கள் 58 வயது வரை மட்டுமே பணி புரிய அனுமதிக்கப்படும்.
    </p>
    <p>
        Your Retirement Age will be 58 years.
    </p>
    <p>
        आपकी सेवानिवृत्ति आयु 58 वर्ष निर्धारित की गई है।
    </p>
    <p class="TamilFont">
        இந்த ஒப்பந்தமானது நிர்வாக தரப்பிலும் தொழிலாளர் தரப்பிலும் ஒருமனதாக ஒப்புக் கொள்ளப்பட்டு கையொப்பமிடப்படுகிறது.
    </p>
    <p>
        This Agreement has been Mutually agreed and signed by Employer and Employee.
    </p>
    <p>
        यह समझौता नियोक्ता एवं कर्मचारी के मध्य आपसी सहमति से संपादित किया गया तथा दोनों पक्षों द्वारा हस्ताक्षरित किया
        गया है।
    </p>
    <div style="padding-left: 300px;">
        <p>
            <span class="TamilFont">தொழிலாளர்</span> / EMPLOYEE / कर्मचारी
        </p>
        <p>
            <span class="TamilFont">பெயர்</span> / NAME / नाम : <u><strong>{{ $emp->name }}</strong></u>
        </p>
        <p>
            <span class="tamilFont">முகவரி</span> / ADDRESS / पता : <u><strong>{{ $emp->present_address }}</strong></u>
        </p>
    </div>
    <br>
    <p>
        <span style="padding-left: 30px;" class="TamilFont">நிர்வாக தரப்பில்</span><br>
    <p><span class="TamilFont">மேலாளர்</span> / Manager / प्रबंधक </p>
    </p>
    <p>
        <span class="TamilFont">இடம்</span> : <br>
        Place : <br>
        स्थान :
    </p>
    <div>
        <p style="display: inline-block;">
            <span class="TamilFont">தேதி</span> : <br>
            Date : <br>
            दिनांक :
        </p>
        <p style="padding-left: 450px; display: inline-block;">
            <span class="TamilFont">கையொப்பம்</span> / Signature / हस्ताक्षर
        </p>
    </div>
    <div>
        <p style="display: inline-block;" class="TamilFont">
            நகல் : <br>
            &nbsp;
        </p>
        <p style="display: inline-block;" class="TamilFont">
            1. தொழிலாளர் அலுவலர், கோயம்புத்தூர். <br>
            2. தொழிலாளர் ஆணையர், பாலசுந்தரம் ரோடு, கோயம்புத்தூர்.
        </p>
    </div>
    <div>
        <p style="display: inline-block;">
            Copy : <br>
            &nbsp;
        </p>
        <p style="display: inline-block;">
            1. Labour Officer, Coimbatore. <br>
            2. Labour Commissioner, Balasundaram Road, Coimbatore.
        </p>
    </div>
    <div>
        <p style="display: inline-block;">
            प्रतिलिपि : <br>
            &nbsp;
        </p>
        <p style="display: inline-block;">
            1. श्रम अधिकारी, कोयंबटूर।<br>
            2. श्रम आयुक्त, बालासुंदरम रोड, कोयंबटूर।
        </p>
    </div>
    <p>
        Note : In case of any deviations in other languages, details given in English language will be final.
    </p>
</body>

</html>
