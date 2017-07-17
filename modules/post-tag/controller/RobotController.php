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
    private function feed($type='xml'){
        if(!module_exists('robot'))
            return $this->show404();
        
        if($type === 'json' && !$this->config->robot['json'])
            return $this->show404();
        
        $feed_router = $type === 'xml' ? 'sitePostTagFeedXML' : 'sitePostTagFeedJSON';
        $feed_host   = $this->setting->post_tag_index_enable ? 'sitePostTag' : 'siteHome';
        
        $feed = (object)[
            'url'         => $this->router->to($feed_router),
            'description' => hs($this->setting->post_tag_index_meta_description),
            'updated'     => null,
            'host'        => $this->router->to($feed_host),
            'title'       => hs($this->setting->post_tag_index_meta_title)
        ];
        
        $pages = Robot::feed();
        $this->robot->feed($feed, $pages, $type);
    }
    
    private function feedSingle($slug, $type='xml'){
        if(!module_exists('robot'))
            return $this->show404();
        
        if($type === 'json' && !$this->config->robot['json'])
            return $this->show404();
        
        $tag = PTag::get(['slug'=>$slug], false);
        if(!$tag)
            return $this->show404();
        
        $tag = \Formatter::format('post-tag', $tag, false);
        
        $feed_router = $type === 'xml' ? 'sitePostTagSingleFeedXML' : 'sitePostTagSingleFeedJSON';
        
        $feed = (object)[
            'url'         => $this->router->to($feed_router, ['slug'=>$tag->slug]),
            'description' => hs($tag->meta_description->value != '' ? $tag->meta_description : $tag->about),
            'updated'     => null,
            'host'        => $tag->page,
            'title'       => hs($tag->name)
        ];
        
        $pages = Robot::feedPost($tag);
        $this->robot->feed($feed, $pages, $type);
    }
    
    public function feedXmlAction(){
        $this->feed('xml');
    }
    
    public function feedJsonAction(){
        $this->feed('json');
    }
    
    public function feedSingleXmlAction(){
        $this->feedSingle($this->param->slug, 'xml');
    }
    
    public function feedSingleJsonAction(){
        $this->feedSingle($this->param->slug, 'json');
    }
}