<?php
namespace ACES\Common;


abstract class TDEStatus {
    
    /****************************SDK specific errors****************/
    public static $SDK_INTERNAL_ERROR = array("code"=>200, "message"=>"SDK generic exception error.");
//    const SDK_INTERNAL_ERROR = array("code"=>200, "message"=>"SDK generic exception error.");
    public static $SDK_USE_INEFFECTIVE_TOKEN = array("code"=>201, "message"=>"SDK uses an ineffective token.");
//    const SDK_USE_INEFFECTIVE_TOKEN = array("code"=>201, "message"=>"SDK uses an ineffective token.");
    public static $SDK_USE_HARD_EXPIRED_TOKEN = array("code"=>202, "message"=>"SDK uses an expired token with hard deadline.");
//    const SDK_USE_HARD_EXPIRED_TOKEN = array("code"=>202, "message"=>"SDK uses an expired token with hard deadline.");
    public static $SDK_USE_SOFT_EXPIRED_TOKEN = array("code"=>203, "message"=>"SDK uses an expired token with soft deadline.");
//    const SDK_USE_SOFT_EXPIRED_TOKEN = array("code"=>203, "message"=>"SDK uses an expired token with soft deadline.");
    public static $SDK_FAIL_TO_READ_BACKUP = array("code"=>204, "message"=>"SDK cannot fetch any function keys from backup file.");
//    const SDK_FAIL_TO_READ_BACKUP = array("code"=>204, "message"=>"SDK cannot fetch any function keys from backup file.");
    public static $SDK_RECEIVED_WRONG_KEYRESPONSE1 = array("code"=>205, "message"=>"SDK received key response with unmatched service name.");
//    const SDK_RECEIVED_WRONG_KEYRESPONSE1 = array("code"=>205, "message"=>"SDK received key response with unmatched service name.");
    public static $SDK_RECEIVED_WRONG_KEYRESPONSE2 = array("code"=>206, "message"=>"SDK received key response with unmatched token id.");
//    const SDK_RECEIVED_WRONG_KEYRESPONSE2 = array("code"=>206, "message"=>"SDK received key response with unmatched token id.");
    public static $SDK_CANNOT_REACH_KMS = array("code"=>207, "message"=>"KMS is unreachable due to:");
//    const SDK_CANNOT_REACH_KMS = array("code"=>207, "message"=>"KMS is unreachable due to:");
    public static $SDK_HAS_NO_AVAILABLE_ENC_KEYS = array("code"=>208, "message"=>"SDK holds a decrypt-only token or has no key to encrypt data.");
//    const SDK_HAS_NO_AVAILABLE_ENC_KEYS = array("code"=>208, "message"=>"SDK holds a decrypt-only token or has no key to encrypt data.");
    public static $SDK_HAS_NO_CORRESPONDING_DEC_KEYS = array("code"=>209, "message"=>"SDK has no corresponding key to decrypt cipher data, header:");
//    const SDK_HAS_NO_CORRESPONDING_DEC_KEYS = array("code"=>209, "message"=>"SDK has no corresponding key to decrypt cipher data, header:");
    public static $SDK_OPERATE_WITH_EXPIRED_KEYS = array("code"=>210, "message"=>"SDK uses old keys to encrypt/decrypt data.");
//    const SDK_OPERATE_WITH_EXPIRED_KEYS = array("code"=>210, "message"=>"SDK uses old keys to encrypt/decrypt data.");
    public static $SDK_OPERATE_WITH_INACTIVE_KEYS = array("code"=>211, "message"=>"SDK uses suspended/revoked keys to encrypt/decrypt data.");
//    const SDK_OPERATE_WITH_INACTIVE_KEYS = array("code"=>211, "message"=>"SDK uses suspended/revoked keys to encrypt/decrypt data.");
    public static $SDK_THROW_JDK_EXCEPTION = array("code"=>212, "message"=>"SDK threw generic JDK exception.");
//    const SDK_THROW_JDK_EXCEPTION = array("code"=>212, "message"=>"SDK threw generic JDK exception.");
    public static $SDK_USE_INVALID_TOKEN = array("code"=>213, "message"=>"SDK uses an invalid token.");
//    const SDK_USE_INVALID_TOKEN = array("code"=>213, "message"=>"SDK uses an invalid token.");
    public static $SDK_HAS_NO_AVAILABLE_KEYS = array("code"=>214, "message"=>"SDK has no keys in internal cache.");
//    const SDK_HAS_NO_AVAILABLE_KEYS = array("code"=>214, "message"=>"SDK has no keys in internal cache.");
    public static $SDK_HAS_CORRUPTED_KEYS = array("code"=>215, "message"=>"SDK has corrupted keys in internal cache.");
//    const SDK_HAS_CORRUPTED_KEYS = array("code"=>215, "message"=>"SDK has corrupted keys in internal cache.");
    public static $SDK_HAS_CORRUPTED_CIPHER = array("code"=>216, "message"=>"SDK tries to decrypt corrupted cipher, header: ");
//    const SDK_HAS_CORRUPTED_CIPHER = array("code"=>216, "message"=>"SDK tries to decrypt corrupted cipher, header: ");
    public static $SDK_DIDNOT_SETUP_RPATH = array("code"=>217, "message"=>"SDK did not set resource path correctly.");
//    const SDK_DIDNOT_SETUP_RPATH = array("code"=>217, "message"=>"SDK did not set resource path correctly.");

