<?php
    function my_simple_crypt($string, $action = 'e') {
        // you may change these values to your own
        $secret_key = '&d&Z8wEoQrlhahhhOZB%5y4FXV';
        $secret_iv = '$4dYwCwc7AIdMYViQxQYE!eF*75';
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    function encrypt($string) {
        $salting = substr(md5(microtime()),-1) . $string;
        return my_simple_crypt($salting, 'e');
    }

    function decrypt($string) {
        $encode = my_simple_crypt($string, 'd');
        return substr($encode, 1);
    }
?>
