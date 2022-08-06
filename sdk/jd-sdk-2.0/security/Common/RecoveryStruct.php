<?php
namespace ACES\Common;

/**
 * RecoveryStruct
 *
 * <P>
 *
 * @author JD Data Security Team (tenma.lin, wei.gao, mozhiyan, xuyina)
 * @version 1.0
 */
class RecoveryStruct
{
    private $backup;
    public function __construct() {
        $this->backup = array();
    }
    
    /*
     * @return array[RecoveryKey]
     */
    public function getKeys() {
        return $this->backup;
    }
    
    /**
     * 
     * @param RecoveryKey $key
     */
    public function insertKey($key) {
        $this->backup[] = $key;
    }
    
    /**
     * 
     * @param array[RecoveryKey]|null $backup
     */
    public function setBackup($backup) {
        $this->backup = $backup;
    }
}

