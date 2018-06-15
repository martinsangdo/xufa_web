<?php
/*
 * Parent controller
 */
date_default_timezone_set('Asia/Ho_Chi_Minh');

Class MY_Controller extends CI_Controller
{
    //send data to view
    public $data = array();

    function __construct()
    {
        //inherit from IC_Controller
        parent::__construct();
        $controller = $this->uri->segment(1);
        //check permission to access files/API (logined or not)
        switch ($controller){
//            case ADMIN_BOOK_CONTROLLER_NAME:
            case ADMIN_ARTICLE_CONTROLLER_NAME:
            case ADMIN_CONTROLLER_NAME:
            {
                //someone using Admin pages
                $this->load->model('admin_model');
                $admin_id = $this->get_login_user_id();

                if (empty($admin_id)){
                    if ($this->uri->segment(2) != 'login' &&
                        $this->uri->segment(2) != 'read_new_captcha' &&
                        $this->uri->segment(2) != 'check_login'){
                        redirect(base_url(ADMIN_CONTROLLER_NAME.'/login')); //not allow
                    }
                } else {
                    $role = $this->get_login_user_role();       //only Admin has role
                    if (empty($role)){
                        redirect(base_url(ADMIN_CONTROLLER_NAME.'/login')); //not allow
                    }
                }
                break;
            }

            case API_CONTROLLER_NAME:
            {
                $login_id = $this->get_login_user_id();
                //someone requests API to get data (required login)
                if (empty($login_id)){
                    return FALSE;
                }
                break;
            }
            default:
            {

            }
        }
        //models
        $this->load->helper('url');
    }
    //force user back to admin login page
    protected function redirect_admin_login(){
        $func = $this->uri->segment(2);
        if ($func != 'login'){
            redirect(base_url(ADMIN_CONTROLLER_NAME.'/login'));
        }
    }
    //get logined user id from session
    protected function get_login_user_id(){
        return $this->session->userdata(SESS_KEY_USER_ID);
    }

    //get logined user id from session
    protected function set_login_user_id($user_id){
        $this->session->set_userdata(SESS_KEY_USER_ID, $user_id);
    }
    //
    protected function get_login_user_role(){
        return $this->session->userdata(SESS_KEY_USER_ROLE);
    }
    protected function set_login_user_role($role){
        $this->session->set_userdata(SESS_KEY_USER_ROLE, $role);
    }
    //
    protected function get_login_user_name(){
        return $this->session->userdata(SESS_KEY_USER_NAME);
    }
    protected function set_login_user_name($name){
        $this->session->set_userdata(SESS_KEY_USER_NAME, $name);
    }
    //
    protected function get_captcha(){
        return $this->session->userdata(SESS_KEY_CAPTCHA);
    }

    protected function set_captcha($str_captcha){
        $this->session->set_userdata(SESS_KEY_CAPTCHA, $str_captcha);
    }
    //
    /**
     * convert array to JSON format
     * @param unknown $array
     */
    protected function responseJsonData($array){
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        return json_encode($array);
    }
    /**
     * convert array to Query string, each term joins by "&"
     */
    protected function convertArray2QueryString($fields){
        $fields_string = '';
        foreach($fields as $key=>$value) {
            $fields_string .= $key.'='.$value.'&';
        }
        $fields_string = rtrim($fields_string,'&');
        return $fields_string;
    }
    /**
     * generate new Captcha image
     * return: HTML of <img/>
     */
    protected function generateCaptchaImageTag(){
        if (!is_dir('captcha')) {
            mkdir('./captcha', 0777, TRUE);
        }
        $this->load->helper('captcha');
        $random = rand(1001, 9999);     //random number is in this range
        $vals = array(
            'word'	=> $random,		//random number
            'img_path' 	=> './'.CAPTCHA_FOLDER,
            'img_url' 	=> base_url() . CAPTCHA_FOLDER,
            'img_width'	=> CAPTCHA_W,
            'img_height' 	=> CAPTCHA_H,
            'expiration' 	=> CAPTCHA_EXP_DURATION,
            'font_size' 	=> CAPTCHA_FONT_SIZE,
            'font_path' => FCPATH. 'captcha/font/OpenSans-Bold.ttf',
        );

        $cap = create_captcha($vals);
        //save into database
        $data = array(
            'captcha_time'	=> $cap['time'],
            'ip_address'	=> $this->input->ip_address(),
            'word'	=> $cap['word']
        );

        $query = $this->db->insert_string('captcha', $data);
        $this->db->query($query);

        //generate captcha session
        $session_userdata = array(
            'CAPTCHA_EXPIRE_TIME' => $cap['time'],
            'CAPTCHA_CODE' => $cap['word']
        );
        $this->session->set_userdata($session_userdata);

        return $cap['image'];
    }
    /**
     * validate captcha word
     * @param unknown $word
     * @param unknown $ip
     */
    protected function isValidCaptcha($word, $ip = ''){
        // First, delete old captchas

        $expiration = time() - CAPTCHA_EXP_DURATION;
        $this->db->query("DELETE FROM captcha WHERE captcha_time < ".$expiration);

        if(!empty($ip)) {
            // Then see if a captcha exists:
            $sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
            $binds = array($word, $ip, $expiration);
        }
        else{ // for API
            // Then see if a captcha exists:
            $sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND captcha_time > ?";
            $binds = array($word, $expiration);
        }
        $query = $this->db->query($sql, $binds);
        $row = $query->row();

        return ($row->count > 0);
    }

    /**
     * validate captcha word
     * @param unknown $word
     * @param unknown $ip
     */
    protected function isValidCaptchaSession($word){
        // First, delete old captchas

        $expiration = time() - CAPTCHA_EXP_DURATION;
        $captcha_expired = $this->session->userdata['CAPTCHA_EXPIRE_TIME'];
        $captcha_code = $this->session->userdata['CAPTCHA_CODE'];

        if($captcha_code == $word && $captcha_expired > $expiration){
            return 1;
        }

        return 0;
    }

    /**
     * sending email
     * @param unknown $from
     * @param unknown $from_title
     * @param unknown $to
     * @param unknown $cc
     * @param unknown $bcc
     * @param unknown $subject
     * @param unknown $message
     * @param unknown $attach
     * @param unknown $reply_to
     */
    protected function sendMail_wrapper($from, $from_title, $to, $cc, $bcc, $subject, $message, $attach, $reply_to = null){
        $this->load->library('email');

        $this->email->from($from, $from_title);
        $this->email->to($to);
        if (isset($cc) && $cc != '')
            $this->email->cc($cc);
        if (isset($bcc) && $bcc != '')
            $this->email->bcc($bcc);
        if (isset($reply_to))
            $this->email->reply_to($reply_to, 'Reply To');
        else
            $this->email->reply_to(INFO_MAIL, 'Admin');

        $this->email->subject($subject);

        //html format + hidden fake timestamp (make each email unique, prevent spam filter engine)
        $message = '<html><head></head><body>'.$message.'<br/><br/><div style="display:none;">'.date('Y-m-d H:i:s').rand(0,10000).'</div></body></html>';
        $this->email->message($message);

        if (isset($attach) && $attach != '')
            $this->email->attach($attach);

        $this->email->send();
//     							echo $this->email->print_debugger();
    }

    /**
     * generate new Captcha image
     * return: HTML of <img/>
     */
    protected function generateCaptchaBase64(){
        $this->load->helper('captcha');
        $random = rand(1001, 9999);     //random number is in this range
        $vals = array(
            'word'	=> $random,		//random number
            'img_path' 	=> './'.CAPTCHA_FOLDER,
            'img_url' 	=> base_url() . CAPTCHA_FOLDER,
            'img_width'	=> CAPTCHA_W,
            'img_height' 	=> CAPTCHA_H,
            'expiration' 	=> CAPTCHA_EXP_DURATION,
            'font_size' 	=> CAPTCHA_FONT_SIZE,
            'font_path' => FCPATH. 'captcha/font/OpenSans-Bold.ttf',
        );

        $cap = create_captcha($vals);
        //save into database
        $data = array(
            'captcha_time'	=> $cap['time'],
            'ip_address'	=> $this->input->ip_address(),
            'word'	=> $cap['word']
        );

        //store session
        $session_userdata = [
            'CAPTCHA_EXPIRE_TIME' => $cap['time'],
            'CAPTCHA_CODE' => $cap['word']
        ];
        $this->session->set_userdata($session_userdata);

        $query = $this->db->insert_string('captcha', $data);
        $this->db->query($query);

//        dump($vals['img_url'].$cap['filename']);

        $image = imagecreatefromjpeg($vals['img_url'].$cap['filename']);

// Add some filters
        imagefilter($image, IMG_FILTER_PIXELATE, 1, true);
        imagefilter($image, IMG_FILTER_MEAN_REMOVAL);

        ob_start(); // Let's start output buffering.
        imagejpeg($image); //This will normally output the image, but because of ob_start(), it won't.
        $contents = ob_get_contents(); //Instead, output above is saved to $contents
        ob_end_clean(); //End the output buffer.

        return base64_encode($contents);
    }

    /**
     * send a POST request
     */
    protected function sendPost($fields, $url){
        $fields_string = $this->convertArray2QueryString($fields);
        //header with content_type api key
        //create SID
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //comment out belows options if request by GET method
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HEADER, 1);	//to get 501 code
        //
        $result = curl_exec($ch);	//full result
        if ($result === FALSE) {
            die('Send Error: ' . curl_error($ch));
        }
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($result, 0, $header_size);
        $body = substr($result, $header_size);

        $info = curl_getinfo($ch);		//get header info

        curl_close($ch);

        if ($info["http_code"] != 200){		//invalid request, must hide information from Cloudbric
            //parse header
            $header_arr = $this->get_headers_from_curl_response($header);

            if (isset($header_arr[0]) && isset($header_arr[0]['Cb-Error'])){
                return array(
                    'header' => 'Cb-Error:'.$header_arr[0]['Cb-Error'],
                    'body'	=> $info
                );
            } else {
                return array(
                    'header' => 'Cb-Error:'.UNKNOWN_ERROR,
                    'body'	=> $header
                );
            }
        } else {	//request ok
            return array(
                'body'	=> json_decode($body)	//real json data, if any
            );
        }
    }

