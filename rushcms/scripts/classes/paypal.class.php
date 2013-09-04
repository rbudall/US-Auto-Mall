<?php
/*
  paypalewp.php

  $Id$

  The PayPal class implements the dynamic encryption of PayPal "buy now"
  buttons using the PHP openssl functions. (This evades the ISP restriction
  on executing the external "openssl" utility.)

  Author: Ivor Durham (ivor.durham@ivor.cc)
  Version: 1.0

  Example usage:

  $paypal = &new PayPal();

  $paypal->setTempFileDirectory('/tmp');
  $paypal->setCertificate('mycompany_cert.pem', 'mycompany_key.pem');
  $paypal->setCertificateID('ABCDEFGHIJKL');
  $paypal->setPayPalCertificate('paypal_cert_sandbox.pem');

  $parameters = array("cmd" => "_xclick",
		      "business" => "sales@mycompany.com",
		      "item_name" => "Cat Litter #40",
		      "amount" => "12.95",
		      "no_shipping" => "1",
		      "return" => "http://mycompany.com/paypal_ok.php",
		      "cancel_return" => "http://mycompany.com/paypal_cancel.php",
		      "no_note" => "1",
		      "currency_code" => "USD",
		      "bn" => "PP-BuyNowBF"
  );

  $encryptedButton = $paypal->encryptButton($parameters);

  echo <<<END_HTML
  <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_s-xclick">
  <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/x-click-but23.gif" border="0" name="submit" alt="Make payments with PayPal - it\'s fast, free and secure!">
  <input type="hidden" name="encrypted" value="
  -----BEGIN PKCS7-----
  {$encryptedButton}
  -----END PKCS7-----
  ">
  </form>

  END_HTML;
 */

class PayPal {
  var $certificate;	// Certificate resource
    var $certificateFile;	// Path to the certificate file

    var $privateKey;	// Private key resource (matching certificate)
    var $privateKeyFile;	// Path to the private key file

    var $paypalCertificate;	// PayPal public certificate resource
    var $paypalCertificateFile;	// Path to PayPal public certificate file
    var $certificateID; // ID assigned by PayPal to the $certificate.

    var $tempFileDirectory;
    
    //setCertificateID: Sets the ID assigned by PayPal to the client certificate
    //$id - The certificate ID assigned when the certificate was uploaded to PayPal

    function setCertificateID($id) {
        $this->certificateID = $id;
    }
  
    //setCertificate: set the client certificate and private key pair.
    //$certificateFilename - The path to the client certificate

    //$keyFilename - The path to the private key corresponding to the certificate
    //Returns: TRUE if the private key matches the certificate.

    function setCertificate($certificateFilename, $privateKeyFilename) {
        $result = false;

        if (is_readable($certificateFilename) && is_readable($privateKeyFilename))
        {
            $certificate=null;
            $handle=fopen($certificateFilename, "r");
            $size=filesize($certificateFilename);
            $certificate=fread($handle,$size);
            fclose($handle);

            $privateKey=null;              
            $handle=fopen($privateKeyFilename,"r");
            $size=filesize($privateKeyFilename);
            $privateKey=fread($handle, $size);
            fclose($handle);
        
      	    if (($certificate !== false) && ($privateKey !== false) && openssl_x509_check_private_key($certificate, $privateKey)) {
			    $this->certificate = $certificate;
			    $this->certificateFile = $certificateFilename;
			    $this->privateKey = $privateKey;
			    $this->privateKeyFile = $privateKeyFilename;
			    $result = true;
      	    }
        }

        return $result;
    }

    //setPayPalCertificate: Sets the PayPal certificate
    //$fileName - The path to the PayPal certificate.
    //Returns: TRUE iff the certificate is read successfully, FALSE otherwise.

