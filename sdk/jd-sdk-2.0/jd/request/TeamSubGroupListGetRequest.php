<?php
class TeamSubGroupListGetRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.team.sub.group.list.get";
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
                                                        		                                    	                   			private $groupId;
    	                                                            
	public function setGroupId($groupId){
		$this->groupId = $groupId;
         $this->apiParas["group_id"] = $groupId;
	}

	public function getGroupId(){
	  return $this->groupId;
	}

                            }





        
 

