<!DOCTYPE html>
<html>
<head>
    <title>Ticket Created</title>
</head>
<body>
    <h1>Ticket Created</h1>
    <p>Dear {{ $ticket->complaint->user->username }},</p>
    <p>Your complaint has been successfully filed. Here are the details of your ticket:</p>
    <ul>
        <li><strong>Complaint ID:</strong> {{ $ticket->complaint->id }}</li>
        <li><strong>Description:</strong> {{ $ticket->description }}</li>
        <li><strong>Status:</strong> {{ $ticket->status }}</li>
        <li><strong>Date Sent:</strong> {{ $ticket->date_sent }}</li>
    </ul>
    <p>Thank you for reaching out to us. We will get back to you shortly.</p>
</body>
</html>
