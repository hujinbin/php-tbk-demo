<?php
namespace ACES\Common\Salsa20;

class IndexCalculationHelper
{
    const NON_ASCII_PLACEHOLDER = "#";
    const ASCII_PLACEHOLDER = "*";
    
    private static $LONG_PLACEHOLDER = "******";
    
    public static function formatPlaintext($spt) {
        return self::unicodeEncode($spt);
    }
    
    public static function unicodeEncode($spt){
        if(mb_check_encoding($spt, "ascii")){
            return $spt;
        }
        $ret = "";
        $len = mb_strlen($spt, "utf8");
        
        for($i=0; $i<$len; $i++){
            if(mb_check_encoding(mb_substr($spt, $i, 1), "ascii")){
                $ret .= mb_substr($spt, $i, 1);
                continue;
            }
            $hexB = FieldElement::fromString(mb_convert_encoding(mb_substr($spt, $i, 1), "unicode"))->toHexLowcase();
            $ret .= "\u" . $hexB;
        }
        return $ret;
    }
    
    public static function formatQueryKeyword($queryW, $placeholderForNonAscii = self::NON_ASCII_PLACEHOLDER) {
        $queryW = self::unicodeEncode($queryW);
        $len = mb_strlen($queryW);
        $ret = "";
        
        for($i = 0; $i < $len; $i ++){
            if(mb_substr($queryW, $i, 1) == $placeholderForNonAscii){
                $ret .= self::$LONG_PLACEHOLDER;
            } elseif (mb_substr($queryW, $i, 1) == self::ASCII_PLACEHOLDER) {
                $ret .= self::ASCII_PLACEHOLDER;
            } else {
                $ret .= mb_substr($queryW, $i);
                break;
            }
        }
        
        return $ret;
    }
    
    public static function generateWildcardKeyword($queryW, $asciiCharPrefixNumber, $nonAsciiCharPrefixNumber) {
        $length = $asciiCharPrefixNumber + $nonAsciiCharPrefixNumber * 6;
        
        if($length == 0){
            return $queryW;
        }
        
        $ret = str_repeat(self::ASCII_PLACEHOLDER, $length);
        
        return $ret . $queryW;
    }
}

