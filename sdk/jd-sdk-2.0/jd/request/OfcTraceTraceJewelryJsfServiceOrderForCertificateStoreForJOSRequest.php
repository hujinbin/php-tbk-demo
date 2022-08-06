<?php
class OfcTraceTraceJewelryJsfServiceOrderForCertificateStoreForJOSRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.ofc.trace.traceJewelryJsfService.orderForCertificateStoreForJOS";
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
                                                             	                        	                                                                                                                                                                                                                                                                                                               private $certificateTypeCode;
                              public function setCertificateTypeCode($certificateTypeCode ){
                 $this->certificateTypeCode=$certificateTypeCode;
                 $this->apiParas["certificateTypeCode"] = $certificateTypeCode;
              }

              public function getCertificateTypeCode(){
              	return $this->certificateTypeCode;
              }
                                                                                                                                                                                                                                                                                                                                              private $orderId;
                              public function setOrderId($orderId ){
                 $this->orderId=$orderId;
                 $this->apiParas["orderId"] = $orderId;
              }

              public function getOrderId(){
              	return $this->orderId;
              }
                                                                                                                                                                                                                                                                                                                                              private $institutionCode;
                              public function setInstitutionCode($institutionCode ){
                 $this->institutionCode=$institutionCode;
                 $this->apiParas["institutionCode"] = $institutionCode;
              }

              public function getInstitutionCode(){
              	return $this->institutionCode;
              }
                                                                                                                                                                                                                                                                                                                                              private $groupId;
                              public function setGroupId($groupId ){
                 $this->groupId=$groupId;
                 $this->apiParas["groupId"] = $groupId;
              }

              public function getGroupId(){
              	return $this->groupId;
              }
                                                                                                                                                                                                                                                                                                                                              private $securityCode;
                              public function setSecurityCode($securityCode ){
                 $this->securityCode=$securityCode;
                 $this->apiParas["securityCode"] = $securityCode;
              }

              public function getSecurityCode(){
              	return $this->securityCode;
              }
                                                                                                                                                                                                                                                                                                                                              private $certificateNo;
                              public function setCertificateNo($certificateNo ){
                 $this->certificateNo=$certificateNo;
                 $this->apiParas["certificateNo"] = $certificateNo;
              }

              public function getCertificateNo(){
              	return $this->certificateNo;
              }
                                                                                                                                                                                                                                                                                                                                              private $skuId;
                              public function setSkuId($skuId ){
                 $this->skuId=$skuId;
                 $this->apiParas["skuId"] = $skuId;
              }

              public function getSkuId(){
              	return $this->skuId;
              }
                                                                                                                                                                                                                                                                                                                                              private $certificateType;
                              public function setCertificateType($certificateType ){
                 $this->certificateType=$certificateType;
                 $this->apiParas["certificateType"] = $certificateType;
              }

              public function getCertificateType(){
              	return $this->certificateType;
              }
                                                                                                                                        	                        	                        	}





        
 

