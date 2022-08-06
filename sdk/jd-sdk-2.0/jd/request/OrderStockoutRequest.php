<?php
class OrderStockoutRequest
{
	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jingdong.orderStockout";
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
                                                        		                                    	                   			private $venderId;
    	                        
	public function setVenderId($venderId){
		$this->venderId = $venderId;
         $this->apiParas["venderId"] = $venderId;
	}

	public function getVenderId(){
	  return $this->venderId;
	}

                        	                   			private $orderId;
    	                        
	public function setOrderId($orderId){
		$this->orderId = $orderId;
         $this->apiParas["orderId"] = $orderId;
	}

	public function getOrderId(){
	  return $this->orderId;
	}

                        	                   			private $completeDate;
    	                        
	public function setCompleteDate($completeDate){
		$this->completeDate = $completeDate;
         $this->apiParas["completeDate"] = $completeDate;
	}

	public function getCompleteDate(){
	  return $this->completeDate;
	}

                        	                   			private $operName;
    	                        
	public function setOperName($operName){
		$this->operName = $operName;
         $this->apiParas["operName"] = $operName;
	}

	public function getOperName(){
	  return $this->operName;
	}

                            }





        
 

