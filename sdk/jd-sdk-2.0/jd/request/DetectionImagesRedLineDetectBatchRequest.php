<?php
class DetectionImagesRedLineDetectBatchRequest
{


	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.detection.imagesRedLineDetectBatch";
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
                                                        		                                    	                        	                        	                   			private $timeZone;
    	                        
	public function setTimeZone($timeZone){
		$this->timeZone = $timeZone;
         $this->apiParas["timeZone"] = $timeZone;
	}

	public function getTimeZone(){
	  return $this->timeZone;
	}

                        	                                            		                                    	                   			private $key;
    	                        
	public function setKey($key){
		$this->key = $key;
         $this->apiParas["key"] = $key;
	}

	public function getKey(){
	  return $this->key;
	}

                        	                   			private $value;
    	                        
	public function setValue($value){
		$this->value = $value;
         $this->apiParas["value"] = $value;
	}

	public function getValue(){
	  return $this->value;
	}

                                                                                                    		                                                             	                        	                                                                                                                                                                                                                                                                                                               private $detectItem;
                              public function setDetectItem($detectItem ){
                 $this->detectItem=$detectItem;
                 $this->apiParas["detectItem"] = $detectItem;
              }

              public function getDetectItem(){
              	return $this->detectItem;
              }
                                                                                                                                                                 	                        	                                                                                                                                                                                                                                                                                                               private $imageUrl;
                              public function setImageUrl($imageUrl ){
                 $this->imageUrl=$imageUrl;
                 $this->apiParas["imageUrl"] = $imageUrl;
              }

              public function getImageUrl(){
              	return $this->imageUrl;
              }
                                                                                                                                        	                            }





        
 

