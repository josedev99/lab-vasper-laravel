<?php

namespace App\Http\Services;

class Lucipher
{
    private static $pass = "hEJ99G4Luv+C2xwzF%Hq1)\5K1,=d~YX_NV},S0{h\_`D|D+QUvbc)?D!G$&d5.0*&o>y1P}vd";
    private static $method = "aes-256-cbc";

    public static function Cipher($datos)
    {
        $ivSize = openssl_cipher_iv_length(self::$method);
        $iv = openssl_random_pseudo_bytes($ivSize);
        $datosCifrados = openssl_encrypt($datos, self::$method, self::$pass, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $datosCifrados);
    }

    public static function Descipher($datos)
    {
        $ivSize = openssl_cipher_iv_length(self::$method);
        $datos = base64_decode($datos);
        $iv = substr($datos, 0, $ivSize);
        $datosCifrados = substr($datos, $ivSize);
        
        if (strlen($iv) !== $ivSize) {
            return false; 
        }

        return openssl_decrypt($datosCifrados, self::$method, self::$pass, OPENSSL_RAW_DATA, $iv);
    }
}
