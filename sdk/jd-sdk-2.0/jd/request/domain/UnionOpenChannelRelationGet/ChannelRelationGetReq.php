<?php
namespace UnionOpenChannelRelationGet;
class ChannelRelationGetReq{

    private $params=array();

    function __construct(){
        $this->params["@type"]="com.jd.union.open.gateway.api.dto.channel.relation.ChannelRelationGetReq";
    }
        
    private $pin;
    
    public function setPin($pin){
        $this->params['pin'] = $pin;
    }

    public function getPin(){
        return $this->pin;
    }
            
    private $inviteCode;
    
    public function setInviteCode($inviteCode){
        $this->params['inviteCode'] = $inviteCode;
    }

    public function getInviteCode(){
        return $this->inviteCode;
    }
            
    private $note;
    
    public function setNote($note){
        $this->params['note'] = $note;
    }

    public function getNote(){
        return $this->note;
    }
    
    function getInstance(){
        return $this->params;
    }

}

?>