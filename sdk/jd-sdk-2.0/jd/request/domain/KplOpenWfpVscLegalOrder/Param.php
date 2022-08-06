<?php
namespace KplOpenWfpVscLegalOrder;
class Param{

    private $params=array();

    function __construct(){
        $this->params["@type"]="com.jd.kpl.MerchantRequestWrap";
    }
        
    private $merchantCode;
    
    public function setMerchantCode($merchantCode){
        $this->params['merchantCode'] = $merchantCode;
    }

    public function getMerchantCode(){
        return $this->merchantCode;
    }
            
    private $data;
    
    public function setData($data){
        $this->params['data'] = $data;
    }

    public function getData(){
        return $this->data;
    }
    
    function getInstance(){
        return $this->params;
    }

}

?>