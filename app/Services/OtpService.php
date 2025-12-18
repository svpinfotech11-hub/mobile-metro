<?php 
namespace app\Services;

class OtpService
{
    public function SendOtp($number, $otp){
            $tamp_id = 1107172845189433045;
            $enmsg = "Hi, Your Verification " . $otp . " code ,NOKA";
            $msg2 = urlencode($enmsg);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://sms.cell24x7.in/mspProducerM/sendSMS?user=noka&pwd=123456789&sender=NOKAFW&mobile=$number&msg=$msg2&mt=0&tempId=$tamp_id");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $response = curl_exec($ch);
            curl_close($ch);
            if (curl_errno($ch)) {
                $error_msg = 'cURL error: ' . curl_error($ch);
                curl_close($ch);
                return $error_msg;
            } else {
                curl_close($ch);
                return 'true';
            }
    }
}

?>