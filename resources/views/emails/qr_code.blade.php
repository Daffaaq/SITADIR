<!DOCTYPE html>
<html>
<head>
    <title>QR Code Email</title>
</head>
<body>
    <h2>QR Code Email</h2>
    <p>Scan the QR code below:</p>
    <img src="{{ $message->embed($qrImagePath) }}" alt="QR Code">
</body>
</html>
