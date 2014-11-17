<?php

include 'Crypt/RSA.php';
include 'Crypt/Rijndael.php';


function encrypt_message($plaintext,$asym_key,$key_length=150)
{
          
     $rsa = new Crypt_RSA();
     $rij = new Crypt_Rijndael();
 
    
    // Generate Random Symmetric Key
    $sym_key = crypt_random_string($key_length); 
    
    // Encrypt Message with new Symmetric Key                  
    $rij->setKey($sym_key);
    $ciphertext = $rij->encrypt($plaintext);
    $ciphertext = base64_encode($ciphertext); 
    
    // Encrypted the Symmetric Key with the Asymmetric Key            
    $rsa->loadKey($asym_key);
    $sym_key = $rsa->encrypt($sym_key);
    
    // Base 64 encode the symmetric key for transport
    $sym_key = base64_encode($sym_key);
    $len = strlen($sym_key); // Get the length
    
    $len = dechex($len); // The first 3 bytes of the message are the key length
    $len = str_pad($len,3,'0',STR_PAD_LEFT); // Zero pad to be sure.
    
    // Concatinate the length, the encrypted symmetric key, and the message
    $message = $len.$sym_key.$ciphertext;
     
                                                    

     return $message;
}


function decrypt_message($message,$asym_key)
{
     
    $rsa = new Crypt_RSA();
    $rij = new Crypt_Rijndael();

    // Extract the Symmetric Key  
    $len = substr($message,0,3); 
    $len = hexdec($len);                        
    $sym_key = substr($message,0,$len);
    
    //Extract the encrypted message
    $message = substr($message,3);
    $ciphertext = substr($message,$len);
    $ciphertext = base64_decode($ciphertext);
    
    // Decrypt the encrypted symmetric key 
    $rsa->loadKey($asym_key);
    $sym_key = base64_decode($sym_key);
    $sym_key = $rsa->decrypt($sym_key);
   
    // Decrypt the message
    $rij->setKey($sym_key);                       
    $plaintext = $rij->decrypt($ciphertext);
                                                    

     return $message;
}




?>