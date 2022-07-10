<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/1/27
 * Time: 16:25
 */

/*
 *
 * */
if (!function_exists('is_login')){
    function is_login()
    {
        $CI =& get_instance();
        if(isset($_SESSION['uid'])) {
            $session_data=array(
                'uid'=>$CI->session->uid,
                'salt'=>$CI->session->salt
            );
            if($CI->user_data->generate_key($session_data) == $CI->session->key)
            {
                return $CI->session->uid;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}

if(!function_exists('check_login'))
{
    function check_login()
    {

    }
}
