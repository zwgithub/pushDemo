/*
    1.使用时需要知道deviceToken
    2.把证书改为相应的名字，例如测试证书改名为apns-dev.pem，把证书和此文件放在一个文件夹下
    3.执行时用   php apns.php
*/


<?php
    $deviceToken = 'xxxxxxxx';//5s   zhenwei
    $message = '到底收到没？';
    $badge = 'badge';
    $mode = 'development';//这里可以更改是测试还是线上
  
    $body = array("aps" => array("alert" => $message, "badge" => (int)$badge, "sound"=>'default'), "c"=>'3|735|www.baidu.com|1149710');//c为自定义的参数
    
	$ctx = stream_context_create();
	$fp;
	if($mode == "development")
	{
		echo "testttttttttttttttestttttttttttttt<br>";
        stream_context_set_option($ctx, "ssl", "local_cert", "apns-dev.pem");
		$fp = stream_socket_client("ssl://gateway.sandbox.push.apple.com:2195", $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
        echo "1111111111111111111111111111<br>";
	}
	else
	{
		stream_context_set_option($ctx, "ssl", "local_cert", "apns-pro.pem");
		$fp = stream_socket_client("ssl://gateway.push.apple.com:2195", $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
	}
	
	stream_context_set_option($ctx, "ssl", "passphrase", "123123");
	if (!$fp) {
		echo "Failed to connect $err $errstrn";
		return;
	}
	
	if($mode == "development")
	{
		echo "Connection OK ==> apns_dev.pem ==> ssl://gateway.sandbox.push.apple.com:2195<br>";
	}
	else
	{
		echo "Connection OK ==> apns_pro.pem ==> ssl://gateway.push.apple.com:2195<br>";
	}
	
	$payload = json_encode($body);
	$msg = chr(0) . pack("n",32) . pack("H*", str_replace(' ', '', $deviceToken)) . pack("n",strlen($payload)) . $payload;
	echo "---------------------------------<br>";
	
	echo "Sending token:".$deviceToken.'<br>';
	echo "Sending message :" . $payload . "<br>";
	echo "---------------------------------<br>";
	fwrite($fp, $msg);
	fclose($fp);
?>

