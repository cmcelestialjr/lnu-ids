<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <img src="https://drive.google.com/uc?export=view&id=1hkre2DzvdkEQ__RYNfcTXCznTP1NEvtK"
        alt="LNU"
        style="height: 70px; width: 80px;"/>
    <br>
    Dear {{$name}},
    <br><br>
    Your request for new password has been processed. Please see details below:
    <br><br>
    <table style="border: 1px solid black;width: 400px;border-collapse: collapse; text-align: center;">
        <tr>
            <td style="width:200px; border:1px solid black;padding: 5px;">Date Submitted</td>
            <td style="width:200px; border:1px solid black;padding: 5px;">{{$dateTime}}</td>
        </tr>
        <tr>
            <td style="width:200px; border:1px solid black;padding: 5px;">Reference No.</td>
            <td style="width:200px; border:1px solid black;padding: 5px;">{{$reference_no}}</td>
        </tr>
        <tr>
            <td style="width:200px; border:1px solid black;padding: 5px;">Temporary Login Password</td>
            <td style="width:200px; border:1px solid black;padding: 5px;">{{$temporary_password}}</td>
        </tr>
    </table>
    <p style="font-size: 12px;">
    <br>
    Thank you.
    <br>
    This is a system generated message.
    <br><br>
    LNU-Integrated Data System (IDS)<br>
    From: <b>IT Support Office</b></p>
</body>
</html>
