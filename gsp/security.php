<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class security{
    function decrypt_response($encrypted_data, $NICAppKey, $sek){
		$ek = $this->decryptData($sek, $NICAppKey);
		return $enc = $this->decrypt($encrypted_data, $ek);
	}
	function eInvEncryption($json_data){
        //read eway pem file
		$keyPath = dirname(__FILE__) . "/PublicKey/einv_sandbox_key.pem";
        $fp = fopen($keyPath, "r");
        $pub_key = fread($fp, 8192);
        fclose($fp);
        //encrypt app key with eway public key
        openssl_public_encrypt(base64_encode($json_data), $crypttext, $pub_key);
        $response['encrypted'] = base64_encode($crypttext);
        return $response;
    }
	function EinvApiAuthenticate($EinvUsername,$EinvPassword,$appKey) {
        $fields['UserName'] = $EinvUsername;
        $fields['Password'] = $EinvPassword;
        $fields['AppKey'] = $appKey;
        $fields['ForceRefreshAccessToken'] = false;
        $data = json_encode($fields);
        $encryptedResponse= $this->eInvEncryption($data);
        $responseArray["Data"] = $encryptedResponse["encrypted"];
		
		$response = json_encode($responseArray);
        return $response;
    }
	
	function generateIRN($json_data, $NICAppKey, $sek) {
		//get $ek
        $ek = $this->decryptData($sek, $NICAppKey);
		$enc = $this->encrypt($json_data, $ek);
		$fields['Data'] = $enc; //base64 encoded data
		$data = json_encode($fields);		
		return $data;
	}
	
	function decryptData($data, $appkey) {
        $value = $data;
        $key = base64_decode($appkey);
        return $this->decrypt($value, $key);
    }

	function encrypt($input, $key) {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = $this->pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }

    function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    function decrypt($sStr, $sKey) {
        $decrypted = mcrypt_decrypt(
                MCRYPT_RIJNDAEL_128, $sKey, base64_decode($sStr), MCRYPT_MODE_ECB
        );
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s - 1]);
        $decrypted = substr($decrypted, 0, -$padding);
        return $decrypted;
    }
}

?>
