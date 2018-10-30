<?php
/**
 * Created by PhpStorm.
 * User: reddeath
 * Date: 10/28/2018
 * Time: 3:03 AM
 */
//define('ROOT',(preg_match('/localhost/',$_SERVER['SERVER_NAME'])) ? $_SERVER['DOCUMENT_ROOT'].'/ML Projec': $_SERVER['DOCUMENT_ROOT']);

include_once (ROOT.'/database/DB.php');
class Session extends DB {
    public  $loggedIn = false;
    public $user;
    public $id;
    public $first_name;
    public $last_name;
    public $email;

    public function __construct()
    {

        parent::__construct();
        $this->session_state();
        $this->cookies_state();
    }

    public function session_state(){
        if(isset($_SESSION['ml_id']) &&  isset($_SESSION['ml_user'])){
            $this->id = preg_replace("[^0-9]","",$_SESSION['ml_id']);
            $this->user = preg_replace("#[^a-z0-9-_. ]#i",'',$_COOKIE['ml_user']);

            if(!empty($this->id) && !empty($this->user)){

                $this->loggedIn = $this->is_logged_in();
            }
        }
    }

    public function cookies_state(){
        if(isset($_COOKIE['ml_id']) && isset($_COOKIE['ml_user'])){
            $this->id = preg_replace("[^0-9]","",$_COOKIE['ml_id']);
            $this->user = preg_replace("#[^a-z0-9-_. ]#i",'',$_COOKIE['ml_user']);

            if(!empty($this->id) && !empty($this->user)){
                $this->loggedIn = $this->is_logged_in();

                if($this->loggedIn){
                    $this->update_user_state();
                }

            }
        }
    }

    public function is_logged_in(){
        $fn = explode(' ',$this->user)[0];
        $ln = explode(' ',$this->user)[1];
        $id = $this->id;

        $this->first_name = $fn;
        $this->last_name = $ln;

        $sql = array('table'=>'users','cols'=>'id,email','where'=>"first_name = '$fn' AND last_name = '$ln' AND id = '$id'");
        $sql = $this->select(json_encode($sql));


        $state = false;
        if($sql->num_rows > 0){
            $state = true;
            while ($row = $sql->fetch_array(MYSQLI_ASSOC)){
                $this->email = $row['email'];
            }
        }

        return $state;
    }

    public function update_user_state(){
        $fn = explode(' ',$this->user)[0];
        $ln = explode(' ',$this->user)[1];
        $id = $this->id;

        $this->update(json_encode(array('table'=>'users',
            'cols'=>"last_login = now()",
            'where'=>"id = '$id' AND first_name = '$fn' AND last_name = '$ln' LIMIT 1")));
    }
}

$session = new Session();