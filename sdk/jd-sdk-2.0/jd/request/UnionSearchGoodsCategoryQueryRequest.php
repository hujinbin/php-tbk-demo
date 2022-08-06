<?php
class UnionSearchGoodsCategoryQueryRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.union.search.goods.category.query";
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
                                    	                        	                   			private $parentId;
    	                                                            
	public function setParentId($parentId){
		$this->parentId = $parentId;
         $this->apiParas["parent_id"] = $parentId;
	}

	public function getParentId(){
	  return $this->parentId;
	}

                        	                   			private $grade;
    	                        
	public function setGrade($grade){
		$this->grade = $grade;
         $this->apiParas["grade"] = $grade;
	}

	public function getGrade(){
	  return $this->grade;
	}

}





        
 

