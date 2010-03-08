<?php
    $request = json_decode(urldecode($_POST['request']), TRUE);
    if(is_array($request)) {
        if(strtolower($request['wsusername']) == 'demo' && $request['wspassword'] == '123456'){
            $user = array(
                'id' => 50,
                'lastlogin' => '1265815958806',
                'name' => 'Imanol',
                'lastname' => 'Urra Ruiz',
                'email0' => 'index02@gmail.com',
                'avatar' => 'http://terrassatsc.upc.edu/user/pix.php/1809/f1.jpg'
            );
        } else {
            $user = array(
                'msg' => 'Invalid user or pass'
            );
        }
        
        $json = $_POST["callback"] . "(" . json_encode($user) . ")"; //JSONP
        echo $json;
    } else {
        $user = array(
                'msg' => 'Algo pasa en Moodbile :('
        );
        
        $json = $_POST["callback"] . "(" . json_encode($user) . ")"; //JSONP
        echo $json;
    }
?>