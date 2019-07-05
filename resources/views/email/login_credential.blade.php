<!DOCTYPE html>
<html>
<link href="https://fonts.googleapis.com/css?family=Montserrat:600,700" rel="stylesheet">
<table style="font-family:'Montserrat','Open Sans'; font-weight:600;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
  <tbody>
    <tr>
      <td style="padding: 20px 30px 50px 30px;" valign="top" bgcolor="#eeeeee" width="100%">
        <table class="deviceWidth" style="margin: 0 auto;" border="0" width="580" cellspacing="0" cellpadding="0" align="center" bgcolor="#eeeeed">
          <tbody>
            <tr>
              <td style="padding: 0;" valign="top" bgcolor="#ffffff" align="center"><img style="display: block; border-radius: 4px; padding-top: 10px;" src="https://go.curlec.com/merchant-images/happyrent.png" alt="" height="100" border="0"></td>
            </tr>
            <!--
<tr>
<td style="padding: 0px 20px 0px 30px; font-size: 14px; text-decoration:none; color:black;" valign="middle" align="center" bgcolor="#ffffff"><b>www.curlec.com</td>
</tr>
<tr>
<td style="padding: 0px 20px 0px 30px; font-size: 9px; text-decoration:none; color:black;" valign="middle" align="center" bgcolor="#ffffff">+603 7629 5701  .  info@curlec.com</td>
</tr>
-->
            <tr>
              <td style="padding: 5px 20px 5px 30px;" bgcolor="#ffffff">
                <hr size="3" color="#5e3284">
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 20px 10px 30px;" valign="top" bgcolor="#ffffff">

                <p><span style="font-size: 14px;">Dear Customer,</span></p>
                <p><span style="font-size: 14px;">Thank you for using registering Happy Rent.</span></p>
                <p><span style="font-size: 14px;">The following is your credential.</span></p>

                  <p>
                    <span style="font-size: 14px;">
                      URL: {{env('APP_URL')}}
                    </span>
                  </p>
                  <p>
                    <span style="font-size: 14px;">
                      Login:
                      @if($login_type == 1)
                        {{$user->email}}
                      @else
                        {{$user->phone_number}}
                      @endif
                    </span>
                  </p>
                  <p>
                    <span style="font-size: 14px;">
                      Password: {{$temporary_password}}
                    </span>
                  </p>

                <p><span style="font-size: 14px;">Thank you.</span></p>
                <p><span style="font-size: 14px;">&nbsp;</span></p>
                <p><span style="font-size: 14px;">Kind Regards,</span></p>
                <p><span style="font-size: 14px;">The Happy Rent Team<br></span></p>
                <p><span style="font-size: 14px;">Supported by RHB Bank<br></span></p>
                <p><span style="font-size: 14px;">Insured by Allianz Insurance<br></span></p>
                <p>&nbsp;</p>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 20px 10px 30px;" valign="top" bgcolor="#ffffff">
                <p style="text-align: justify; font-size: 10px;">DISCLAIMER: This email is intended for use of the
                  addressee only and may contain privileged and confidential information. If you are not the intended
                  recipient or have received this communication in error, please delete it from your system
                  immediately. You are hereby notified that any unauthorized use or dissemination of this communication
                  is strictly prohibited and may be unlawful. We are neither liable for the proper and complete
                  transmission of the information contained in this communication nor for any delay in its receipt.</p>
              </td>
            </tr>
            <tr>
              <td style="padding: 10px 20px 10px 30px;" bgcolor="#ffffff">
                <hr size="3" color="#5e3284">
              </td>
            </tr>
            <tr>
              <td style="line-height:1.5; font-size: 12px;" align="center" bgcolor="#ffffff">{{$user->profile->name}}<br>
                Tel: <font color="#5e3284">+6019 - 996 0707 . </font>
                <a href="https://www.happyrent.com.my" style="color:#5e3284;" target="_blank">https://www.happyrent.com.my</a></td>
            </tr>
            <tr>
              <td style="padding: 5px 20px 5px 30px;" bgcolor="#ffffff">
                <p align="right"><span style="font-size: 14px;"><br><br><a href="https://www.happyrent.com.my" style="color:#5e3284;" target="_blank"><img class="img2" style="display:block;margin-left:auto;margin-right:auto;width:50%;" src="http://drive.google.com/uc?export=view&amp;id=1HOVGlhc6VfLzVVlPx-f-cVXFq9ASQ4pM"></a></span></p>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
</html>