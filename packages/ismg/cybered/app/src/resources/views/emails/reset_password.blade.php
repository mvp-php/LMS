
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberEd - Admin</title>
    <style>
        @font-face {
            font-family: "Poppins-Regular";
            src: url('../../assets/fonts/Poppins-Regular.ttf');

        }

        @font-face {
            font-family: "Poppins-SemiBold";
            src: url('../../assets/fonts/Poppins-SemiBold.ttf');
        }
    </style>

</head>
@php 
    $mainimage = url('assets/img/back-image1.png');
    $mail_logo = url('assets/img/mail-logo.png');
    $mail_logo = url('assets/img/mail-logo.png');
    $linkdin_log = url('assets/img/linked-in.png');
    $twitter_logo = url('assets/img/twitter.png');
    $facebook_logo = url('assets/img/facebook.png');
    $email_logo = url('assets/img/email.png');
@endphp
<body>
    <table style="max-width: 800px;margin:auto;width:100%;">
        <tr>
            <td>
                <table style="max-width: 600px;margin:auto;width:100%;border-collapse: collapse;margin-top:50px;">
                    <tr>
                        <td 
                            style="background-image:url('{{ $mainimage }}');width:600px;height:100px;text-align: center;vertical-align: middle;">
                            <div>
                            
                                <img src="{{ $mail_logo}}" alt="">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#F5F5F5;padding:30px;">
                            <div>
                                <h6
                                    style="font-family:'poppins-semibold';font-size:18px;margin:0;margin-bottom:20px;color: #3A3B3C;">
                                   Hi {{ $details['username']}}!</h6>
                                <p
                                    style="font-family:'Poppins-Regular';font-size: 14px;margin:0;margin-bottom:20px;line-height:22px;color: #3A3B3C;">
                                    Your password has successfully been changed.
                          
                                </p>
                                
                                <p
                                    style="font-family:'Poppins-Regular';font-size: 14px;margin:0;margin-bottom:20px;line-height:22px;color: #3A3B3C;">
                                    If you performed this password reset, no action is required. If you did not initiate this password reset, please contact support support url immediately to ensure the security of your account.


                                
                                </p>

                                <p
                                    style="font-family:'Poppins-Regular';font-size: 14px;margin:0;margin-bottom:20px;line-height:22px;color: #3A3B3C;">
                                    If you’re having difficulties with the above link, you may copy and paste the URL below into your web browser.
                                    action url

                                </p>
                                <p
                                    style="font-family:'Poppins-Regular';font-size: 14px;margin:0;margin-bottom:20px;line-height:22px;color: #3A3B3C;">
                                    Thanks,<br>
                                    CyberEd team

                                </p>
                                
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;padding-top:20px ;padding-bottom:30px;">
                            <div>
                                <a href="" style="cursor: pointer;"><img src="{{ $linkdin_log }}" alt=""
                                        style="margin-right:10.5px;"></a>

                                <a href="" style="cursor: pointer;"><img src="{{ $twitter_logo}}" alt=""
                                        style="margin-right:10.5px;"></a>
                                <a href="" style="cursor: pointer;"><img src="{{ $facebook_logo }}" alt=""
                                        style="margin-right:10.5px;"></a>
                                <a href="" style="cursor: pointer;"><img src="{{ $email_logo}}" alt=""
                                       ></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            style="text-align:center;padding-bottom:50px;font-family:'Poppins-Regular';font-size: 10px;">
                            Copyright (C) {{ date('Y')}} Information Security Media Group, Corp.
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>

</html>