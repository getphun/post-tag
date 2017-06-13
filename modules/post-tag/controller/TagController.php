<?php
/**
 * Tag controller
 * @package post-tag
 * @version 0.0.1
 * @upgrade false
 */

namespace PostTag\Controller;
use PostTag\Meta\Tag;
use PostTag\Model\PostTag as PTag;
use PostTag\Model\PostTagChain as PTChain;

class TagController extends \SiteController
{
    public function indexAction(){
        // serve only if it's allowed to be served
        if(!$this->setting->post_tag_index_enable)
            return $this->show404();
        
        $page = $this->req->getQuery('page', 1);
        $rpp  = 12;
        
        $cache= 60*60*24*7;
        if($page > 1 || is_dev())
            $cache = null;
        
        $tags = PTag::get([], $rpp, $page, 'created DESC');
        if(!$tags)
            return $this->show404();
        
        $tags = \Formatter::formatMany('post-tag', $tags, false, ['user']);
        $params = [
            'tags' => $tags,
            'index' => new \stdClass(),
            'pagination' => [],
            'total' => PTag::count()
        ];
        
        $params['index']->meta = Tag::index();
        
        // pagination
        if($params['total'] > $rpp)
            $params['pagination'] = calculate_pagination($page, $rpp, $params['total']);
        
        $this->respond('post/tag/index', $params, $cache);
    }
    
    public function singleAction(){
        $slug = $this->param->slug;
        
        $tag = PTag::get(['slug'=>$slug], false);
        if(!$tag)
            return $this->show404();
            
        $page = $this->req->getQuery('page', 1);
        $rpp = 12;
        
        $cache = 60*60*24*7;
        if($page > 1 || is_dev())
            $cache = null;
        
        $tag = \Formatter::format('post-tag', $tag, false, ['user']);
        $params = [
            'tag' => $tag,
            'posts' => [],
            'pagination' => [],
            'total' => 0
        ];
        
        // TODO
        // get the post and pagination
        
        $params['tag']->meta = Tag::single($tag);
        
        $this->respond('post/tag/single', $params, $cache);
    }
}