//<iframe class="home4-center-cropped center-cropped-fill"
//src="/notfound/iframe_img">
//</iframe>
    /**
     * send a GET request
     */
    protected function sendGet($url){
        //  Initiate curl
        $ch = curl_init();
// Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);        //able to get header
// Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        $headers = array(
            'Referer: https://btcmanager.com/wp-content/uploads/2018/04/Singaporean-Resident-to-be-Caned-12-strokes-and-Jailed-in-Connection-with-Bitcoin-Related-Robbery-768x458.jpg', //Your referrer address
            'REQUEST_URI: https://btcmanager.com/wp-content/uploads/2018/04/Singaporean-Resident-to-be-Caned-12-strokes-and-Jailed-in-Connection-with-Bitcoin-Related-Robbery-768x458.jpg', //Your referrer address
            'REDIRECT_QUERY_STRING: https://btcmanager.com/wp-content/uploads/2018/04/Singaporean-Resident-to-be-Caned-12-strokes-and-Jailed-in-Connection-with-Bitcoin-Related-Robbery-768x458.jpg', //Your referrer address
            'REDIRECT_URL: https://btcmanager.com/wp-content/uploads/2018/04/Singaporean-Resident-to-be-Caned-12-strokes-and-Jailed-in-Connection-with-Bitcoin-Related-Robbery-768x458.jpg', //Your referrer address
        );
        $headers = array(
            'Referer'=>' https://btcmanager.com/wp-content/uploads/2018/04/Singaporean-Resident-to-be-Caned-12-strokes-and-Jailed-in-Connection-with-Bitcoin-Related-Robbery-768x458.jpg', //Your referrer address
            'REQUEST_URI'=>' https://btcmanager.com/wp-content/uploads/2018/04/Singaporean-Resident-to-be-Caned-12-strokes-and-Jailed-in-Connection-with-Bitcoin-Related-Robbery-768x458.jpg', //Your referrer address
            'REDIRECT_QUERY_STRING'=>' https://btcmanager.com/wp-content/uploads/2018/04/Singaporean-Resident-to-be-Caned-12-strokes-and-Jailed-in-Connection-with-Bitcoin-Related-Robbery-768x458.jpg', //Your referrer address
            'REDIRECT_URL'=>' https://btcmanager.com/wp-content/uploads/2018/04/Singaporean-Resident-to-be-Caned-12-strokes-and-Jailed-in-Connection-with-Bitcoin-Related-Robbery-768x458.jpg', //Your referrer address
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// Execute
        $result=curl_exec($ch);
        //
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($result, 0, $header_size);
        $body = substr($result, $header_size);
        //parse header
        $header_arr = $this->get_headers_from_curl_response($header);
// Closing
        curl_close($ch);

// Will dump a beauty json :3
        return array(
            'data'=> $body,
            'header' => $header_arr
        );
    }

    /**
     * send a GET request without getting Header
     */
    protected function sendGetWithoutHeader($url){
        //  Initiate curl
        $ch = curl_init();
// Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
// Execute
        $result=curl_exec($ch);
// Closing
        curl_close($ch);

// Will dump a beauty json :3
        return json_decode($result, true);
    }

    /**
     *
     * @param unknown $headerContent
     * @return Ambigous <multitype:, unknown>
     */
    private function get_headers_from_curl_response($headerContent){
        $headers = array();

        // Split the string on every "double" new line.
        $arrRequests = explode("\r\n\r\n", $headerContent);

        // Loop of response headers. The "count() -1" is to
        //avoid an empty row for the extra line break before the body of the response.
        for ($index = 0; $index < count($arrRequests) -1; $index++) {

            foreach (explode("\r\n", $arrRequests[$index]) as $i => $line)
            {
                if ($i === 0)
                    $headers[$index]['http_code'] = $line;
                else
                {
                    list ($key, $value) = explode(': ', $line);
                    $headers[$index][$key] = $value;
                }
            }
        }

        return $headers;
    }

    /*
     * function upsert tb push_token
     * params: data
     */
    protected function upsertPushToken($data){
        $account_id = $data['account_id'];
        $device_id = $data['device_id'];
        $device_name = $data['device_name'];
        $device_version = $data['device_version'];
        $firebase_token = $data['firebase_token'];
        $apple_token = $data['apple_token'];
        $timezone = $data['timezone'];

        if(empty($device_id) || (empty($firebase_token) && empty($apple_token))){
            return;
        }

        $push_token_exists = $this->push_token_model->findOne(['device_id' => $device_id]);

        if(!empty($push_token_exists)){ //update
            $this->push_token_model->update_by_condition([
                '_id' => $push_token_exists->_id
            ], [
                'account_id' => $account_id,
                'device_name' => $device_name,
                'device_version' => $device_version,
                'firebase_token' => $firebase_token,
                'apple_token' => $apple_token,
                'timezone' => $timezone,
                'update_time' => CURRENT_TIME,
            ]);
        }
        else{ //insert new
            $this->push_token_model->create([
                'account_id' => $account_id,
                'device_id' => $device_id,
                'device_name' => $device_name,
                'device_version' => $device_version,
                'firebase_token' => $firebase_token,
                'apple_token' => $apple_token,
                'timezone' => $timezone,
                'create_time' => CURRENT_TIME,
                'update_time' => CURRENT_TIME,
            ]);
        }
    }

    /*
     * return errors when import excel file
     */
    protected function returnErrorImport($data, $errList, $filename, $extention)
    {
        $this->load->library('excel');
        $extention_ = scan_file_type($extention);
        $excel_factor = PHPExcel_IOFactory::createReader($extention_);
        $objPHPExcel = $excel_factor->load($filename);
//        $objPHPExcel->setLoadAllSheets();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('P2', 'Error')->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getStyle('P2')->getFill()->applyFromArray(color_error());
        $objPHPExcel->getActiveSheet()->getStyle('P2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('P2')->applyFromArray(arr_all_border());

        foreach ($errList as $key => $row) {
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $key, $row)->getColumnDimension('P')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getStyle('P' . $key)->getFont()->getColor()->setRGB('6F6F6F');;
            $objPHPExcel->getActiveSheet()->getStyle('P' . $key)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('P' . $key)->applyFromArray(arr_all_border());
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $extention_);
        $objWriter->save('export/excel/import_error_' . date('Ymd') . '.xls');
        header("location: " . base_url() . "export/excel/import_error_" . date('Ymd') . ".xls");
        unlink(base_url() . 'export/excel/import_error_' . date('Ymd') . '.xls');
    }

    protected function checkVerifyJWT($jwt, $account_id){
        /* use for CI */
        if (!(strpos($jwt, 'Bearer') !== false)) {
            return false;
        }
        /* end use for CI */
        $jwt = str_replace('Bearer ', '', $jwt);
        //check token
        $where = [
            'jwt' => $jwt,
            'expire_time >' => time(),
        ];
        $account_jwt = $this->account_jwt_model->findOne($where);
        if(empty($account_jwt)){
            return false;
        }

        $jwt_generator = new JWT();
        $jwt_decode = $jwt_generator::decode($jwt, SERVER_KEY);
        if($jwt_decode->account_id != $account_id){
            return false;
        }

        return true;
    }

    /*
     * send email
     */
    protected function sendEmail($from_email, $to_email, $subject, $message)
    {
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'engma.com.vn',
            'smtp_port' => 25,
            'smtp_user' => 'standard@engma.com.vn',
            'smtp_pass' => '?Pid6o71',
            'mailtype' => 'html',
            'newline' => "\r\n",
//            'charset' => 'iso-8859-1',
            'charset' => 'utf-8',
            'validation' => TRUE,
            'wordwrap' => TRUE
        );


        $this->load->library('email', $config);
