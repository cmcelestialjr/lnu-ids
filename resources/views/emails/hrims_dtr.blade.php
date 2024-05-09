<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>HRIMS DTR Email</title>
</head>
<body>
    <img src="https://drive.google.com/uc?export=view&id=1hkre2DzvdkEQ__RYNfcTXCznTP1NEvtK"
        alt="LNU"
        style="height: 70px; width: 80px;"/>
    <br>
    Good day!
    <br><br>
    Your Daily Time Record for {{date('F d, Y', strtotime($date))}}
    <br><br>
    <table style="border: 1px solid black;width: 400px;border-collapse: collapse; text-align: center;">
        <thead>
            <th style="width:200px; border:1px solid black;">Time</th>
            <th style="width:200px; border:1px solid black;">Type</th>
        </thead>
        @foreach($logs as $log)
            <tr>
                <td style="border:1px solid black;">{{date('h:i a',strtotime($log->dateTime))}}</td>
                <td style="border:1px solid black;">@if($log->type==0)In @else Out @endif</td>
            </tr>
        @endforeach
    </table>
    <p style="font-size: 12px;">
    <br>
    This is a system generated message.
    <br><br>
    LNU-Integrated Data System (IDS)<br>
    Human Resource Information Management System (HRIMS)<br>
    From: <b>Human Resource Management Office</b></p>
</body>
</html>
