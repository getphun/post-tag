<?php
/**
 * Static page events
 * @package post-tag
 * @version 0.0.1
 * @upgrade false
 */

namespace PostTag\Event;

class TagEvent{
    
    static function general($object, $old=null){
        $dis = \Phun::$dispatcher;
        $page = $dis->router->to('sitePostTagSingle', ['slug'=>$object->slug]);
        $dis->cache->removeOutput($page);
    }
    
    static function created($object){
        self::general($object);
    }
    
    static function updated($object, $old=null){
        self::general($object, $old);
    }
    
    static function deleted($object){
        self::general($object);
    }
}