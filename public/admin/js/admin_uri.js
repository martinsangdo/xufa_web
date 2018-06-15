/*
    API uri of Admin functions
 */
var ADMIN_CONTROLLER = '/admin_1/';
var ADMIN_BOOK_CONTROLLER_NAME = '/admin-book/';

 var ADMIN_API_URI = {
     //login
     READ_NEW_CAPTCHA: ADMIN_CONTROLLER+ 'read_new_captcha',
     LOGIN: ADMIN_CONTROLLER+ 'check_login',
     LOGOUT: ADMIN_CONTROLLER+ 'logout',
     //book
     GENERATE_SPECIFIC_CODE: ADMIN_BOOK_CONTROLLER_NAME+'generate_specific_code',

 };