    function setPayPalCertificate($fileName) {
        if (is_readable($fileName)) {
            $handle=null;
            $certificate=null;
            $size=null;
            
            $handle=fopen($fileName, "r");
            if (!$handle){
                echo 'Paypal cert could not be opened';
            }
            $size=filesize($fileName);
        
            $certificate=fread($handle, $size);
            if (!$certificate){
                echo 'Paypal cert could not be read';
            }
            fclose($handle);

    	    if ($certificate !== false) {
			    $this->paypalCertificate = $certificate;
			    $this->paypalCertificateFile = $fileName;
			    return true;
    	    }
        }
        return false;
    }

    //setTempFileDirectory: Sets the directory into which temporary files are written.
    //$directory - Directory in which to write temporary files.
    //Returns: TRUE iff directory is usable.

    function setTempFileDirectory($directory) {
  	    if (is_dir($directory) && is_writable($directory)) {
    	    $this->tempFileDirectory = $directory;
      	    return true;
        } else {
      	    return false;
          }
      }

    //encryptButton: Using the previously set certificates and tempFileDirectory
    //encrypt the button information.

    //$parameters - Array with parameter names as keys.
    //Returns: The encrypted string for the _s_xclick button form field.

    function encryptButton($parameters) {
        // Check encryption data is available.
		
		$TempFileDirectory = $_SERVER['DOCUMENT_ROOT'].'/tmp';                         					//path to temp file
		$myPublicCertificate = $_SERVER['DOCUMENT_ROOT'].'/scripts/encryption/my-pubcert.pem';       	//path to your public certificate
		$myPrivateKey = $_SERVER['DOCUMENT_ROOT'].'/scripts/encryption/my-prvkey.pem';                	//path to your private key
		$CertificateID = 'FUQ9JCYS8NWN4';                   											//certificate id generated by PayPal when you uploaded your public certificate to your PayPal account
		$PayPalPublicCertificate = $_SERVER['DOCUMENT_ROOT'].'/scripts/encryption/paypal_cert_pem.txt'; //PayPal public certificate
				
		$this->setTempFileDirectory($TempFileDirectory);
		$this->setCertificate($myPublicCertificate, $myPrivateKey);
		$this->setCertificateID($CertificateID);
		$this->setPayPalCertificate($PayPalPublicCertificate);

        if (($this->certificateID == '') || !isset($this->certificate) || !isset($this->paypalCertificate)) {
    	    return false;
        }

        $clearText = '';
        $encryptedText = '';

        // initialize data.
        $data = "cert_id=" . $this->certificateID . "\n";;
        foreach($parameters as $k => $v) 
            $d[] = "$k=$v";
            $data .= join("\n", $d);

        $dataFile = tempnam($this->tempFileDirectory, 'data');
        
        $out = fopen("{$dataFile}_data.txt", 'wb');
        fwrite($out, $data);
        fclose($out);
        
        $out=fopen("{$dataFile}_signed.txt", "w+"); 

        if (!openssl_pkcs7_sign("{$dataFile}_data.txt", "{$dataFile}_signed.txt", $this->certificate, $this->privateKey, array(), PKCS7_BINARY)) {
    	    return false;
        }
        fclose($out);

        $signedData = explode("\n\n", file_get_contents("{$dataFile}_signed.txt"));

        $out = fopen("{$dataFile}_signed.txt", 'wb');
        fwrite($out, base64_decode($signedData[1]));
        fclose($out);

        if (!openssl_pkcs7_encrypt("{$dataFile}_signed.txt", "{$dataFile}_encrypted.txt", $this->paypalCertificate, array(), PKCS7_BINARY)) {
    	    return false;
        }

        $encryptedData = explode("\n\n", file_get_contents("{$dataFile}_encrypted.txt"));

        $encryptedText = $encryptedData[1];

        @unlink($dataFile);  
        @unlink("{$dataFile}_data.txt");
        @unlink("{$dataFile}_signed.txt");
        @unlink("{$dataFile}_encrypted.txt");

        return $encryptedText;
    }
}
?>
