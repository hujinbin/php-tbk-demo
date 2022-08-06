<?php
class KplOpenPolcenterUnsubscribeorderRequest
{


	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.kpl.open.polcenter.unsubscribeorder";
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
                                                        		                                    	                   			private $factoryId;
    	                        
	public function setFactoryId($factoryId){
		$this->factoryId = $factoryId;
         $this->apiParas["factoryId"] = $factoryId;
	}

	public function getFactoryId(){
	  return $this->factoryId;
	}

                        	                   			private $clientIp;
    	                        
	public function setClientIp($clientIp){
		$this->clientIp = $clientIp;
         $this->apiParas["clientIp"] = $clientIp;
	}

	public function getClientIp(){
	  return $this->clientIp;
	}

                        	                   			private $mobile;
    	                        
	public function setMobile($mobile){
		$this->mobile = $mobile;
         $this->apiParas["mobile"] = $mobile;
	}

	public function getMobile(){
	  return $this->mobile;
	}

                        	                   			private $orderNum;
    	                        
	public function setOrderNum($orderNum){
		$this->orderNum = $orderNum;
         $this->apiParas["orderNum"] = $orderNum;
	}

	public function getOrderNum(){
	  return $this->orderNum;
	}

                        	                   			private $imei;
    	                        
	public function setImei($imei){
		$this->imei = $imei;
         $this->apiParas["imei"] = $imei;
	}

	public function getImei(){
	  return $this->imei;
	}

                        	                   			private $wifiMac;
    	                        
	public function setWifiMac($wifiMac){
		$this->wifiMac = $wifiMac;
         $this->apiParas["wifiMac"] = $wifiMac;
	}

	public function getWifiMac(){
	  return $this->wifiMac;
	}

                        	                   			private $bussName;
    	                        
	public function setBussName($bussName){
		$this->bussName = $bussName;
         $this->apiParas["bussName"] = $bussName;
	}

	public function getBussName(){
	  return $this->bussName;
	}

                        	                   			private $androidId;
    	                        
	public function setAndroidId($androidId){
		$this->androidId = $androidId;
         $this->apiParas["androidId"] = $androidId;
	}

	public function getAndroidId(){
	  return $this->androidId;
	}

                            }





        
 