//        $this->email->set_newline("\r\n");
        $this->email->from($from_email);
        $this->email->to($to_email);
//        $this->email->bcc(BCC_EMAIL);
        $this->email->subject($subject);
//        $this->email->attach($attach_file);
        $this->email->message($message);
        if($this->email->send())
        {
//            echo 'Email send.';
        }
        else
        {
            show_error($this->email->print_debugger());
        }

    }

    /**
     * validate captcha code for API
     * @param unknown $word
     * @param unknown $ip
     */
    protected function checkCaptcha($code){
        $captcha_expire_time = $this->session->userdata('CAPTCHA_EXPIRE_TIME');
        $captcha_code = $this->session->userdata('CAPTCHA_CODE');
        $expiration = time() - CAPTCHA_EXP_DURATION;

        if($code == $captcha_code && $captcha_expire_time > $expiration){
            return true;
        }
        else{
            return false;
        }
    }

    /*
     * write log file
     */
    protected function writeLog($url, $txt){
        if (!is_dir(LOG_FOLDER)) { //create log folder
            mkdir('./' . LOG_FOLDER, 0777, TRUE);
        }

        $log_txt = "======================\r\n";
        $log_txt .= "======================\r\n";
        $log_txt .= 'Time: '. date('Y-m-d H:i:s') ." \r\n";
        $log_txt .= 'URL: '. $url ." \r\n";
        $log_txt .= $txt;
        $log_txt .= "\r\n\r\n\r\n";

        $log_file = LOG_FOLDER .'/'. date('Ymd') .'_log_'. $_SERVER['SERVER_NAME'] .'.txt';
        $log_file_path = UPLOAD_PATH. $log_file;

        if(file_exists($log_file_path))
        {
            write_file($log_file_path, $log_txt, 'a');
        }
        else
        {
            write_file($log_file_path, $log_txt);
        }
    }

    protected function checkSessionAndTokenAuth(){
        /*check session & jwt*/
        $account_id = $this->session->userdata(SESSION_ACCOUNT_ID);
        if (empty($account_id)) {
            $this->response(RestForbidden(NOT_LOGIN_MSG), FORBIDDEN_CODE);
        }
        $jwt = !empty($this->input->request_headers()[HEADER_PARAM_AUTHORIZATION]) ? $this->input->request_headers()[HEADER_PARAM_AUTHORIZATION] : '';
        if (!$this->checkVerifyJWT($jwt, $account_id)) {
            $this->response(RestForbidden(INVALID_TOKEN_MSG."---".$jwt), FORBIDDEN_CODE);
        }
        return $account_id;
        /*end check session & jwt*/

    }

    protected function checkSessionAuth(){
        /*check session*/
        $account_id = $this->session->userdata(SESSION_ACCOUNT_ID);
        if(empty($account_id)){
            $this->response(RestForbidden(NOT_LOGIN_MSG), FORBIDDEN_CODE);
        }
        return $account_id;
        /*end check session*/
    }


    protected function checkApplicationTokenAuth()
    {
        /*check jwt*/
        $jwt = !empty($this->input->request_headers()[HEADER_PARAM_AUTHORIZATION]) ? $this->input->request_headers()[HEADER_PARAM_AUTHORIZATION] : '';
        $check_jwt = $this->checkJWT($jwt);
//        dump($check_jwt);

        if(empty($check_jwt)){
            $this->response(RestForbidden(INVALID_TOKEN_MSG."---".$jwt), FORBIDDEN_CODE);
        }
        return $check_jwt->account_id;
        /*end check jwt*/
    }

    /**
     * check jwt
     */
    protected function checkJWT($jwt){
        /* use for CI */
        if (!(strpos($jwt, 'Bearer') !== false)) {
            return false;
        }
        /* end use for CI */
        $jwt = str_replace('Bearer ', '', $jwt);
        //check token
        $where = [
            'jwt' => $jwt
        ];
        $account_jwt = $this->account_jwt_model->findOne($where);
        if(empty($account_jwt)){
            return false;
        }


        if($account_jwt->expire_time < time()){ //expire_time
            return false;
        }

        $jwt_generator = new JWT();
        $jwt_decode = $jwt_generator::decode($jwt, SERVER_KEY);

        return $jwt_decode;
    }

    /**
     * send Push notification wrapper
     */
    protected function sendPushNotificationFirebase($server_key, $target, $data, $notification){
        //FCM api URL
        $url = 'https://fcm.googleapis.com/fcm/send';
        //api_key available in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $fields = array(	//package to send from Firebase
            'priority'=> 'high',
            'delay_while_idle'=> true,
            'content_available' => true
        );
        if (isset($data)){
            $fields['data'] = $data;
        }
        if (isset($notification)){		//iOS case
            $fields['notification'] = $notification;
        }
        //send to which device(s)
