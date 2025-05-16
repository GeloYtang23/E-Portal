<?php
function encryptMessage($plaintext, $key) {
    $cipher = "AES-256-CBC";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $encrypted = openssl_encrypt($plaintext, $cipher, $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptMessage($encrypted, $key) {
    $cipher = "AES-256-CBC";
    $data = base64_decode($encrypted);
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = substr($data, 0, $ivlen);
    $ciphertext = substr($data, $ivlen);
    return openssl_decrypt($ciphertext, $cipher, $key, 0, $iv);
}
?>
