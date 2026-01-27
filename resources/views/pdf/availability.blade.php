<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disponibilidad de Horarios - Fisiosalud</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: #fff;
        }

        .container {
            padding: 30px 40px;
        }

        /* Header */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0cb8b6;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
            text-align: right;
        }

        .logo {
            max-width: 180px;
            height: auto;
        }

        .header-title {
            font-size: 24px;
            font-weight: bold;
            color: #0cb8b6;
            margin-bottom: 5px;
        }

        .header-subtitle {
            font-size: 14px;
            color: #666;
        }

        .header-date {
            font-size: 11px;
            color: #999;
            margin-top: 10px;
        }

        /* Date Range Banner */
        .date-range-banner {
            background: linear-gradient(135deg, #0cb8b6 0%, #0a9f9d 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            text-align: center;
        }

        .date-range-banner h2 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .date-range-banner p {
            font-size: 13px;
            opacity: 0.9;
        }

        /* Doctor Section */
        .doctor-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .doctor-header {
            background: #f8f9fa;
            border-left: 4px solid #0cb8b6;
            padding: 12px 20px;
            margin-bottom: 15px;
            border-radius: 0 8px 8px 0;
        }

        .doctor-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        /* Days Grid */
        .days-container {
            display: table;
            width: 100%;
        }

        .day-card {
            display: inline-block;
            width: 48%;
            margin-right: 2%;
            margin-bottom: 10px;
            vertical-align: top;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }

        .day-card:nth-child(2n) {
            margin-right: 0;
        }

        .day-header {
            background: #0cb8b6;
            color: white;
            padding: 8px 12px;
            font-weight: 600;
            font-size: 12px;
        }

        .day-header .date {
            float: right;
            font-weight: normal;
            opacity: 0.9;
        }

        .day-slots {
            padding: 10px 12px;
            background: #fff;
        }

        .slot {
            display: inline-block;
            background: #e8f7f7;
            border: 1px solid #0cb8b6;
            border-radius: 15px;
            padding: 4px 12px;
            margin: 3px 3px 3px 0;
            font-size: 11px;
            color: #0a9f9d;
        }

        .slot-time {
            font-weight: 600;
        }

        /* No Availability */
        .no-availability {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 10px;
        }

        .footer-brand {
            color: #0cb8b6;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .footer-contact {
            margin-top: 8px;
        }

        .footer-contact a {
            color: #0cb8b6;
            text-decoration: none;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* Summary Box */
        .summary-box {
            background: #f0fafa;
            border: 1px solid #0cb8b6;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .summary-title {
            font-size: 13px;
            font-weight: bold;
            color: #0cb8b6;
            margin-bottom: 10px;
        }

        .summary-item {
            display: inline-block;
            margin-right: 30px;
            font-size: 11px;
        }

        .summary-item strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <img src="{{ $logoUrl }}" alt="Fisiosalud" class="logo">
            </div>
            <div class="header-right">
                <div class="header-title">Disponibilidad de Horarios</div>
                <div class="header-subtitle">Agenda tu cita con nosotros</div>
                <div class="header-date">Generado el {{ $generatedAt->format('d/m/Y') }} a las {{ $generatedAt->format('H:i') }}</div>
            </div>
        </div>

        <!-- Date Range Banner -->
        <div class="date-range-banner">
            <h2>Horarios Disponibles</h2>
            <p>Del {{ $dateFrom->format('d') }} de {{ $dateFrom->translatedFormat('F') }} al {{ $dateTo->format('d') }} de {{ $dateTo->translatedFormat('F') }} de {{ $dateTo->format('Y') }}</p>
        </div>

        <!-- Summary -->
        <div class="summary-box">
            <div class="summary-title">Resumen</div>
            <div class="summary-item">
                <strong>{{ count($doctors) }}</strong> Licenciado(s) disponible(s)
            </div>
            <div class="summary-item">
                <strong>{{ $dateFrom->diffInDays($dateTo) + 1 }}</strong> Día(s) consultado(s)
            </div>
        </div>

        <!-- Doctors Availability -->
        @foreach($availability as $doctorData)
            <div class="doctor-section">
                <div class="doctor-header">
                    <span class="doctor-name">{{ $doctorData['doctor_name'] }}</span>
                </div>

                @if(count($doctorData['days']) > 0)
                    <div class="days-container">
                        @foreach($doctorData['days'] as $day)
                            <div class="day-card">
                                <div class="day-header">
                                    {{ $day['day_name'] }}
                                    <span class="date">{{ $day['formatted_date'] }}</span>
                                </div>
                                <div class="day-slots">
                                    @foreach($day['slots'] as $slot)
                                        <span class="slot">
                                            <span class="slot-time">{{ $slot['start_time'] }} - {{ $slot['end_time'] }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-availability">
                        No hay horarios disponibles para las fechas seleccionadas
                    </div>
                @endif
            </div>
        @endforeach

        <!-- Footer -->
        <div class="footer">
            <div class="footer-brand">FISIOSALUD</div>
            <div>Centro de Fisioterapia y Rehabilitación</div>
            <div class="footer-contact">
                <a href="https://fisiosalud.pe">www.fisiosalud.pe</a>
            </div>
        </div>
    </div>
</body>
</html>
