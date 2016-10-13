<?php
class JsonHelper
{
    public static function encodeContent($content,$result = true)
    {
        return Zend_Json::encode(array('result'    => $result,
                                        'data'      => $content));
    }
}
