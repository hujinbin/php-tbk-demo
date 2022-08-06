<?php
class UnionOpenStatisticsRedpacketPidQueryRequest
{

    public function __construct()
    {
         $this->version = "1.0";
    }

	private $apiParas = array();
	
	public function getApiMethodName(){
	  return "jd.union.open.statistics.redpacket.pid.query";
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
    private  $redPacketPidEffectDataReq;

    public function setRedPacketPidEffectDataReq($redPacketPidEffectDataReq){
        $this->apiParas['redPacketPidEffectDataReq'] = $redPacketPidEffectDataReq;
    }
    public function getRedPacketPidEffectDataReq(){
        return $this->apiParas['redPacketPidEffectDataReq'];
    }
}

?>