<?php
/**
 * Meta provider
 * @package post-tag
 * @version 0.0.1
 * @upgrade true
 */

namespace PostTag\Meta;

class Tag
{
    static function index(){
        $dis = \Phun::$dispatcher;
        
        $page = $dis->req->getQuery('page', 1);
        
        $base_url   = $dis->router->to('siteHome');
        
        $meta_title = $dis->setting->post_tag_index_meta_title;
        $meta_desc  = $dis->setting->post_tag_index_meta_description;
        $meta_keys  = $dis->setting->post_tag_index_meta_keywords;
        $meta_url   = $dis->router->to('sitePostTag');
        $meta_image = $base_url . 'theme/site/static/logo/500x500.png';
        
        if($page && $page > 1){
            $meta_title = sprintf('Page %s %s', $page, $meta_title);
            $meta_desc  = sprintf('Page %s %s', $page, $meta_desc);
            $meta_url   = $meta_url . '?page=' . $page;
        }
        
        $index = (object)[
            '_schemas' => [],
            '_metas'   => [
                'title'         => $meta_title,
                'canonical'     => $meta_url,
                'description'   => $meta_desc,
                'keywords'      => $meta_keys,
                'image'         => $meta_image,
                'type'          => 'website'
            ]
        ];
        
        // my rss feed?
        if(module_exists('robot'))
            $index->_metas['feed'] = $dis->router->to('sitePostTagFeed');
        
        // Schema
        $schema = [
            '@context'      => 'http://schema.org',
            '@type'         => 'CollectionPage',
            'name'          => $meta_title,
            'description'   => $meta_desc,
            'publisher'     => [
                '@type'         => 'Organization',
                'name'          => $dis->config->name,
                'url'           => $base_url,
                'logo'          => $meta_image
            ],
            'url'           => $meta_url,
            'image'         => $meta_image
        ];
        
        $index->_schemas[] = $schema;
        
        return $index;
    }
    
    static function mask($tag, $post){
        $dis = \Phun::$dispatcher;
        
        $base_url = $dis->router->to('siteHome');
        
        $meta_desc  = $tag->meta_description->safe;
        if(!$meta_desc)
            $meta_desc = $tag->about->chars(160);
        $meta_image = $base_url . 'theme/site/static/logo/500x500.png';
        $meta_url   = $tag->page;
        $meta_title = $tag->meta_title->safe;
        $meta_keys  = $tag->meta_keywords;
        if(!$meta_title)
            $meta_title = $tag->name->safe;
        
        $page = $dis->req->getQuery('page', 1);
        if($page && $page > 1){
            $meta_title = sprintf('Page %s %s', $page, $meta_title);
            $meta_desc  = sprintf('Page %s %s', $page, $meta_desc);
            $meta_url   = $meta_url . '?page=' . $page;
        }
        
        // metas
        $single = (object)[
            '_schemas' => [],
            '_metas'   => [
                'title'         => $meta_title,
                'canonical'     => $meta_url,
                'description'   => $meta_desc,
                'keywords'      => $meta_keys,
                'image'         => $meta_image,
                'type'          => 'website',
                
                'og:title'      => $post->meta_title->value ? $post->meta_title : $post->title,
                'og:url'        => $post->page,
                'og:description'=> $post->meta_description,
                'og:image'      => $post->cover,
                'og:type'       => 'article'
            ]
        ];
        
        // my rss feed?
        if(module_exists('robot'))
            $single->_metas['feed'] = $dis->router->to('sitePostTagSingleFeed', ['slug'=>$tag->slug]);
        
        // schemas 
        $schema = [
            '@context'      => 'http://schema.org',
            '@type'         => 'CollectionPage',
            'name'          => $tag->name,
            'description'   => $meta_desc,
            'publisher'     => [
                '@type'         => 'Organization',
                'name'          => $dis->config->name,
                'url'           => $base_url,
                'logo'          => $meta_image
            ],
            'url'           => $meta_url,
            'image'         => $meta_image
        ];
        $single->_schemas[] = $schema;
        
        // schema breadcrumbs
        $second_item = [
            '@type' => 'ListItem',
            'position' => 2,
            'item' => [
                '@id' => $base_url . '#post',
                'name' => 'Post'
            ]
        ];
        if(module_exists('post'))
            $second_item['item']['name'] = $dis->setting->post_index_meta_title;
        
        if($dis->setting->post_tag_index_enable){
            $second_item = [
                '@type' => 'ListItem',
                'position' => 2,
                'item' => [
                    '@id' => $dis->router->to('sitePostTag'),
                    'name' => $dis->setting->post_tag_index_meta_title
                ]
            ];
        }
        
        $schema = [
            '@context'  => 'http://schema.org',
            '@type'     => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'item' => [
                        '@id' => $base_url,
                        'name' => $dis->config->name
                    ]
                ],
                $second_item
            ]
        ];
        
        $single->_schemas[] = $schema;
        
        return $single;
    }
    
    static function single($tag){
        $dis = \Phun::$dispatcher;
        
        $base_url = $dis->router->to('siteHome');
        
        $meta_desc  = $tag->meta_description->safe;
        if(!$meta_desc)
            $meta_desc = $tag->about->chars(160);
        $meta_image = $base_url . 'theme/site/static/logo/500x500.png';
        $meta_url   = $tag->page;
        $meta_title = $tag->meta_title->safe;
        $meta_keys  = $tag->meta_keywords;
        if(!$meta_title)
            $meta_title = $tag->name->safe;
        
        $page = $dis->req->getQuery('page', 1);
        if($page && $page > 1){
            $meta_title = sprintf('Page %s %s', $page, $meta_title);
            $meta_desc  = sprintf('Page %s %s', $page, $meta_desc);
            $meta_url   = $meta_url . '?page=' . $page;
        }
        
        // metas
        $single = (object)[
            '_schemas' => [],
            '_metas'   => [
                'title'         => $meta_title,
                'canonical'     => $meta_url,
                'description'   => $meta_desc,
                'keywords'      => $meta_keys,
                'image'         => $meta_image,
                'type'          => 'website'
            ]
        ];
        
        // my rss feed?
        if(module_exists('robot'))
            $single->_metas['feed'] = $dis->router->to('sitePostTagSingleFeed', ['slug'=>$tag->slug]);
        
        // schemas 
        $schema = [
            '@context'      => 'http://schema.org',
            '@type'         => 'CollectionPage',
            'name'          => $tag->name,
            'description'   => $meta_desc,
            'publisher'     => [
                '@type'         => 'Organization',
                'name'          => $dis->config->name,
                'url'           => $base_url,
                'logo'          => $meta_image
            ],
            'url'           => $meta_url,
            'image'         => $meta_image
        ];
        $single->_schemas[] = $schema;
        
        // schema breadcrumbs
        $second_item = [
            '@type' => 'ListItem',
            'position' => 2,
            'item' => [
                '@id' => $base_url . '#post',
                'name' => 'Post'
            ]
        ];
        if(module_exists('post'))
            $second_item['item']['name'] = $dis->setting->post_index_meta_title;
        
        if($dis->setting->post_tag_index_enable){
            $second_item = [
                '@type' => 'ListItem',
                'position' => 2,
                'item' => [
                    '@id' => $dis->router->to('sitePostTag'),
                    'name' => $dis->setting->post_tag_index_meta_title
                ]
            ];
        }
        
        $schema = [
            '@context'  => 'http://schema.org',
            '@type'     => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'item' => [
                        '@id' => $base_url,
                        'name' => $dis->config->name
                    ]
                ],
                $second_item
            ]
        ];
        
        $single->_schemas[] = $schema;
        
        return $single;
    }
}