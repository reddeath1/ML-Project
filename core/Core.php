<?php
/**
 * Created by PhpStorm.
 * User: reddeath
 * Date: 10/27/2018
 * Time: 10:44 PM
 */

class Core
{
    public $URi;
    public $errors;

    public function __construct()
    {
        
    }


    /**
     * @return bool|void
     */
    public function page($page = false){
        $http = $_SERVER['REQUEST_URI'];
        $this->URi = explode('/',$http);
        $this->errors = array();
        if(!$page){
            if(!empty(array_filter($this->URi))){
                $p = (preg_match('/localhost/',URI)) ? ucfirst($this->URi[2]) : ucfirst($this->URi[1]);

                $this->method_exists($p) ? $this->$p() :$this->renderer('home');

            }else{
                $this->renderer('home');
            }
        }else{
            if (!empty($page)) {
                return strtolower($this->URi[$page]);
            }
        }

   }

   public function dashboard(){
       $this->renderer('dashboard');
   }

   public function Home(){
       $this->renderer('home');
   }

   public function Logout(){
       $this->renderer('logout');
   }

   public function Profile(){
       $this->renderer('profile');
   }

   public function method_exists($meth){
       if (method_exists($this,$meth)) {
           return true ;
       }else{
           $this->errors = array('Page'=>"This page does not exists!");
       }

       return false;
   }

   private function renderer($view){
        include_once (ROOT."/views/$view.php");
   }

   public function error(){
        $e = '<h3 style="text-transform: uppercase;margin-top: 50px">Error Occurred !</h3>';
        if($this->errors){
            foreach ($this->errors as $k => $error) {
                $e .= "<p><b style='color:brown'>$k</b>: $error</p>";
            }
        }

        echo $e;
   }

   public function install(){
        header("Location:".URI.'/database/Tables.php');
   }

   public function header(){
      $this->renderer('header');
   }

   public function footer(){
       echo file_get_contents($_SERVER['DOCUMENT_ROOT'].'/views/footer.php');
   }

}

$core = new Core();
