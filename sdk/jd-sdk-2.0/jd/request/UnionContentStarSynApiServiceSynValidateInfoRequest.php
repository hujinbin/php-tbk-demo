<?php
class UnionContentStarSynApiServiceSynValidateInfoRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.union.content.StarSynApiService.synValidateInfo";
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
                                                        		                                    	                   			private $synDate;
    	                        
	public function setSynDate($synDate){
		$this->synDate = $synDate;
         $this->apiParas["synDate"] = $synDate;
	}

	public function getSynDate(){
	  return $this->synDate;
	}

                        	                   			private $type;
    	                        
	public function setType($type){
		$this->type = $type;
         $this->apiParas["type"] = $type;
	}

	public function getType(){
	  return $this->type;
	}

                        	                   			private $platId;
    	                        
	public function setPlatId($platId){
		$this->platId = $platId;
         $this->apiParas["platId"] = $platId;
	}

	public function getPlatId(){
	  return $this->platId;
	}

                        	                   			private $command;
    	                        
	public function setCommand($command){
		$this->command = $command;
         $this->apiParas["command"] = $command;
	}

	public function getCommand(){
	  return $this->command;
	}

                        	                   			private $sumCount;
    	                        
	public function setSumCount($sumCount){
		$this->sumCount = $sumCount;
         $this->apiParas["sumCount"] = $sumCount;
	}

	public function getSumCount(){
	  return $this->sumCount;
	}

                            }





        
 

