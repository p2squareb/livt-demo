<!DOCTYPE html>
<html>
<head>
    <title>Email</title>
</head>
<body>
    <h1>Hello, {{ $nickname }}</h1>
    <p>Your access to the site has been restricted due to violation of site policies.</p>
    <p>Restriction period: {{ $period_type }}days, until {{ $until_date }}</p>
    <p>Reason for restriction: {{ $cause }}</p>
</body>
</html>