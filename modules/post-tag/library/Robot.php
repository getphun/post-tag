<?php
/**
 * Robot provider
 * @package post-tag
 * @version 0.0.1
 * @upgrade true
 */

namespace PostTag\Library;
use PostTag\Model\PostTag as PTag;

class Robot
{
    static function _getTags(){
        // get all pages that is updated last 2 days
        $last2days = date('Y-m-d H:i:s', strtotime('-2 days'));
        
        $tags = PTag::get([
            'updated >= :updated',
            'bind' => [
                'updated' => $last2days
            ]
        ], true);
        
        if(!$tags)
            return false;
        
        return \Formatter::formatMany('post-tag', $tags, false, ['user']);
    }
    
    static function feed(){
        $result = [];
        
        $tags = self::_getTags();
        
        if(!$tags)
            return $result;
        
        foreach($tags as $tag){
            $desc = $tag->meta_description->safe;
            if(!$desc)
                $desc = $tag->about->chars(160);
            
            $result[] = (object)[
                'author'      => hs($tag->user->fullname),
                'description' => $desc,
                'page'        => $tag->page,
                'published'   => $tag->created->format('c'),
                'updated'     => $tag->updated->format('c'),
                'title'       => $tag->name->safe
            ];
        }
        
        return $result;
    }
    
    static function sitemap(){
        $result = [];
        
        $tags = self::_getTags();
        
        if(!$tags)
            return $result;
        
        foreach($tags as $tag){
            $result[] = (object)[
                'url'       => $tag->page,
                'lastmod'   => $tag->updated->format('Y-m-d'),
                'changefreq'=> 'daily',
                'priority'  => 0.4
            ];
        }
        
        return $result;
    }
}