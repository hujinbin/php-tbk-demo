<?php
class MfaInnerValidateMsgCodeRequest
{


	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.mfa.inner.validateMsgCode";
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
                                    	                   			private $msgCode;
    	                        
	public function setMsgCode($msgCode){
		$this->msgCode = $msgCode;
         $this->apiParas["msgCode"] = $msgCode;
	}

	public function getMsgCode(){
	  return $this->msgCode;
	}

                        	                   			private $rKey;
    	                        
	public function setRKey($rKey){
		$this->rKey = $rKey;
         $this->apiParas["rKey"] = $rKey;
	}

	public function getRKey(){
	  return $this->rKey;
	}

                        	                   			private $validateType;
    	                        
	public function setValidateType($validateType){
		$this->validateType = $validateType;
         $this->apiParas["validateType"] = $validateType;
	}

	public function getValidateType(){
	  return $this->validateType;
	}

}





        
 

