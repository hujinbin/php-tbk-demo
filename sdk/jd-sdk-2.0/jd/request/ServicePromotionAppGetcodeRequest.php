<?php
class ServicePromotionAppGetcodeRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.service.promotion.app.getcode";
	}
	
	public function getApiParas(){
        if(empty($this->apiParas)){
	        return "{}";
	    }
		return json_encode($this->apiParas);
	}
	
	public function check(){
		
	}
	
    public function putOtherTextParam($key, $value){
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}

    private  $version;

    public function setVersion($version){
        $this->version = $version;
    }

    public function getVersion(){
        return $this->version;
    }
    private  $AppPromotionCodeParam;

    public function setAppPromotionCodeParam($AppPromotionCodeParam){
        $this->apiParas['AppPromotionCodeParam'] = $AppPromotionCodeParam;
    }
    public function getAppPromotionCodeParam(){
        return $this->apiParas['AppPromotionCodeParam'];
    }
}

?>