    public static $SDK_FAIL_TO_WRITE_KEYCACHE = array("code"=>218, "message"=>"SDK cannot write key cache file to the given resource path.");
    public static $SDK_FAIL_TO_DELETE_KEYCACHE = array("code"=>219, "message"=>"SDK fails to delete all key cache files.");
    public static $SDK_FAIL_TO_READ_KEYCACHE = array("code"=>220, "message"=>"SDK cannot fetch any function keys from cache file.");
    public static $SDK_FAIL_TO_DELETE_KEYBACKUP = array("code"=>221,"message"=> "SDK fails to delete backup file.");
    // event related
    public static $SDK_SUCCEEDS_TO_DELETE_KEYBACKUP = array("code"=>222, "message"=>"SDK deletes backup file successfully.");
    public static $SDK_SUCCEEDS_TO_DELETE_KEYCACHE = array("code"=>223, "message"=>"SDK deletes cache file successfully.");
    public static $SDK_RECOVERIES_KEYS_FROM_KEYBACKUP = array("code"=>224, "message"=>"SDK recoveries keys from backup file successfully.");
    public static $SDK_RECOVERIES_KEYS_FROM_KEYCACHE = array("code"=>225, "message"=>"SDK recoveries keys from cache file successfully.");
    public static $SDK_SUCCEEDS_TO_OVERWRITE_KEYBACKUP = array("code"=>226, "message"=>"SDK successfully rewrite new keys to cache file.");
    public static $SDK_FAILS_TO_FETCH_UPDATED_KEYS = array("code"=>227, "message"=>"SDK failed to fetch rotated keys, header:");
    public static $SDK_TRIGGER_ROTATED_KEY_FETCH = array("code"=>228, "message"=>"SDK trigger key fetching because ciphertext is encrypted with newer keys.");
    public static $SDK_REPORT_CUR_KEYVER = array("code"=>229, "message"=>"CurKeyVer=");
    // sign/verify
    public static $SDK_HAS_NO_AVAILABLE_SIGN_KEYS = array("code"=>233, "message"=>"SDK has no key to sign data.");
    public static $SDK_HAS_NO_CORRESPONDING_VERIFY_KEYS = array("code"=>234, "message"=>"SDK has no corresponding key to verify signature, header:");



    /****************************TMS about**************************/
    public static $TMS_INTERNAL_ERROR = array("code"=>300, "message"=>"TMS internal system error.");
    public static $TMS_DB_DATA_NOTFOUND_ERROR = array("code"=>301, "message"=>"TMS-db's data not found.");
    public static $TMS_REQUEST_ARGS_ERROR = array("code"=>302, "message"=>"Request argument error.");
    public static $TMS_DB_DATA_ERROR = array("code"=>303, "message"=>"Tms db data error.");
    public static $TMS_KMS_REQUEST_EXPIRE = array("code"=>304, "message"=>" KMS request timeout.");
    public static $TMS_REQUEST_VERIFY_FAILED = array("code"=>305, "message"=>"Request signature validation failed.");
    public static $TMS_TOKEN_EXPIRE = array("code"=>306, "message"=>"The request token is expired.");
    public static $TMS_TOKEN_IS_FROZEN = array("code"=>307, "message"=>"The request token is frozen.");
    public static $TMS_TOKEN_IS_REVOKE = array("code"=>308, "message"=>"The request token is revoked.");
    public static $TMS_TOKEN_IS_NOT_IN_THE_EFFECT_TIME_RANGE = array("code"=>309, "message"=>"The token is ineffective.");
    public static $TMS_TOKEN_IN_DB_IS_NULL = array("code"=>310, "message"=>"The token in the db is null.");
    public static $TMS_NO_AVAILABLE_GRANTS_FOR_SERVICE = array("code"=>311, "message"=>"The token has no granted service.");

    public static $SDK_HAS_PROPERTY_NOT_SET = array("code"=>901, "message"=>"SDK has property not set. ");
}

