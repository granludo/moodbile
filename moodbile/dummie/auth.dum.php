<?php
    $request = json_decode($_POST['request'], TRUE);
    if($request['wsusername'] && $request['wspassword']) {
        if(strtolower($request['wsusername']) == 'demo' && $request['wspassword'] == 123456){
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
    }