<?php
	require_once ("articlemodule/LibraryHeader.php");
	require_once ("articlemodule/searchArticles.php");
	include("phpmailer/class.phpmailer.php");

	$input = file_get_contents('php://input');
	$data = json_decode($input);
	$msg = 'Full Name: '.$data->name.'<br>Email Id: '.$data->email.'<br>Subject Name: '.$data->subject.'<br>Message: '.$data->message.'';

	//function sendMailViaSMTP($msgs)
	//{
	
		/*$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->CharSet="UTF-8";
		$mail->SMTPSecure = "ssl";
		$mail->Host = "mail.mailspot.info";
		$mail->Port = "465";
		$mail->Username = "no-replay@johs.com.sa";//$email;
		$mail->Password = 'johs_1234';
		$mail->SMTPAuth = true;
		$mail->From = 'same';
		$mail->FromName = 'ONLINE';
		$mail->AddAddress('dr-ayman-89@hotmail.com');
		$mail->AddReplyTo('dr-ayman-89@hotmail.com', 'Information');
		$mail->IsHTML(true);
		$mail->Subject = "JOHS";
		$mail->AltBody = $msgs;
		$mail->Body = $msgs;*/

		/*$hostName = "mail.mailspot.info";
		$portName = "465";
		$userName = "no-replay@johs.com.sa";//$email;
		$password = 'johs_1234';
		$fromName = 'ONLINE';
		$smtpAuth = 'true';
		if($orgemail=="")
			$orgemail="no-replay@johs.com.sa";*/
		/*$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->CharSet="UTF-8";
		$mail->SMTPSecure = "ssl";
		$mail->Port = "465";
		$mail->Host = $hostName;
		$mail->Username = $userName;
		$mail->Password = $password;
		$mail->SMTPAuth = $smtpAuth;
		$mail->From = $orgemail;
		$mail->FromName = $fromName;
		$mail->AddAddress("prakarsh@ubitechsolutions.com");
		$mail->AddReplyTo($orgemail,"Information");
		$mail->IsHTML(true);
		$mail->Subject = "JOHS";
		$mail->AltBody = $msg;
		$mail->Body    = $msg;*/

		/*$mail= new PHPMailer;
		$mail -> isSMTP();
		$mail->setFrom('no-replay@johs.com.sa',$fromName);
		$mail->AddAddress('prakarsh@ubitechsolutions.com');
		// $mail->AddCC('radhika@ubitechsolutions.com');
		$mail->Username =  $userName;
		$mail->Password = $password;
		$mail->Host = $hostName;
		$mail->Subject = "JOHS" ;
		$mail->Body = "hello message";
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = 465;
		$mail->isHTML(true);

		if(!$mail->Send()) 
		{ 
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: $fromName<no-replay@johs.com.sa>' . "\r\n"; 		
			echo 0;*/


		//include('admin/application/libs/PHPMailer.php');  
		//$hostName = "mail.mailspot.info";
		//$portName = 25;
		//$userName = "no-replay@johs.com.sa";//$email;
		//$password = 'johs_1234';
		//$fromName = "ONLINE";
		//$smtpAuth = true;
		//$to= $data->email;
		//$orgemail="no-replay@johs.com.sa";


		//$mail = new PHPMailer();
		//$mail->IsSMTP();
		//$mail->CharSet="UTF-8";
		//$mail->SMTPSecure = "ssl";
		//$mail->Host = "mail.mailspot.info";
		//$mail->Port = '465';
		//$mail->Username = "no-replay@johs.com.sa";
		//$mail->Password = "johs_1234";
		//$mail->SMTPAuth = 'true';
		//$mail->From = "no-replay@johs.com.sa";
		//$mail->FromName = "ONLINE";
		//$mail->AddAddress($data->email);
		//$mail->AddReplyTo("no-replay@johs.com.sa","Information");
		//$mail->IsHTML(true);
		//$mail->Subject = "JOHS";
		//$mail->AltBody = $msg;
		//$mail->Body    = $msg;	
		//if(!$mail->Send()) 
		//{ 
			//$headers = "MIME-Version: 1.0" . "\r\n";
			//$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			//$headers .= 'From:'. $fromName.' <no-replay@johs.com.sa>' . "\r\n"; 		
			//echo 0;
		//} 
		//else 
		//{ 
			//$headers = "MIME-Version: 1.0" . "\r\n";
			//$headers .= "Content-type:text/html;charset=UTF-8 \r\n";
			//$headers .= 'From:'. $fromName. '<no-replay@johs.com.sa> \r\n'; 
			//echo 1;
		//}
			
			
		
///////////-----mail to registered user
					//$username=Utils::getOrgName($orgid,$this->db);
					
					
					$subject="JOHS";
					//$orgemail=Utils::getOrgEmail($orgid,$this->db);
					//$sts=Utils::sendMail($useremail, $username, $subject, $message,$orgemail);
					//$to = 'jyoti@ubitechsolutions.com';
					$to = "dr-ayman-89@hotmail.com";
				$from="noreply@johs.com.sa";
				//$subject = $message['subject'];
				//$message = $message['message'];
				
				$header  = "From: noreply@johs.com.sa <noreply@johs.com.sa>" . "\r\n";
				//$header .= "CC: shivani@ubitechsolutions.com\r\n";
				$header .= 'MIME-Version: 1.0' . "\r\n";
				$header .= "Content-type: text/html; charset=iso-8859-1\r\n";
         
				 $retval = mail ($to,$subject,$msg,$header);
				 
				 if( $retval == true ) {
					 echo 1;
					
				 }else {
					echo 0;
				 }
			
	//}

	//print_r(json_encode(sendMailViaSMTP($msg)));

	?>