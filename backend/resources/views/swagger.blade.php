<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information Management REST API - Swagger UI Documentation</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.18.2/swagger-ui.css" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin: 0;
            background: #fafafa;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        .topbar {
            background-color: #1e293b;
            padding: 12px 24px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .topbar h1 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar .badge {
            background: #2563eb;
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 9999px;
            text-transform: uppercase;
        }
        .topbar a {
            color: #93c5fd;
            text-decoration: none;
            font-size: 0.875rem;
        }
        .topbar a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <h1>
            <span>🎓 Student Information Management API</span>
            <span class="badge">v1.0.0</span>
        </h1>
        <a href="{{ asset('docs/openapi.json') }}" target="_blank">View OpenAPI JSON Spec ↗</a>
    </div>

    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist@5.18.2/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.18.2/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            window.ui = SwaggerUIBundle({
                url: "{{ asset('docs/openapi.json') }}",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "BaseLayout",
                docExpansion: "list",
                filter: true,
                persistAuthorization: true
            });
        };
    </script>
</body>
</html>
