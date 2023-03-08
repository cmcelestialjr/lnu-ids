<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LNU - IDS</title>
    <link rel="icon" href="{{ asset('assets/images/logo/lnu_logo.png') }}" type="image/gif">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Select2 Bootsrap style -->
    <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- ICheck style -->
    <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('_adminLTE/dist/css/adminlte.min.css') }}">

</head>
<style>
* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}

body {
    background-color: #4682B4;
	background: radial-gradient(ellipse at center, #4682B4 0%, #6082B6 100%) fixed no-repeat;
    overflow: hidden;
}

p {
    font-family: "Arial";
    font-size: 100px;
    margin: 5vh 0 0;
    text-align: center;
    letter-spacing: 5px;
    background-color: black;
    color: transparent;
    text-shadow: 2px 2px 3px rgba(255, 255, 255, 0.1);
    -webkit-background-clip: text;
    -moz-background-clip: text;
    background-clip: text;

    span {
        font-size: 1.2em;
    }
}

code {
    font-family: "Arial";
    color: #bdbdbd;
    text-align: center;
    display: block;
    font-size: 18px;
    margin: 0 5px 5px;

    span {
        color: #f0c674;
    }

    i {
        color: #b5bd68;
    }

    em {
        color: #b294bb;
        font-style: unset;
    }

    b {
        color: #81a2be;
        font-weight: 500;
    }
}


a {
    color: #0047AB;
    font-family: monospace;
    font-size: 24px;
    text-decoration: underline;
    margin-bottom:10px;
    display:inline-block
}
img{
    margin-top:50px;
    margin-left:100px;
    height:120px;
    width:450px;
}
@media screen and (max-width: 880px) {
    p {
        font-size: 14vw;
    }
    code {
        font-size: 14px;
    }
}
</style>
<body>
    <section class="content">
        <img src="{{ asset('assets/images/logo/lnu_logo_header_blue.png') }}">
        <p>HTTP: <span>404</span></p>
        <code><i class="fas fa-exclamation-triangle text-warning"></i> <span>Oops! </span>Your<em> connection</em> is <b>not private</b>.</code>
        <code><span>We</span> <b>recommend</b> <span>you to click</span> the <b>LINK</b> below.</code>
        <code><center><a href="{{ str_replace('http','https',url('/')) }}">{{ str_replace("http","https",url('/')) }}</a></center></code>
        <code><b>Hypertext Transfer Protocol Secure</b><span> is an extension of the Hypertext Transfer Protocol.</span></code>
        <code><span>It is used for secure communication over a computer network,</span> <span>and is widely used on the Internet.</span> </code>
        <code><b>In HTTPS,</b> <span>the communication protocol is</span> <span>encrypted using</span> <b>Transport Layer Security</b> <span>or, formerly, Secure Sockets Layer.</span> </code>
    </section>
</body>
<script type="text/javascript">
function type(n, t) {
    var str = document.getElementsByTagName("code")[n].innerHTML.toString();
    var i = 0;
    document.getElementsByTagName("code")[n].innerHTML = "";

    setTimeout(function() {
        var se = setInterval(function() {
            i++;
            document.getElementsByTagName("code")[n].innerHTML =
                str.slice(0, i) + "|";
            if (i == str.length) {
                clearInterval(se);
                document.getElementsByTagName("code")[n].innerHTML = str;
            }
        }, 10);
    }, t);
}

type(0, 0);
type(1, 200);
type(2, 400);
type(3, 400);
type(4, 600);
type(5, 400);
</script>
</html>
