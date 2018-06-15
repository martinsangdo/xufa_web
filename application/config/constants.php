<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Ho_Chi_Minh');
/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*default*/
define('LIMIT_DEFAULT', 10);
define('GENDER_DEFAULT', 'male'); //male
define('LANGUAGE_DEFAULT', 'en');
define('LANGUAGE_ID_DEFAULT', 2); //english
define('LIMIT_RECORD_DEFAULT', 6);
define('SOCIAL_TYPE_DEFAULT', 'normal');
/*Rest API code*/
define('OK_MSG', 'OK');
define('SUCCESS_CODE', 200);
define('BAD_REQUEST_CODE', 400);
define('MISMATCH_PARAMS_MSG', 'MISMATCH_PARAMS');
define('WRONG_CAPTCHA_MSG', 'WRONG_CAPTCHA');
define('USER_EXISTED_MSG', 'USER_IS_EXISTED');
define('EMAIL_IS_EXISTED_MSG', 'EMAIL_IS_EXISTED');
define('USER_IS_NOT_EXISTED_MSG', 'USER_IS_NOT_EXISTED');
define('USER_IS_NOT_ACTIVATED_MSG', 'USER_IS_NOT_ACTIVATED');
define('USER_IS_BANNED_MSG', 'USER_IS_BANNED');
define('INVALID_USERNAME_OR_PASSWORD_MSG', 'INVALID_USERNAME_OR_PASSWORD');
define('INVALID_EXCEL_FILE_MSG', 'INVALID_EXCEL_FILE');
define('ERROR_FORMAT_DATA_RECORD_MSG', 'ERROR_FORMAT_DATA_RECORD');
define('INVALID_VOUCHER_MSG', 'INVALID_VOUCHER');
define('VOUCHER_IS_ALREADY_USED_MSG', 'VOUCHER_IS_ALREADY_USED');
define('INVALID_CURRENT_PASSWORD_MSG', 'INVALID_CURRENT_PASSWORD');
define('FILE_IS_NOT_EXISTED_MSG', 'FILE_IS_NOT_EXISTED');
define('UNAUTHORIZED_CODE', 401);
define('NOT_LOGIN_MSG', 'NOT_LOGIN');
define('FORBIDDEN_CODE', 403);
define('INVALID_TOKEN_MSG', 'INVALID_TOKEN');
define('NOT_FOUND_CODE', 404);
define('NOT_FOUND_MSG', 'NOT_FOUND');
define('METHOD_NOT_ALLOWRD_CODE', 405);
define('SERVER_ERROR_CODE', 500);
define('SERVER_ERROR_MSG', 'SERVER_ERROR');
define('LANGUAGE_NOT_FOUND_MSG', 'LANGUAGE_NOT_FOUND');
define('TRANSLATION_IS_EXISTED_MSG', 'TRANSLATION_IS_EXISTED');

define('SERVER_KEY', 'e296b7ae-4209-4ec6-9c72-686f0617893e');
define('STR_LOWERCASE', 'lowercase');
define('STR_UPPERCASE', 'uppercase');
define('CURRENT_MILLISECONDS', round(microtime(true) * 1000).'');
define('CURRENT_TIME', date('Y-m-d H:i:s'));
define('JWT_EXPIRE_TIME', time() + 60*60*24*7); // 7 days
define('ACTIVATED', 'activated');
define('NOT_ACTIVATED', 'not_activated');
define('BANNED', 'banned');
define('ARR_EMPTY', "''");
define('OBJ_EMPTY', '');
define('RECORD_ALIAS', 'sản phẩm');
define('RECORD_ALIAS_EN', 'record');
define('VIDEO_FILE_TYPE', 'video');
define('AUDIO_FILE_TYPE', 'audio');
define('IMAGE_FILE_TYPE', 'image');
define('EXCEL_FILE_TYPE', 'excel');
define('ORDER_STATUS_CANCEL', 'cancel');
define('TMP_FOLDER_AVATAR', 'public/upload/tmp/user/avatar');
define('FOLDER_IMG_UPLOAD', 'public/upload/img');
define('LOG_FOLDER', 'public/log');
define('FROM_EMAIL', 'standard@engma.com.vn');
define('TO_EMAIL', 'nhviet3393@gmail.com');


/* Admin */
define('APP_TITLE', 'easy market');
define('APP_TITLE_SHORT', 'ES');
define('RECORD_UPLOAD_PATH', './public/upload/file/record');
define('CATEGORY_UPLOAD_PATH', './public/upload/file/category');
define('RECORD_PATH', 'public/upload/img/record');
define('UPLOAD_ERROR', 'Upload thất bại, Vui lòng thử lại');
define('UPLOAD_WARNING', 'Đã gặp lỗi trong quá trình upload');
define('UPLOAD_SUCCESS', 'Đã upload dữ liệu thành công');
define('MAX_FILE_UPLOAD', 10);
define('MAX_IMAGE_SIZE', 2000);
define('MAX_VIDEO_SIZE', 10000);

define('LANGUAGE_ENGLISH', 'en');
define('LANGUAGE_VIETNAMESE', 'vi');
define('TRANSLATIONS_ALIAS', 'translations');
define('TRANSLATION_ALIAS_EN', 'translation');
define('UPLOAD_PATH', FCPATH);
define('SAMPLE_TRANSLATION_FILE', '/file/sample/translation.xlsx');

define('HEADER_PARAM_AUTHORIZATION', 'Authorization');
define('HEADER_PARAM_LANGUAGE', 'Language');
define('SESSION_ACCOUNT_ID', 'ACCOUNT_ID');