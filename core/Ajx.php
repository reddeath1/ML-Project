<?php
/**
 * Created by PhpStorm.
 * User: reddeath
 * Date: 10/29/2018
 * Time: 9:24 PM
 */

define('ROOT',(preg_match('/localhost/',$_SERVER['SERVER_NAME'])) ? $_SERVER['DOCUMENT_ROOT'].'/ML Project': $_SERVER['DOCUMENT_ROOT']);

include_once (ROOT.'/database/DB.php');
class Ajx extends DB
{
    private $URL;


    public function  __construct()
    {
        parent::__construct();
        $this->processor();
    }

    private function url(){
        $page_url   = 'http';
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
            $page_url .= 's';
        }
        $page_url = $page_url.'://'.$_SERVER['SERVER_NAME'];

        return $page_url;
    }

    private function processor(){

        $this->URL = (preg_match('/localhost/',$this->url())) ? $this->url().'/ML Project' : $this->url();
        
        if(isset($_POST['action']) && !empty($_POST['action']) ||
            isset($_GET['action']) && !empty($_GET['action'])){

            $ac = ($this->get_requested_method() === "POST") ?
                $_POST['action'] : $_GET['action'];

            if(method_exists($this,$ac)){
                $this->$ac();
            }else{
                $this->error('0',"Error bad request");
            }

        }else{
            $this->error('0',"Error bad request");
        }
    }

    public function get_requested_method(){
        $method = $_SERVER['REQUEST_METHOD'];
        return $method;
    }

    private function signup(){
        if(isset($_POST['fn']) && !empty($_POST['fn']) &&
            isset($_POST['ln']) && !empty($_POST['ln']) &&
            isset($_POST['e']) && !empty($_POST['e']) &&
            isset($_POST['tel']) && !empty($_POST['tel']) &&
            isset($_POST['p']) && !empty($_POST['p'])
        ){

            $fn = (!empty($this->sanitize_username($_POST['fn']))) ?
                $this->sanitize_username($_POST['fn']) : $this->error('02',"First name is require!");

            $ln = (!empty($this->sanitize_username($_POST['ln']))) ?
                $this->sanitize_username($_POST['ln']) : $this->error('02','Last name is required!');

            $e = (!empty($this->sanitize_email($_POST['e']))) ?
                $this->sanitize_email($_POST['e']) : $this->error('02','Email is required!');

            $n = (!empty($this->sanitize_phone_number($_POST['tel']))) ?
                $this->sanitize_phone_number($_POST['tel']) : $this->error('02','Phone number is required');

            $p = (!empty($this->password_hash($_POST['p']))) ?
                $this->password_hash($_POST['p']) : $this->error('02','Password is required!');


            if(!empty($fn) && !empty($ln) && !empty($e) && !empty($n) && !empty($p)){
                $param = array('table'=>'users',
                    'data'=>
                        "
                        first_name = '$fn',
                        last_name = '$ln',
                        email = '$e',
                        phone_number = '$n',
                        password = '$p'
                        ");
               (strlen($this->insert(json_encode($param))) < 40) ?
                   $this->success("Registration successful!") :
                   $this->error('03',"Sorry something went wrong please try again!");
            }

        }else{
            $this->error('01',"All fields are required!");
        }
    }

    private function login(){
        if(isset($_POST['u']) && !empty($_POST['u']) &&
            isset($_POST['p']) && !empty($_POST['p'])){
            $u = (!empty($this->sanitize_post($_POST['u']))) ?
                $this->sanitize_post($_POST['u']) : $this->error('02',"First name is require!");

            $p = (!empty($this->password_hash($_POST['p']))) ?
                $this->password_hash($_POST['p']) : $this->error('02','Password is required!');


            if(!empty($u) && !empty($p)){
                $param = array('table'=>'users',
                    'cols'=>"id,CONCAT(first_name,' ',last_name) as username",'where'=>"email = '$u' AND password = '$p' LIMIT 1");

                /**
                 * get user
                 */
                $sql = $this->select(json_encode($param));
                if($sql->num_rows > 0){
                    while ($row = $sql->fetch_array(MYSQLI_ASSOC)){
                        $username = $row['username'];
                        $id = $row['id'];

                        /**
                         * Store user data in memory
                         */
                        session_start();
                        $_SESSION['ml_id'] = $id;
                        $_SESSION['ml_user'] = $username;

                        setcookie('ml_id',$id,strtotime("+30 days"),'/','','',TRUE);
                        setcookie('ml_user',$username,strtotime("+30 days"),'/','','',TRUE);

                        /**
                         * update user current time
                         */
                        $param = array('table'=>'users','data'=>"last_login = now()",
                            'where'=>"id = '$id'");
                        $this->update(json_encode($param));

                        $this->success("Login successful!");
                    }
                }else{
                    $this->error('2',"Login Failed!");
                }
            }

        }else{
            $this->error('2',"Login Failed!");
        }
    }

    private function saveAd(){
        if(isset($_POST['ad']) && !empty($_POST['ad']) &&
            isset($_POST['dim']) && !empty($_POST['dim'])){

            $ad = $this->filter_number($_POST['ad']);
            $dim = $this->filter_number($_POST['dim']);
            $u = $this->filter_number($_POST['u']);

            $param = array('table'=>'cart',
                'data'=>
                    "
                        user_id = '$u',
                        ad_id = '$ad',
                        dimension = '$dim'
                        ");
            if(strlen($this->insert(json_encode($param))) < 40) {
                $this->success("Ad successful saved!") ;
            }else{
                $this->error('03',"Sorry something went wrong please try again!");
            }

        }else{
            $this->error('00',"Sorry something went wrong please try again!");
        }

    }

    private function getPost($id = false){
        header("Content-type: text/event-stream");
        header("Connection: Keep-alive");
        header("Cache-Control: no-cache");

        echo "retry:3000\n";

        $ads = $this->_post();

        echo "data:".$this->response(array('success'=>$ads))."\n\n";
    }

    public function _post($id = false){
        $data = array();
        $id = ($id) ? "WHERE a.id = '$id'" : '';

        $param = array('query'=>
            "SELECT *,a.id as ad_id FROM ads as a 
              LEFT JOIN campaign as c ON(a.campaign_id = c.id) $id"
        );
        $sql = $this->query(json_encode($param));

        $result = '';
        $price = '';
        $dimension = '';
        $last_id = 0;
        $dims = '';
        if($sql->num_rows > 0){
            while ($row = $sql->fetch_array(MYSQLI_ASSOC)){
                $ad_id = $row['ad_id'];
                $data[] = $row;
            }

            foreach ($data as $datum) {
                $ad_id = $datum['ad_id'];
                $title = $datum['title'];
                $desc = $datum['desc_ad'];
                $format = $datum['format'];
                $type = $datum['type'];
                $media = $datum['media'];
                $campaign_id = $datum['campaign_id'];
                $campaign_name = $datum['name'];
                $last_id += $ad_id;

                /**
                 * get dimensions
                 */
                $param = array('query'=>"SELECT * FROM dimensions WHERE ad_id = '$ad_id'");
                $sql = $this->query(json_encode($param));
                while ($row = $sql->fetch_array(MYSQLI_ASSOC)){
                    $dim_id = $row['id'];
                    $card_rate = $row['card_rate'];
                    $discount_rate = $row['discount_rate'];
                    $di = $row['dimension'];

                    $price = "
                    <div class=\"col-lg-5\">
                                        <p>Card Rate</p>
                                        <span>$$card_rate</span>
                                    </div>
                                    <div class=\"col-lg-5\">
                                        <p>Offer Rate</p>
                                        <span>$$discount_rate</span>
                                    </div>
                    ";

                    $dimension .= "
                    <span class=\"di\">$di</span>
                    ";
                    $dims .= "<option value='$dim_id'>$di</option>";
                }

                $modal = "<div class=\"modal fade col-sm-12 pop$ad_id\" id=\"modalQuickView\"  tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"exampleModalLabel\"
     aria-hidden=\"true\">
    <div class=\"modal-dialog modal-lg\" role=\"document\">
        <div class=\"modal-content\">
            <div class=\"modal-body\">
                <div class=\"row\">
                    <table id=\"cart\" class=\"table table-hover table-condensed\">
                        <thead>
                        <tr>
                            <th style=\"width:40%\">Name</th>
                            <th style=\"width:15%\">Quantity</th>
                            <th style=\"width:15%\">Filters</th>
                            <th style=\"width:22%\" class=\"text-center\">Rates</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <div class=\"row\">
                                    <div class=\"col-sm-4 hidden-xs\"><img src=\"$this->URL/assets/images/$media\" alt=\"...\" class=\"img-responsive\"/></div>
                                    <div class=\"col-sm-7\">
                                        <h4 class=\"nomargin\">$title</h4>
                                        <p>$desc</p>
                                    </div>
                                </div>
                            </td>

                            <td class=\"col-sm-2\">
                                <span>Thousand Impression(s)</span>
                                <input type=\"number\" class=\"form-control text-center number$ad_id\" value=\"60\">
                            </td>

                            <td class=\"col-sm-2\">
                                <span >Dimension</span>
                                <select id=\"filter\" class=\"form-control filter$ad_id\" >
                                    $dims
                                </select>
                            </td>
                            <td  class=\"col-sm-6\">

                                <div class=\"row\">
                                    <div class=\"col-sm-6\">
                                        <span>Card Rate</span>
                                        <span>Discounted Rate</span>
                                        <span>Sub Total</span>
                                        <span>GST</span>
                                        <span>Total (Incl. Tax)</span>

                                    </div>
                                    <div class=\"col-sm-6\">
                                        <span style=\"text-decoration: line-through;\">Tsh $card_rate</span><br>
                                        <span>Tsh $discount_rate</span><br>
                                        <span>Tsh $discount_rate</span><br>
                                        <span>Tsh $discount_rate</span><br>
                                        <span>Tsh  $discount_rate</span>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr class=\"visible-xs\">
                            <td class=\"text-center\"><strong class='total'>Total $discount_rate</strong></td>
                        </tr>
                        </tfoot>
                    </table>
                        <!-- Add to Cart -->
                        <div class=\"card-body\">

                            <div class=\"text-center\">
                                <p class='status'></p>
                                <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Close</button>
                                <span>
                                    <button class=\"btn btn-success\" onclick=\"save('$ad_id',this)\">Save
                                        <i class=\"fa fa-cart-plus ml-2\" aria-hidden=\"true\"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <!-- /.Add to Cart -->
                    </div>
                </div>
            </div>
        </div>
    </div>";

               $result .= "<div class=\"col-md-3 col-sm-6 wr cont$ad_id\">
            <div class=\"product-grid4\">
                <div class=\"product-image4\">
                    <a href=\"#\">
                        <img class=\"pic-1\" src=\"$this->URL/assets/images/$media\">
                        <img class=\"pic-2\" src=\"$this->URL/assets/images/$media\">
                    </a>
                </div>
                <div class=\"product-content\">
                    <div class=\"row form-group\">
                        <div class=\"col-xs-12\">
                            <ul class=\"nav nav-pills nav-justified thumbnail setup-panel $ad_id\">
                                <li class=\"active\"><a href=\"#step-1\">
                                        RATE
                                    </a></li>
                                <li class=\"\"><a href=\"#step-2\">
                                        INFO
                                    </a></li>
                                <li class=\"\"><a href=\"#step-3\">
                                        STEP
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                    <div class=\"setup-content\" id=\"step-1\">
                        <div >
                            <h3 class=\"title\">$title</h3>
                            <div class=\"price\">
                                <div class=\"row\">
                                    $price
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=\"setup-content\" id=\"step-2\">
                        <div>
                            <p style=\"text-align: center\">
                                $desc
                            </p>
                        </div>
                    </div>
                    <div class=\"setup-content\" id=\"step-3\">
                        <div>
                            <p>Creative Format : .$format<br>

                                Dimension(s)</p>

                            $dimension
                        </div>
                    </div>

                    <a class=\"add-to-cart $ad_id\">View Detailed Pricing</a>
                </div>
            </div>
        </div>
        
        $modal
        ";
            }

            $data = array('result'=>$result,'lastEventId'=>$last_id,'url'=>$this->url());
        }else{
            $data = false;
        }

        return $data;

    }

    public function response($r){
        return json_encode($r);
    }

    public function sanitize_username($name){
        return preg_replace("#[^a-z0-9-_. ]#i",'',$name);
    }

    public function sanitize_email($email){
        return preg_replace("#[^a-z0-9-_.@]#i",'',$email);
    }

    public function password_hash($pass){
        return sha1(sha1($pass));
    }

    public function sanitize_phone_number($n){
        return preg_replace("#[^0-9+-]#i",'',$n);
    }

    public function filter_number($n){
        return preg_replace("#[^0-9]#",'',$n);
    }

    public function sanitize_post($post){
        $post = htmlentities($post);
        $post = strip_tags($post);
        $post = stripslashes($post);
        return $post;
    }

    private function error($code,$error){
        echo json_encode(array('error'=>$error,'code'=>$code));
    }

    private function success($success){
        echo json_encode(array('success'=>$success));
    }
}

new Ajx();