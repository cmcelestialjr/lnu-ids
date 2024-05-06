<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LNU - IDS</title>
    <link rel="icon" href="{{ asset('assets/images/logo/lnu_logo.png') }}" type="image/gif" nonce="{{ csp_nonce() }}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/fontawesome-free/css/all.min.css') }}" nonce="{{ csp_nonce() }}">
    <!-- Select2 Bootsrap style -->
    <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" nonce="{{ csp_nonce() }}">
    <!-- ICheck style -->
    <link rel="stylesheet" href="{{ asset('_adminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" nonce="{{ csp_nonce() }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('_adminLTE/dist/css/adminlte.min.css') }}" nonce="{{ csp_nonce() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/error/http.css') }}" nonce="{{ csp_nonce() }}">
</head>
<body>
    <section class="content">
        <img src="{{ asset('assets/images/logo/lnu_logo_header_blue.png') }}">
        <p>HTTP: <span>404</span></p>
        <code><i class="fas fa-exclamation-triangle text-warning"></i> <span>Oops! </span>
            Your<em> connection</em> is <b>not private</b>.</code>
        <code><span>We</span> <b>recommend</b> <span>you to click</span> the <b>LINK</b> below.</code>
        <code class="center"><a href="{{ str_replace('http','https',url('/')) }}">{{ str_replace("http","https",url("/")) }}</a></code>
        <code><b>Hypertext Transfer Protocol Secure</b><span> is an extension of the Hypertext Transfer Protocol.</span></code>
        <code><span>It is used for secure communication over a computer network,</span> 
            <span>and is widely used on the Internet.</span> </code>
        <code><b>In HTTPS,</b> <span>the communication protocol is</span> <span>encrypted using</span> 
            <b>Transport Layer Security</b> <span>or, formerly, Secure Sockets Layer.</span> </code>
    </section>
</body>
<script type="text/javascript" src="{{ asset('assets/js/error/http.js') }}" nonce="{{ csp_nonce() }}"></script>
</html>
