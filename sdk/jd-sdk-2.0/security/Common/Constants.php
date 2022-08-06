<?php
namespace ACES\Common;

use ACES\Common\Exception\MalformedException;

/**
 * ACES PHP Client constants
 * <P>
 *
 * @author JD Data Security Team (tenma.lin, wei.gao, mozhiyan, xuyina)
 * @version 1.0
 *        
 */
abstract class Constants {
    const CIPHER_TYPE_WEAK      = 0;
    const CIPHER_TYPE_REGULAR   = 1;
    const CIPHER_TYPE_LARGE     = 2;
    const CIPHER_TYPE_LEN       = 1;
    
    const ALGO_TYPE_AES_CBC_128 = 4;
    const ALGO_TYPE_LEN         = 1;
    
    const DEFAULT_KEYID_LEN = 16;
    const DEFAULT_DKEY_LEN = 16;
    const DEFAULT_CIPHERBLK_LEN = 16;
    const DEFAULT_SEED_LEN = 16;
    
    // http&https configuration
//    const PROD_CA_PATH = __DIR__ . "/cert/prod_ca_cert.crt";
    const PROD_CA_RELATIVE_PATH = "/cert/prod_ca_cert.crt";
//    const BETA_CA_PATH = __DIR__ . "/cert/beta_ca_cert.crt";
    const BETA_CA_RELATIVE_PATH = "/cert/beta_ca_cert.crt";
    const HTTP_RETRY_MAX = 2;
    const HTTP_TIMEOUT =  5000;     // 5000 ms
    
    const TOKEN_EXP_DELTA = 2592000000;
    
    // algo
    const DEFAULT_TOKEN_SIGN_ALGO = "sha256";
    const DEFAULT_CERTDIGEST_ALGO = "sha256";
    
