<?php
class TeamSkulistGetRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.team.skulist.get";
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
                                                        		                                    	                   			private $teamId;
    	                                                            
	public function setTeamId($teamId){
		$this->teamId = $teamId;
         $this->apiParas["team_id"] = $teamId;
	}

	public function getTeamId(){
	  return $this->teamId;
	}

                            }





        
 

