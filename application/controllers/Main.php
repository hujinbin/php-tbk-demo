<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Main extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        //    $db_mssg_user=$this->load->database();//加载数据库
        date_default_timezone_set("PRC");
    }

    // 首页
    public function index()
    {
        $this->load->view('taobao');
    }

}
