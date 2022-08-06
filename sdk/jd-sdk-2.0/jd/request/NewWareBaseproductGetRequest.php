<?php
class NewWareBaseproductGetRequest
{

    public function __construct()
    {
         $this->version = "1.0";
    }

	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.new.ware.baseproduct.get";
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

    private $version;

    public function setVersion($version){
        $this->version = $version;
    }

    public function getVersion(){
        return $this->version;
    }
    private  $skuIdSet;

    public function setSkuIdSet($skuIdSet){
        $this->apiParas['skuIdSet'] = $skuIdSet;
    }
    public function getSkuIdSet(){
        return $this->apiParas['skuIdSet'];
    }
    private  $baseset;

    public function setBaseset($baseset){
        $this->apiParas['baseset'] = $baseset;
    }
    public function getBaseset(){
        return $this->apiParas['baseset'];
    }
}

?>