//    if(is_array($target)){
//        $fields['registration_ids'] = $target;
//    }else{		//single device
        $fields['to'] = $target;
//    }
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$server_key
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
    //create pagination links
    protected function create_pagination($url, $total, $per_page, $uri_segment){
        $this->load->library('pagination');
        $config = array(
            'base_url' => $url,
            'total_rows' => $total,
            'per_page' => $per_page,
            'num_links' => 3,
            'uri_segment' => $uri_segment, //segment of offset
            'full_tag_open' => '<ul class="list-inline text-center mb-0">',
            'full_tag_close' => '</ul>',
            'first_tag_open' => '<li class="list-inline-item g-hidden-xs-down">',
            'first_tag_close' => '</li>',
            'num_tag_open' => '<li class="list-inline-item g-hidden-xs-down">',
            'num_tag_close' => '</li>',
            'attributes' => array('class' => 'u-pagination-v1__item g-width-30 g-height-30 g-brd-transparent g-brd-primary--hover g-brd-primary--active g-color-secondary-dark-v1 g-bg-primary--active g-font-size-12 rounded g-pa-5'),
            'cur_tag_open' => '<li class="list-inline-item g-hidden-xs-down bold">',
            'cur_tag_close' => '</li>',
            'first_link' => false,
            'last_link' => false
        );

        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }
}