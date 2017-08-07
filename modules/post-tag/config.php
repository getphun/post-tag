<?php
/**
 * post-tag config file
 * @package post-tag
 * @version 0.0.1
 * @upgrade true
 */

return [
    '__name' => 'post-tag',
    '__version' => '0.0.1',
    '__git' => 'https://github.com/getphun/post-tag',
    '__files' => [
        'modules/post-tag/config.php'   => [ 'install', 'remove', 'update' ],
        'modules/post-tag/_db'          => [ 'install', 'remove', 'update' ],
        'modules/post-tag/model'        => [ 'install', 'remove', 'update' ],
        'modules/post-tag/library'      => [ 'install', 'remove', 'update' ],
        'modules/post-tag/meta'         => [ 'install', 'remove', 'update' ],
        'modules/post-tag/event'        => [ 'install', 'remove' ],
        'modules/post-tag/controller/RobotController.php' => [ 'install', 'remove', 'update' ],
        'modules/post-tag/controller/TagController.php' => [ 'install', 'remove' ],
        'theme/site/post/tag'           => [ 'install', 'remove' ]
    ],
    '__dependencies' => [
        'site-param',
        'formatter',
        'site',
        'site-meta',
        '/db-mysql',
        '/robot'
    ],
    '_services' => [],
    '_autoload' => [
        'classes' => [
            'PostTag\\Model\\PostTag'       => 'modules/post-tag/model/PostTag.php',
            'PostTag\\Model\\PostTagChain'  => 'modules/post-tag/model/PostTagChain.php',
            'PostTag\\Library\\Robot'       => 'modules/post-tag/library/Robot.php',
            'PostTag\\Meta\\Tag'            => 'modules/post-tag/meta/Tag.php',
            'PostTag\\Controller\\RobotController'  => 'modules/post-tag/controller/RobotController.php',
            'PostTag\\Controller\\TagController'    => 'modules/post-tag/controller/TagController.php',
            'PostTag\\Event\\TagEvent'      => 'modules/post-tag/event/TagEvent.php'
        ],
        'files' => []
    ],
    '_routes' => [
        'site' => [
            'sitePostTagFeed' => [
                'rule' => '/post/tag/feed.xml',
                'handler' => 'PostTag\\Controller\\Robot::feed'
            ],
            'sitePostTag' => [
                'rule' => '/post/tag',
                'handler' => 'PostTag\\Controller\\Tag::index'
            ],
            
            'sitePostTagSingleFeed' => [
                'rule' => '/post/tag/:slug/feed.xml',
                'handler' => 'PostTag\\Controller\\Robot::feedSingle'
            ],
            'sitePostTagSingle' => [
                'rule' => '/post/tag/:slug',
                'handler' => 'PostTag\\Controller\\Tag::single'
            ]
        ]
    ],
    'events' => [
        'post-tag:created' => [
            'post-tag' => 'PostTag\\Event\\TagEvent::created'
        ],
        'post-tag:updated' => [
            'post-tag' => 'PostTag\\Event\\TagEvent::updated'
        ],
        'post-tag:deleted' => [
            'post-tag' => 'PostTag\\Event\\TagEvent::deleted'
        ]
    ],
    'formatter' => [
        'post-tag' => [
            'name' => 'text',
            'about' => 'text',
            'updated' => 'date',
            'created' => 'date',
            'user' => [
                'type' => 'object',
                'model' => 'User\\Model\\User'
            ],
            'page' => [
                'type' => 'router',
                'params' => [
                    'for' => 'sitePostTagSingle'
                ]
            ],
            'meta_title' => 'text',
            'meta_description' => 'text'
        ],
        'post' => [
            'tag' => [
                'type' => 'chain',
                'model' => 'PostTag\\Model\\PostTag',
                'chain' => [
                    'model' => 'PostTag\\Model\\PostTagChain',
                    'object' => 'post',
                    'parent' => 'post_tag'
                ],
                'format' => 'post-tag'
            ]
        ]
    ],
    'robot' => [
        'sitemap' => [
            'post-tag' => 'PostTag\\Library\\Robot::sitemap'
        ],
        'feed' => [
            'post-tag' => 'PostTag\\Library\\Robot::feed'
        ]
    ]
];