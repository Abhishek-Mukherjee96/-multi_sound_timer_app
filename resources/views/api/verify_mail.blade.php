<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{$data['title']}} | Multi-Sound Timer</title>

</head>




<body bgcolor="#e1e5e8" style="margin-top:0 ;margin-bottom:0 ;margin-right:0 ;margin-left:0 ;padding-top:0px;padding-bottom:0px;padding-right:0px;padding-left:0px;background-color:#e1e5e8;">
    <!--[if gte mso 9]>
<center>
<table width="600" cellpadding="0" cellspacing="0"><tr><td valign="top">
<![endif]-->
    <center style="width:100%;table-layout:fixed;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#e1e5e8;">
        <div style="max-width:600px;margin-top:0;margin-bottom:0;margin-right:auto;margin-left:auto;">
            <table align="center" cellpadding="0" style="border-spacing:0;font-family:'Muli',Arial,sans-serif;color:#333333;Margin:0 auto;width:100%;max-width:600px;">
                <tbody>
                    <tr>
                        <td align="center" class="vervelogoplaceholder" height="143" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;height:143px;vertical-align:middle;" valign="middle"></td>
                    </tr>
                    <!-- Start of Email Body-->
                    <tr>
                        <td class="one-column" style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;background-color:#ffffff;">
                            <!--[if gte mso 9]>
                    <center>
                    <table width="80%" cellpadding="20" cellspacing="30"><tr><td valign="top">
                    <![endif]-->
                            <table style="border-spacing:0;" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="center" class="inner" style="padding-top:15px;padding-bottom:15px;padding-right:30px;padding-left:30px;" valign="middle">
                                            <span class="sg-image"><img src="{{asset('public/images/MST_logo_large.png')}}" alt="" style="width: 150px;"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="inner contents center" style="padding-top:15px;padding-bottom:15px;padding-right:30px;padding-left:30px;text-align:left;">
                                            <center>
                                                <p class="h1 center" style="Margin:0;text-align:center;font-family:'flama-condensed','Arial Narrow',Arial;font-weight:100;font-size:30px;Margin-bottom:26px;">Verify Email</p>
                                                <!--[if (gte mso 9)|(IE)]><![endif]-->

                                                <p class="description center" style="font-family:'Muli','Arial Narrow',Arial;Margin:0;text-align:center;max-width:320px;color:#a1a8ad;line-height:24px;font-size:15px;Margin-bottom:10px;margin-left: auto; margin-right: auto;"><span style="color: rgb(161, 168, 173); font-family: Muli, &quot;Arial Narrow&quot;, Arial; font-size: 15px; text-align: center; background-color: rgb(255, 255, 255);">{{$data['body']}}</span></p>

                                                <p style="text-align: center; font-size: 18px; font-weight: bold;">Code: {{$data['unique_code']}}</p>

                                            </center>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--[if (gte mso 9)|(IE)]>
                    </td></tr></table>
                    </center>
                    <![endif]-->
                        </td>
                    </tr>
                    <!-- End of Email Body-->


                    <!-- whitespace -->
                    <tr>
                        <td height="25">
                            <p style="line-height: 25px; padding: 0 0 0 0; margin: 0 0 0 0;">&nbsp;</p>

                            <p>&nbsp;</p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="padding-top:0;padding-bottom:0;padding-right:30px;padding-left:30px;text-align:center;Margin-right:auto;Margin-left:auto;">
                            <center>




                                <p style="font-family:'Muli',Arial,sans-serif;Margin:0;text-align:center;Margin-right:auto;Margin-left:auto;padding-top:10px;padding-bottom:0px;font-size:15px;color:#a1a8ad;line-height:23px;">© <?php echo date('Y'); ?> Multi-Sound Timer | All Rights Reserved</p>
                            </center>
                        </td>
                    </tr>
                    <!-- whitespace -->
                    <tr>
                        <td height="40">
                            <p style="line-height: 40px; padding: 0 0 0 0; margin: 0 0 0 0;">&nbsp;</p>

                            <p>&nbsp;</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </center>
    <!--[if gte mso 9]>
</td></tr></table>
</center>
<![endif]-->


</body>



</html>