    // weak version header: CipherType(1b) Algorithm(1b) | MKeyID(16b)
    //1 + 1 + 16
    const WEAK_HDR_LEN = 18; // 18 fixed
    // strong version header:
    // cipher type, mkey_id length, mkey_id,
    // key cipher type, key cipher length, key cipher
    // data cipher type, data cipher length, data cipherh
    //1 + 2 + 16 + 1 + 2 + 16 + 1 + 4
    const STRONG_HDR_LEN = 43; // 43 fixed
    
    
    const TMS_BTEA_TOKEN_CERT = "-----BEGIN CERTIFICATE-----MIIDgjCCAmoCCQCEnAy4Ro7QFDANBgkqhkiG9w0BAQsFADCBgjELMAkGA1UEBhMCQ04xEDAOBgNVBAgMB0JlaWppbmcxEDAOBgNVBAcMB0JlaWppbmcxCzAJBgNVBAoMAkpEMREwDwYDVQQLDAhTZWN1cml0eTEOMAwGA1UEAwwFVGVubWExHzAdBgkqhkiG9w0BCQEWEHRlbm1hLmxpbkBqZC5jb20wHhcNMTcwNzI4MjA0NTE4WhcNMTgwNzI4MjA0NTE4WjCBgjELMAkGA1UEBhMCQ04xEDAOBgNVBAgMB0JlaWppbmcxEDAOBgNVBAcMB0JlaWppbmcxCzAJBgNVBAoMAkpEMREwDwYDVQQLDAhTZWN1cml0eTEOMAwGA1UEAwwFVGVubWExHzAdBgkqhkiG9w0BCQEWEHRlbm1hLmxpbkBqZC5jb20wggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQC9evzs1Lcm8pFAt5Sj7g4cZDbEhxs+aOFNDGm5TfoxDlbF32Ee1Q/o6pocoUL8op7TjcW2gIGRdidjmbQnsdDdpSGm7cGetsc//qBvHnyjByxXjQyBvGuwUtUJa2c+67Ot4G4ejY4bPjwV2NDJN7z8/qr46PJeLUwF628N+xLEbOmjpgHt3/b+xHnAk1lIbZcdW6KQ2dteWewGGHO0Znk28/CsBLXvlipucVpkRIZ7m8R2G6dDgkOl8KdX658v2NCgqO0gvZt12TiquguSMH6cYhQgb9sB31HRdlzAf1NcGfYJ3H4CHu0CZnJW//uFOrCkrz5VHdpHu2zASOzI/9TRAgMBAAEwDQYJKoZIhvcNAQELBQADggEBAAvhIw6Ir/RDtW0dcMGOqCieqqRd/bE+rRR3IxDy2q1NbVY2rp4Jp0qwyrGyE/8L+QUQb+78MOU5+vxfibwNSULi1wlqQG4rN3syMWYDP3G9pMqJNoUgrqYKn+7oDtyRGyMFclIB9tBedA+XSwooX0/EhqhnkaYZeB+tYQ5giS4uRKZv4bMsnxO1Sew2v1lt84ZFiLcBL7onf1MJXPLSwN9DoUTYJf1DqT9aSLG6jK5OhP4u3AsUKsqarI6sp46nM6uCUSBcO3okd+fIrl/vtKARRVqxENQQ36/k4Z7krrrTyWox8dZ3XYbtagWy0hAA5z9GWS/GwiR3tueRJF2BNeA=-----END CERTIFICATE-----";

//    const TMS_PROD_TOKEN_CERT = "-----BEGIN CERTIFICATE-----MIIEcDCCA1igAwIBAgIJAKCBMSvIHNiEMA0GCSqGSIb3DQEBBQUAMIGAMQswCQYDVQQGEwJDTjEQMA4GA1UECBMHQmVpamluZzEQMA4GA1UEBxMHQmVpamluZzEPMA0GA1UEChMGSkQuQ09NMQwwCgYDVQQLEwNKT1MxEzARBgNVBAMTCmpvcy5qZC5jb20xGTAXBgkqhkiG9w0BCQEWCmpvc0BqZC5jb20wIBcNMTkwMzE1MDQ1NTM2WhgPMjA1OTAzMDUwNDU1MzZaMIGAMQswCQYDVQQGEwJDTjEQMA4GA1UECBMHQmVpamluZzEQMA4GA1UEBxMHQmVpamluZzEPMA0GA1UEChMGSkQuQ09NMQwwCgYDVQQLEwNKT1MxEzARBgNVBAMTCmpvcy5qZC5jb20xGTAXBgkqhkiG9w0BCQEWCmpvc0BqZC5jb20wggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDf9jdFbaYJLb6H/B1EEtuOokkjrU1taQSudZhuBlnzCiKeUjK6vYDoqGgJSzRI86slU/rkK/7o4mc8LOvmAJRvULWLUdM9EzI+6+M6eVLwuWnm3QMIJJl1y7dQqwnAMLl3T/P6UGP1g19R7D8LcaEw289Y8i/qJaVdobaM822xcW4Wv+QIldlWo6YlDoE7dfY9pXTlAkTP/GzO+LOnCzp1/VA3Q6Xl1Cl4Kvk0wFWnGiMEbVEZx9yEknwPV1Viq3QGjMPoEGEau6x9srCcEitClllqXHOWkIVNt//qN2ubx90wjyHKZTe3HrQ/LFSIWLTeNo738iR8tFzxSfa5hitZAgMBAAGjgegwgeUwHQYDVR0OBBYEFHYHDa2moq7nEccftSm3x72QBWWJMIG1BgNVHSMEga0wgaqAFHYHDa2moq7nEccftSm3x72QBWWJoYGGpIGDMIGAMQswCQYDVQQGEwJDTjEQMA4GA1UECBMHQmVpamluZzEQMA4GA1UEBxMHQmVpamluZzEPMA0GA1UEChMGSkQuQ09NMQwwCgYDVQQLEwNKT1MxEzARBgNVBAMTCmpvcy5qZC5jb20xGTAXBgkqhkiG9w0BCQEWCmpvc0BqZC5jb22CCQCggTEryBzYhDAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4IBAQAr9qLL6qkNJjtcOzYM5afdyt+KBF9iwIcKG8caNUPNXwOFnOFw/JBKR4svjafvV3rSGs7ZtVMmASLUhrtStwfJJvXV7tdyqC0p44u/sWK6SHoTNIHX+kXbzKrkwggqeTiUlHDTw60BP/mmbrYhIwOiTNvI247iWZ4IxxyDbpFULv0gBfTVuc/ATWrHTI2pT78lIectDgUCpTOAhQIvE0PLK9nZjrsSCvW7tREDPC+6KCPYQAzxmKvRRMCHXkAVeqb/0M6GEXBIT0aYEBHKdQ7s4g1VSGrbMUL5mQsA+3fYhR+QEhE8PboH5kVct1V9tiMpx7kymJQKVfNufC3FIlyr-----END CERTIFICATE-----";
    const TMS_PROD_TOKEN_CERT = "-----BEGIN CERTIFICATE-----\nMIIEcDCCA1igAwIBAgIJAKCBMSvIHNiEMA0GCSqGSIb3DQEBBQUAMIGAMQswCQYD\nVQQGEwJDTjEQMA4GA1UECBMHQmVpamluZzEQMA4GA1UEBxMHQmVpamluZzEPMA0G\nA1UEChMGSkQuQ09NMQwwCgYDVQQLEwNKT1MxEzARBgNVBAMTCmpvcy5qZC5jb20x\nGTAXBgkqhkiG9w0BCQEWCmpvc0BqZC5jb20wIBcNMTkwMzE1MDQ1NTM2WhgPMjA1\nOTAzMDUwNDU1MzZaMIGAMQswCQYDVQQGEwJDTjEQMA4GA1UECBMHQmVpamluZzEQ\nMA4GA1UEBxMHQmVpamluZzEPMA0GA1UEChMGSkQuQ09NMQwwCgYDVQQLEwNKT1Mx\nEzARBgNVBAMTCmpvcy5qZC5jb20xGTAXBgkqhkiG9w0BCQEWCmpvc0BqZC5jb20w\nggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQDf9jdFbaYJLb6H/B1EEtuO\nokkjrU1taQSudZhuBlnzCiKeUjK6vYDoqGgJSzRI86slU/rkK/7o4mc8LOvmAJRv\nULWLUdM9EzI+6+M6eVLwuWnm3QMIJJl1y7dQqwnAMLl3T/P6UGP1g19R7D8LcaEw\n289Y8i/qJaVdobaM822xcW4Wv+QIldlWo6YlDoE7dfY9pXTlAkTP/GzO+LOnCzp1\n/VA3Q6Xl1Cl4Kvk0wFWnGiMEbVEZx9yEknwPV1Viq3QGjMPoEGEau6x9srCcEitC\nlllqXHOWkIVNt//qN2ubx90wjyHKZTe3HrQ/LFSIWLTeNo738iR8tFzxSfa5hitZ\nAgMBAAGjgegwgeUwHQYDVR0OBBYEFHYHDa2moq7nEccftSm3x72QBWWJMIG1BgNV\nHSMEga0wgaqAFHYHDa2moq7nEccftSm3x72QBWWJoYGGpIGDMIGAMQswCQYDVQQG\nEwJDTjEQMA4GA1UECBMHQmVpamluZzEQMA4GA1UEBxMHQmVpamluZzEPMA0GA1UE\nChMGSkQuQ09NMQwwCgYDVQQLEwNKT1MxEzARBgNVBAMTCmpvcy5qZC5jb20xGTAX\nBgkqhkiG9w0BCQEWCmpvc0BqZC5jb22CCQCggTEryBzYhDAMBgNVHRMEBTADAQH/\nMA0GCSqGSIb3DQEBBQUAA4IBAQAr9qLL6qkNJjtcOzYM5afdyt+KBF9iwIcKG8ca\nNUPNXwOFnOFw/JBKR4svjafvV3rSGs7ZtVMmASLUhrtStwfJJvXV7tdyqC0p44u/\nsWK6SHoTNIHX+kXbzKrkwggqeTiUlHDTw60BP/mmbrYhIwOiTNvI247iWZ4IxxyD\nbpFULv0gBfTVuc/ATWrHTI2pT78lIectDgUCpTOAhQIvE0PLK9nZjrsSCvW7tRED\nPC+6KCPYQAzxmKvRRMCHXkAVeqb/0M6GEXBIT0aYEBHKdQ7s4g1VSGrbMUL5mQsA\n+3fYhR+QEhE8PboH5kVct1V9tiMpx7kymJQKVfNufC3FIlyr\n-----END CERTIFICATE-----";
    const TDE_BETA_CA_CERT = "-----BEGIN CERTIFICATE-----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-----END CERTIFICATE-----";
    
