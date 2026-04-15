<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee PDF Documents</title>
    <style>
        body {
            margin: 0;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #0b1220;
            color: #e2e8f0;
        }
        .container {
            max-width: 960px;
            margin: 32px auto;
            padding: 28px;
            background: #111827;
            border-radius: 20px;
            box-shadow: 0 30px 100px rgba(15, 23, 42, 0.45);
        }
        h1 {
            margin: 0 0 12px;
            font-size: 2rem;
            letter-spacing: -0.03em;
        }
        p {
            margin: 0 0 24px;
            color: #cbd5e1;
        }
        .cards {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            margin-bottom: 24px;
        }
        .card {
            display: block;
            padding: 18px 20px;
            border-radius: 14px;
            background: #1f2937;
            border: 1px solid rgba(148, 163, 184, 0.12);
            color: #e2e8f0;
            text-decoration: none;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            background: #111827;
            border-color: rgba(148, 163, 184, 0.25);
        }
        .card span {
            display: block;
            font-size: 0.9rem;
            opacity: 0.85;
            margin-top: 6px;
            color: #94a3b8;
        }
        .footer {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: space-between;
            align-items: center;
        }
        .back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 44px;
            padding: 0 18px;
            border-radius: 999px;
            background: #3b82f6;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }
        .back:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }
        .note {
            color: #94a3b8;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Employee Documents</h1>
        <p>{{ $employee->code }} - {{ $employee->name }} &middot; {{ $company->name }}</p>

        <div class="cards">
            <a class="card print-link" href="{{ route('pdf.bio-data', $employee->id) }}?print=1">
                Bio Data
                <span>Print employee biodata</span>
            </a>
            <a class="card print-link" href="{{ route('pdf.t-and-c', $employee->id) }}?print=1">
                Terms & Conditions
                <span>Print terms and conditions</span>
            </a>
            <a class="card print-link" href="{{ route('pdf.appointment-order', $employee->id) }}?print=1">
                Appointment Order
                <span>Print appointment order</span>
            </a>
            <a class="card print-link" href="{{ route('pdf.induction-training', $employee->id) }}?print=1">
                Induction Training
                <span>Print induction training</span>
            </a>
            <a class="card print-link" href="{{ route('pdf.form-v', $employee->id) }}?print=1">
                Form V
                <span>Print Form V</span>
            </a>
            <a class="card print-link" href="{{ route('pdf.form-f', $employee->id) }}?print=1">
                Form F
                <span>Print Form F</span>
            </a>
            <a class="card print-link" href="{{ route('pdf.form-2', $employee->id) }}?print=1">
                Form 2
                <span>Print Form 2</span>
            </a>
            <a class="card print-link" href="{{ route('pdf.esic', $employee->id) }}?print=1">
                ESIC
                <span>Print ESIC</span>
            </a>
            <a class="card print-link" href="{{ route('pdf.form-34', $employee->id) }}?print=1">
                Form 34
                <span>Print Form 34</span>
            </a>
        </div>

        <div class="footer">
            <a class="back" href="{{ url()->previous() }}">Back to Reports</a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.print-link').forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();

                    var printUrl = this.href;
                    var win = window.open(printUrl, '_blank');

                    if (!win) {
                        window.location.href = printUrl;
                        return;
                    }

                    win.addEventListener('load', function() {
                        try {
                            win.focus();
                            win.print();
                        } catch (err) {
                            console.warn('Print popup failed', err);
                        }
                    });

                    // setTimeout(function() {
                    //     try {
                    //         win.focus();
                    //         win.print();
                    //     } catch (err) {
                    //         console.warn('Fallback print failed', err);
                    //     }
                    // }, 800);
                });
            });
        });
    </script>
</body>
</html>
