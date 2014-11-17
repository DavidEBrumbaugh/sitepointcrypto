<?php

include 'Crypt/RSA.php';
include 'Crypt/Rijndael.php';


$rsa = new Crypt_RSA();
$keys = $rsa->createKey(2048);

?>
<pre>
<?php echo $keys['privatekey']; 
file_put_contents('key.pri',$keys['privatekey']);
?>
<hr />
<?php 
echo $keys['publickey'];
file_put_contents('key.pub',$keys['publickey']);
 ?>
</pre>

