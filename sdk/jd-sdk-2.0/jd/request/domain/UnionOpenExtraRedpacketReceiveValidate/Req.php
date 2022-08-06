<?php
namespace UnionOpenExtraRedpacketReceiveValidate;
class Req{

    private $params=array();

    function __construct(){
        $this->params["@type"]="com.jd.union.open.gateway.api.dto.extra.redpacket.ReceiveRedpacketReq";
    }
        
    private $userId;
    
    public function setUserId($userId){
        $this->params['userId'] = $userId;
    }

    public function getUserId(){
        return $this->userId;
    }
            
    private $userIdType;
    
    public function setUserIdType($userIdType){
        $this->params['userIdType'] = $userIdType;
    }

    public function getUserIdType(){
        return $this->userIdType;
    }
            
    private $actId;
    
    public function setActId($actId){
        $this->params['actId'] = $actId;
    }

    public function getActId(){
        return $this->actId;
    }
    
    function getInstance(){
        return $this->params;
    }

}

?>