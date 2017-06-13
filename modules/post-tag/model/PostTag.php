<?php
/**
 * post_tag model
 * @package post-tag
 * @version 0.0.1
 * @upgrade true
 */

namespace PostTag\Model;

class PostTag extends \Model
{
    public $table = 'post_tag';
    public $q_field = 'name';
}