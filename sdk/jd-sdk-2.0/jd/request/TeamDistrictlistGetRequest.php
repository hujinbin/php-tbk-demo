<?php
class TeamDistrictlistGetRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.team.districtlist.get";
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
                                                        		                                    	                   			private $cityId;
    	                                                            
	public function setCityId($cityId){
		$this->cityId = $cityId;
         $this->apiParas["city_id"] = $cityId;
	}

	public function getCityId(){
	  return $this->cityId;
	}

                            }





        
 