    const TDE_PROD_CA_CERT = "-----BEGIN CERTIFICATE-----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-----END CERTIFICATE-----";
    
    // index server configuration
    const INDEX_SERVER_ENDPOINT = "http://indexserver.aces.jd.local/kms/v1/host";
    const DEV_INDEX_SERVER_ENDPOINT = "http://indexsvr-indexserverdev.aces.svc.hcyf.n.jd.local/kms/v1/host";
    const BETA_INDEX_SERVER_ENDPOINT = "http://test.indexserver.aces.jd.local/kms/v1/host";
    
    // KMS configuration
    const KMS_SERVER_ENDPOINT = "https://kms.tde.jd.local";
    const KMS_ENDPOINT_REQUEST_MK = "/request_mk";
    const BETA_KMS_SERVER_ENDPOINT = "https://beta.kms.tde.jd.local";
    const DEV_KMS_SERVER_ENDPOINT = "https://dev.kms.tde.jd.local";
    
    // ID
    const ID_KMS_SERVER_ENDPOINT = "https://kms.jd.id";
    const ID_INDEX_SERVER_ENDPOINT = "http://indexserver.aces.jd.id/kms/v1/host";
    // TH
    const TH_KMS_SERVER_ENDPOINT = "https://kms.jd.th.local";
    const TH_INDEX_SERVER_ENDPOINT = "http://indexserver.jd.th.local/kms/v1/host";
    
}

