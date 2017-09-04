CREATE TABLE IF NOT EXISTS `post_tag` (
    `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user` INTEGER NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `about` TEXT,
    
    `meta_title` VARCHAR(100),
    `meta_description` TEXT,
    `meta_keywords` VARCHAR(200),
    
    `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `post_tag_chain` (
    `id` INTEGER NOT NULL AUTO_INCREMENT KEY,
    `user` INTEGER NOT NULL,
    `post` INTEGER NOT NULL,
    `post_tag` INTEGER NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX `by_post` ON `post_tag_chain` ( `post` );
CREATE INDEX `by_post_tag` ON `post_tag_chain` ( `post_tag` );

INSERT IGNORE INTO `site_param` ( `name`, `type`, `group`, `value` ) VALUES
    ( 'post_tag_index_enable', 4, 'Post Tag', '0' ),
    ( 'post_tag_mask_enable', 4, 'Post Tag', '0' ),
    ( 'post_tag_index_meta_title', 1, 'Post Tag', 'Post Tags' ),
    ( 'post_tag_index_meta_description',  5, 'Post Tag', 'List of post tags' ),
    ( 'post_tag_index_meta_keywords', 1, 'Post Tag', '' );
