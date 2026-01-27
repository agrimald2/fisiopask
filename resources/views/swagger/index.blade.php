<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FisioSalud MCP API - Documentación</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui.css" />
    <style>
        html {
            box-sizing: border-box;
            overflow-y: scroll;
        }
        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }
        body {
            margin: 0;
            background: #fafafa;
        }
        .swagger-ui .topbar {
            background-color: #1a365d;
        }
        .swagger-ui .topbar .download-url-wrapper .select-label {
            display: none;
        }
        .swagger-ui .info .title {
            color: #1a365d;
        }
        .swagger-ui .opblock.opblock-get .opblock-summary-method {
            background: #2e7d32;
        }
        .swagger-ui .opblock.opblock-post .opblock-summary-method {
            background: #1565c0;
        }
        .swagger-ui .opblock.opblock-put .opblock-summary-method {
            background: #f57c00;
        }
        .swagger-ui .opblock.opblock-delete .opblock-summary-method {
            background: #c62828;
        }
        .custom-header {
            background: linear-gradient(135deg, #1a365d 0%, #2c5282 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .custom-header h1 {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 24px;
            font-weight: 600;
        }
        .custom-header .badge {
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="custom-header">
        <h1>🏥 FisioSalud MCP API</h1>
        <span class="badge">v1.0.0</span>
    </div>
    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.11.0/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: "{{ route('mcp.docs.spec') }}",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                persistAuthorization: true,
                displayRequestDuration: true,
                filter: true,
                tryItOutEnabled: true
            });
            window.ui = ui;
        };
    </script>
</body>
</html>
