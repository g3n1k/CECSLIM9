<?php
/**
 * @author              : Waris Agung Widodo
 * @Date                : 04/04/18 12.27
 * @Last Modified by    : ido
 * @Last Modified time  : 04/04/18 12.27
 *
 * Copyright (C) 2017  Waris Agung Widodo (ido.alit@gmail.com)
 */

class ReCaptchaResponse {
    var $is_valid;
    var $error;
}

function recaptcha_check_answer($secretKey, $remoteip, $response) {

    $url = 'https://www.google.com/recaptcha/api/siteverify';

    $data = array(
        'secret' => $secretKey,
        'response' => $response,
        'remoteip' => $remoteip
    );
    $query = http_build_query($data);
    $options = array(
        'http' => array(
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
                        "Content-Length: ".strlen($query)."\r\n".
                        "User-Agent:MyAgent/1.0\r\n",
            'method' => 'POST',
            'content' => $query
        )
    );

    $context  = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captcha = json_decode($verify);

    $recaptcha_response = new ReCaptchaResponse();

    if ($captcha->success==false) {
        $recaptcha_response->is_valid = false;
        $keyError = "error-codes";
        $recaptcha_response->error = $captcha->$keyError;
    } else if ($captcha->success==true) {
        $recaptcha_response->is_valid = true;
    }

    return $recaptcha_response;
}

function recaptcha_get_html($siteKey) {
    return '<script src="https://www.google.com/recaptcha/api.js"></script>
    <div class="g-recaptcha" data-sitekey="'.$siteKey.'"></div>';
}