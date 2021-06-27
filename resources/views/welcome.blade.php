<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv=“Content-Security-Policy” content=“default-src ‘self’ gap://ready file://* *; style-src ‘self’ ‘unsafe-inline’; script-src ‘self’ ‘unsafe-inline’ ‘unsafe-eval’”/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="/js/app.js" defer></script>
    <title>Weather app</title>
</head>
<body class="bg-blue-300">
    <div id="app" class="flex justify-center pt-16">
        <weather-app></weather-app>
    </div>
</body>
</html>