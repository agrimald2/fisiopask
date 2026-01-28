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
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        .container {
            padding: 20px 30px;
        }

        /* Header */
        .header {
            width: 100%;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #0cb8b6;
        }

        .header-table {
            width: 100%;
        }

        .header-left {
            width: 40%;
        }

        .header-right {
            width: 60%;
            text-align: right;
        }

        .logo {
            max-width: 150px;
            height: auto;
        }

        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #0cb8b6;
            margin-bottom: 3px;
        }

        .header-subtitle {
            font-size: 12px;
            color: #666;
        }

        .header-date {
            font-size: 10px;
            color: #999;
            margin-top: 8px;
        }

        /* Date Range Banner */
        .date-range-banner {
            background-color: #0cb8b6;
            color: white;
            padding: 12px 20px;
            margin-bottom: 15px;
            text-align: center;
        }

        .date-range-banner h2 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .date-range-banner p {
            font-size: 11px;
        }

        /* Summary Box */
        .summary-box {
            background-color: #f0fafa;
            border: 1px solid #0cb8b6;
            padding: 10px 15px;
            margin-bottom: 15px;
        }

        .summary-title {
            font-size: 11px;
            font-weight: bold;
            color: #0cb8b6;
            margin-bottom: 5px;
        }

        .summary-text {
            font-size: 10px;
        }

        /* Doctor Section */
        .doctor-section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .doctor-header {
            background-color: #f8f9fa;
            border-left: 4px solid #0cb8b6;
            padding: 8px 15px;
            margin-bottom: 10px;
        }

        .doctor-name {
            font-size: 13px;
            font-weight: bold;
            color: #333;
        }

        /* Days Table */
        .days-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .days-table th {
            background-color: #0cb8b6;
            color: white;
            padding: 6px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
        }

        .days-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
        }

        .day-name {
            font-weight: 600;
            color: #333;
            font-size: 11px;
        }

        .day-date {
            color: #666;
            font-size: 10px;
        }

        .slots-cell {
            font-size: 10px;
        }

        .slot-tag {
            background-color: #e8f7f7;
            border: 1px solid #0cb8b6;
            padding: 2px 8px;
            margin: 2px 4px 2px 0;
            font-size: 9px;
            color: #0a9f9d;
            display: inline-block;
        }

        /* No Availability */
        .no-availability {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
            font-size: 11px;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 9px;
        }

        .footer-brand {
            color: #0cb8b6;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .footer-contact a {
            color: #0cb8b6;
            text-decoration: none;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* Alternate row colors */
        .days-table tr:nth-child(even) td {
            background-color: #fafafa;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="header-left">
                        <img src="{{ $logoUrl }}" alt="Fisiosalud" class="logo">
                    </td>
                    <td class="header-right">
                        <div class="header-title">Disponibilidad de Horarios</div>
                        <div class="header-subtitle">Agenda tu cita con nosotros</div>
                        <div class="header-date">Generado el {{ $generatedAt->format('d/m/Y') }} a las {{ $generatedAt->format('H:i') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Date Range Banner -->
        <div class="date-range-banner">
            <h2>Horarios Disponibles</h2>
            <p>Del {{ $dateFrom->format('d') }} de {{ $dateFrom->translatedFormat('F') }} al {{ $dateTo->format('d') }} de {{ $dateTo->translatedFormat('F') }} de {{ $dateTo->format('Y') }}</p>
        </div>

        <!-- Summary -->
        <div class="summary-box">
            <div class="summary-title">Resumen</div>
            <div class="summary-text">
                <strong>{{ count($doctors) }}</strong> Licenciado(s) &nbsp;&bull;&nbsp;
                <strong>{{ $dateFrom->diffInDays($dateTo) + 1 }}</strong> Día(s) consultado(s)
            </div>
        </div>

        <!-- Doctors Availability -->
        @foreach($availability as $index => $doctorData)
            <div class="doctor-section">
                <div class="doctor-header">
                    <span class="doctor-name">{{ $doctorData['doctor_name'] }}</span>
                </div>

                @if(count($doctorData['days']) > 0)
                    <table class="days-table">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Día</th>
                                <th style="width: 75%;">Horarios Disponibles</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctorData['days'] as $day)
                                <tr>
                                    <td>
                                        <div class="day-name">{{ $day['day_name'] }}</div>
                                        <div class="day-date">{{ $day['formatted_date'] }}</div>
                                    </td>
                                    <td class="slots-cell">
                                        @foreach($day['slots'] as $slot)
                                            <span class="slot-tag">{{ $slot['start_time'] }} - {{ $slot['end_time'] }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-availability">
                        No hay horarios disponibles para las fechas seleccionadas
                    </div>
                @endif
            </div>

            @if(!$loop->last && count($doctorData['days']) > 15)
                <div class="page-break"></div>
            @endif
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
