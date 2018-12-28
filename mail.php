<?php

echo 'entrer dans le php';
echo  $_SERVER['PHP_SELF'];

$return = '{"success":false}';

//if ($_SERVER['REQUEST_METHOD'] == 'GET') { //this file being called using xhr over crossdomain origin, it's called two times: first using the OPTION method and second the real GET method and so only the second call should actually do something (and in this case send the email)
    if ( isset($_REQUEST['name']) && isset($_REQUEST['mail']) && isset($_REQUEST['message'])) {
        //Secure form posted values
        $from_fname = secure_value( $_REQUEST['name'] );
        $from_mail = secure_value( $_REQUEST['mail'] );
        $mail_msg = secure_value( $_REQUEST['message'] );
        
        //form validation
        if ($from_fname != '' && $from_mail != '' && preg_match('/[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/iu', $from_mail) && $mail_msg != '') {  //http://www.regular-expressions.info/email.html
        
            //send mail (warning page reload send the mail again !)
            $to = 'clement8.baille@gmail.com';
                
            $msg_title = "Site presentation - Message de " . $from_fname;
            
            $msg_content = "
                <p>
                    Message envoyé par " . $from_fname . " <" . $from_mail . "><br/>
                    Contenu : <br/>
                    " .
                    $mail_msg
                    . "
                </p>
            ";
            if( send_mail($to, $from_fname, $from_mail, $msg_title, $msg_content) ) {
                $return = json_encode( array('success' => true) );

            } else {
                $return = json_encode( array('success' => false, 'msg' => "Mail refused for delivery") );
            }
        }    else {
            $return = json_encode( array('success' => false, 'msg' => "Badly filled form - invalid data") );
        }
    }    else {
            $return = json_encode( array('success' => false, 'msg' => "Badly filled form - missing data") );
    }
//}
exit($return);



function send_mail($to, $from_fullname, $from_email, $msg_title, $msg_content) {
    $message = "
    <html>
    <head>
        <title>" . $msg_title . "</title>
    </head>
    <body>
        " . $msg_content . "
    </body>
    </html>
    ";
    
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
    
    // More headers
    $headers .= 'From: ' . $from_fullname . ' <'. $from_email  .'>' . "\r\n";
    //$headers .= 'Cc: myboss@example.com' . "\r\n";
    
    //send mail
    //var_dump($message);var_dump($to);var_dump($msg_title);var_dump($headers);
    $res = @mail( trim($to,"'"), $msg_title, $message, $headers ); //var_dump($res);
    
    return $res;
}

function secure_value($value) {
    return stripslashes( htmlspecialchars($value) );
}
