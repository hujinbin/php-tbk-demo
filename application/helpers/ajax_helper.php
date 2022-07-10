<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/1/31
 * Time: 23:35
 */
if (!function_exists('ajax_json')){
    /**
     * ajax_json
     *
     * 向ajax请求响应json数据
     *
     * @param   int  $status  要响应的成功标志 0 : error, 1 : success
     * @param   array  $data  要传递的消息数组
     * @param   string  $msg  要响应的消息
     * @return  void
     */
    function ajax_json($status=0,$data=array(),$msg='')
    {
        // if($data == '')
        // {
        //     $data=array();
        // }
        $json_data=array(
            'states'=>$status,
            'data'=>$data,
            'msg'=>$msg
        );
        
        echo json_encode($json_data,JSON_UNESCAPED_UNICODE);//输出
        exit;//结束执行
    }
}

if(!function_exists('jump_url'))
{
    /**
     * jump_url
     *
     * 跳转到一个相对的路径地址
     *
     * @param   string  $url  需要跳转的相对或者绝对地址
     */
    function jump_url($url)
    {
        header('Location: '.$url);
        exit;
    }
}


if(!function_exists('curl_get'))
{
    // 调用get接口
    function curl_get($url)
    {
        $testurl = $url;  
        $ch = curl_init();    
        curl_setopt($ch, CURLOPT_URL, $testurl);    
            //参数为1表示传输数据，为0表示直接输出显示。  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
            //参数为0表示不带头文件，为1表示带头文件  
        curl_setopt($ch, CURLOPT_HEADER,0);  
        $output = curl_exec($ch);   
        curl_close($ch);   
        return $output;  
    }
}

if(!function_exists('curl_post'))
{
    // 调用post接口
    function curl_post($url)
    {
        $curl = curl_init();  
        //设置提交的url  
        curl_setopt($curl, CURLOPT_URL, $url);  
        //设置头文件的信息作为数据流输出  
        curl_setopt($curl, CURLOPT_HEADER, 0);  
        //设置获取的信息以文件流的形式返回，而不是直接输出。  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
        //设置post方式提交  
        curl_setopt($curl, CURLOPT_POST, 1);
        //执行命令  
        $data = curl_exec($curl);  
        //关闭URL请求  
        curl_close($curl);  
        //获得数据并返回  
        return $data;  
    }
}