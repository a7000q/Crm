<?php 
namespace app\models;

use Yii;
use yii\Helpers\ArrayHelper;
use yii\base\Model;
use linslin\yii2\curl;

define("SMSC_LOGIN", "Licada");         // логин клиента
define("SMSC_PASSWORD", "51db938c073b9b395296e542d8359f68");    // пароль или MD5-хеш пароля в нижнем регистре
define("SMSC_POST", 0);                 // использовать метод POST
define("SMSC_HTTPS", 0);                // использовать HTTPS протокол
define("SMSC_CHARSET", "utf-8");    // кодировка сообщения: utf-8, koi8-r или windows-1251 (по умолчанию)
define("SMSC_DEBUG", 0);   

class SmsCenter extends Model
{
                 // флаг отладки

    public function send($phone, $msg)
    {
        $curl = new curl\Curl();

        //post http://example.com/
        $response = $curl->setOption(
            CURLOPT_POSTFIELDS, 
            http_build_query(array(
                'login' => SMSC_LOGIN,
                'psw' => SMSC_PASSWORD,
                'phones' => $phone,
                'mes' => $msg,
                'charset' => SMSC_CHARSET
            )
        ))
        ->post('https://smsc.ru/sys/send.php');
    }
}?>