<?php
//========== Define common variables of whole system
define('ADMIN_CONTROLLER_NAME', 'admin_1');		//controller name in URL of Admin
define('ADMIN_ARTICLE_CONTROLLER_NAME', 'admin-article');
define('ADMIN_BOOK_CONTROLLER_NAME', 'admin-book');
define('API_CONTROLLER_NAME', 'api');		    //controller name in URL of API which need login to process
define('PUBLIC_CONTROLLER_NAME', 'public');	//controller name in URL of API which don't need login to process

//========== Session keys
define('SESS_KEY_USER_ID', 'sess_user_id');     //used in Admin or front-end user
define('SESS_KEY_USER_NAME', 'sess_user_name');
define('SESS_KEY_USER_ROLE', 'sess_user_role');     //only Admin has role

define('SESS_KEY_CAPTCHA', 'sess_captcha');     //save captcha string

//========== captcha
define('CAPTCHA_FOLDER', 'captcha/');
define('CAPTCHA_W', 100);
define('CAPTCHA_H', 40);
define('CAPTCHA_EXP_DURATION', 300);    //5 minutes
define('CAPTCHA_FONT_SIZE', 100);

define('UNKNOWN_ERROR', 'unknown_error');
//block key, display in Home
define('DEFAULT_PAGE_LEN', 20); //used in paging
define('BLOCK_KEY_1', 'block_key_1');
define('BLOCK_KEY_2', 'block_key_2');
define('BLOCK_KEY_3', 'block_key_3');
define('BLOCK_KEY_4', 'block_key_4');
define('BLOCK_KEY_5', 'block_key_5');
define('BLOCK_KEY_6', 'block_key_6');
define('BLOCK_KEY_7', 'block_key_7');
define('BLOCK_KEY_8', 'block_key_8');
define('BLOCK_KEY_9', 'block_key_9');
define('BLOCK_KEY_10', 'block_key_10');
define('BLOCK_KEY_11', 'block_key_11');
define('BLOCK_KEY_12', 'block_key_12');
define('BLOCK_KEY_13', 'block_key_13');
define('BLOCK_KEY_14', 'block_key_14');
define('BLOCK_KEY_15', 'block_key_15');
define('BLOCK_KEY_16', 'block_key_16');
define('BLOCK_KEY_17', 'block_key_17');
define('BLOCK_KEY_18', 'block_key_18');
define('BLOCK_KEY_19', 'block_key_19');
define('BLOCK_KEY_20', 'block_key_20');
define('BLOCK_KEY_21', 'block_key_21');
define('BLOCK_KEY_22', 'block_key_22');
define('BLOCK_KEY_23', 'block_key_23');
define('BLOCK_KEY_24', 'block_key_24');
define('BLOCK_KEY_SIDE_PREFIX', 'block_key_');
//site type in DB
define('WORDPRESS_TYPE', 'wp');
define('RSS_TYPE', 'rss');
//
define('RELATED_POST_NUM', 6);      //number of related posts
define('CAT_POST_NUM', 30);      //number of posts to show in 1 category
define('VIDEO_PAGE_NUM', 20);      //number of videos to show in 1 page
define('DETAIL_EXCERPT_LIMIT', 300);      //number of videos to show in 1 page