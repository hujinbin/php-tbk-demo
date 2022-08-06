<?php
namespace ACES\spi;

define("digestMethod", "sha1");
define("cipherMethod", "AES-128-CBC");
define("localIv", "0000000000000000");

class SpiTdeClient {
    /**
     * 加密
     * @param $string
     * @param $key
     * @return false|string
     */
    public function _encrypt($string, $key='')
    {
        // 对接java，服务商做的AES加密通过SHA1PRNG算法（只要password一样，每次生成的数组都是一样的），Java的加密源码翻译php如下：
        $key = substr(openssl_digest(openssl_digest($key, digestMethod, true), digestMethod, true), 0, 16);

        // openssl_encrypt 加密不同Mcrypt，对秘钥长度要求，超出16加密结果不变
        $data = openssl_encrypt($string, cipherMethod, $key, OPENSSL_CIPHER_AES_128_CBC, localIv);

        return base64_encode($data);
    }

    /**
     * 解密
     * @param string $string 需要解密的字符串
     * @param string $key 密钥
     * @return string
     */
    public function _decrypt($string, $key='')
    {
        // 对接java，服务商做的AES加密通过SHA1PRNG算法（只要password一样，每次生成的数组都是一样的），Java的加密源码翻译php如下：
        $key = substr(openssl_digest(openssl_digest($key, digestMethod, true), digestMethod, true), 0, 16);

        return openssl_decrypt(base64_decode($string), cipherMethod, $key, OPENSSL_CIPHER_AES_128_CBC, localIv);
    }
}
