<?php
/**
 * Static page robot provider
 * @package post-tag
 * @version 1.0.0
 */

namespace PostTag\Controller;
use PostTag\Library\Robot;
use PostTag\Model\PostTag as PTag;

class RobotController extends \SiteController
{
    public function feedAction(){
        if(!module_exists('robot'))
            return $this->show404();
        
        $feed_host   = $this->setting->post_tag_index_enable ? 'sitePostTag' : 'siteHome';
        
        $feed = (object)[
            'url'         => $this->router->to('sitePostTagFeed'),
            'description' => hs($this->setting->post_tag_index_meta_description),
            'updated'     => null,
            'host'        => $this->router->to($feed_host),
            'title'       => hs($this->setting->post_tag_index_meta_title)
        ];
        
        $pages = Robot::feed();
        $this->robot->feed($feed, $pages);
    }
    
    public function feedSingleAction(){
        if(!module_exists('robot'))
            return $this->show404();
        
        $slug = $this->param->slug;
            
        $tag = PTag::get(['slug'=>$slug], false);
        if(!$tag)
            return $this->show404();
        
        $tag = \Formatter::format('post-tag', $tag, false);
        
        $feed = (object)[
            'url'         => $this->router->to('sitePostTagSingleFeed', ['slug'=>$tag->slug]),
            'description' => hs($tag->meta_description->value != '' ? $tag->meta_description : $tag->about),
            'updated'     => null,
            'host'        => $tag->page,
            'title'       => hs($tag->name)
        ];
        
        $pages = Robot::feedPost($tag);
        $this->robot->feed($feed, $pages);
    }
}