abstract class KEY_STATUS {
    const ACTIVE = 0;
    const SUSPENDED = 1;
    const REVOKED = 2;
    
    public static function fromValue($value){
        if($value === null){
            throw new MalformedException("Key status is null.");
        }
        
        switch ($value){
            case 0: return KEY_STATUS::ACTIVE;
            case 1: return KEY_STATUS::SUSPENDED;
            case 2: return KEY_STATUS::REVOKED;
            default:throw new MalformedException("Unknown key status.");
        }
    }
}

abstract class KEY_USAGE{
    const N = -1;
    const E = 0;
    const D = 1;
    const ED = 2;
    
    public static function fromValue($value){
        if($value === null){
            throw new MalformedException("Key usage is null.");
        }
        
        switch ($value){
            case "N": return KEY_USAGE::N;
            case "E": return KEY_USAGE::E;
            case "D": return KEY_USAGE::D;
            case "ED": return KEY_USAGE::ED;
            default: throw new MalformedException("Unknown key usage.");
        }
    }
}

abstract class KEY_TYPE {
    const AES = 0;
    
    public static function fromValue($value){
        if($value === null) throw new MalformedException("Key type is null.");
        switch ($value){
            case 0: return KEY_TYPE::AES;
            default:throw new MalformedException("Unknown key type.");
        }
    }
}


