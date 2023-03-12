<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>       
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    </style>
</head>                 
<body style="margin: 0;padding:0;">
    <table width="100%" cellspacing="0" cellpadding="0" style="font-size: 16px; font-family: 'Poppins', sans-serif;background-color: #F6F6F6;background-image: url({{asset('emails/bg-top.png')}}), url({{asset('emails/bg-center.png')}}), url({{asset('emails/bg-bottom.png')}});background-repeat: no-repeat, no-repeat, no-repeat;background-position: top left, right center, bottom left;">
        <tr>
            <td style="padding:40px;"></td>
        </tr>
        <tr>
            <td>
                <table width="600px" cellspacing="0" cellpadding="0" style="margin:0 auto;table-layout:fixed;background:#ffffff;border-radius: 4px;box-shadow: 0 0 10px rgba(0,0,0,0.04);">
                    <tr>
                        <td>
                            <table width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="text-align:center;padding-top: 60px;">
                                        <div  class="logo-wrapper" style="max-width: 200px;margin: 0 auto;">
                                            <img data-yk="" style="max-width: 100%;" src="{{ $logo ?? asset('emails/email-logo.png') }}" alt="" />
                                            
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

