<!DOCTYPE html>
<html>
<head>
    <title>Agent Registration Status</title>
</head>
<body>
    <h2>Hi {{ $agent->full_name }},</h2>

    <p>Your registration with <strong>{{ $agent->company_name }}</strong> has been <strong>{{ $status }}</strong>.</p>

    @if($status === 'Approved')
        <p>You may now log in and start using the system.</p>
    @else
        <p>Please review your submission and contact us if you believe this was a mistake.</p>
    @endif

    <p>Thank you,<br>Tan Eng Hong SDN. BHD.</p>
</body>
</html>
