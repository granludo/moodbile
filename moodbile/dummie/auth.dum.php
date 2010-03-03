<?php
    if($_GET['wsusername'] && $_GET['wspassword']) {
        if(strtolower($_GET['wsusername']) == 'demo' && $_GET['wspassword'] == md5('123456')){
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
        
        $json = $_GET["callback"] . "(" . json_encode($user) . ")"; //JSONP
        echo $json;
    }