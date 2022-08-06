<?php
namespace UnionOpenChannelInvitecodeGet;
class ChannelInviteReq{

    private $params=array();

    function __construct(){
        $this->params["@type"]="com.jd.union.open.gateway.api.dto.channel.invite.ChannelInviteReq";
    }
        
    private $inviteType;
    
    public function setInviteType($inviteType){
        $this->params['inviteType'] = $inviteType;
    }

    public function getInviteType(){
        return $this->inviteType;
    }
            
    private $channelType;
    
    public function setChannelType($channelType){
        $this->params['channelType'] = $channelType;
    }

    public function getChannelType(){
        return $this->channelType;
    }
    
    function getInstance(){
        return $this->params;
    }

}

?>