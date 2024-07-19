<!DOCTYPE html>
<html>
<head>
    <title>Plantilla PDF</title>
</head>
<body>
    <h1>{{ $documento->nombre }}</h1>
    <p>Documento: {{ $documento->nrodocumento }}</p>
    <p>Placa: {{ $documento->placa }}</p>
</body>
</html>
