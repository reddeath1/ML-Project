<?php
/**
 * Created by PhpStorm.
 * User: reddeath
 * Date: 10/29/2018
 * Time: 2:01 AM
 */

include_once 'DB.php';
class Tables extends DB
{
    private $URL;

    public function __construct()
    {
        parent::__construct();
        $this->URL = (preg_match('/localhost/',$this->url())) ? $this->url().'/ML Project' : $this->url();
        $this->install();
    }

    /**
     * create user table
     */
    private function userTable(){
        $param = array("name"=>'users',
            'value'=> "
            id int(11) not null auto_increment,
            first_name varchar(25) not null,
            last_name varchar(25) not null,
            phone_number varchar(255) not null,
            email varchar(25) not null,
            password text not null,
            join_date datetime not null default now(),
            last_login datetime not null default now(),
            primary key(id) 
            ");

        echo $this->createTable(json_encode($param));
    }

    /**
     * create campaign table
     */
    private function campaignTable(){
        $param = array("name"=>'campaign',
            'value'=> "
            id int(11) not null auto_increment,
            name varchar(255) not null,
            date_created datetime not null default NOW(),
            schedule datetime null,
            primary key(id) 
            ");
        echo $this->createTable(json_encode($param));
    }

    /**
     * create ad table
     */
    private function adsTable(){
        $param = array("name"=>'ads',
            'value'=> "
            id int(11) not null auto_increment,
            title varchar(255) not null,
            date_created datetime not null default NOW(),
            desc_ad text not null,
            format varchar(25) not null,
            type varchar(255) not null,
            media text not null,
            campaign_id int(11) not null,
            primary key(id),
            foreign key(campaign_id) references campaign(id)
            ");
        echo $this->createTable(json_encode($param));
    }

    /**
     * cart table
     */
    private function cartTable(){
        $param = array("name"=>'cart',
            'value'=> "
            id int(11) not null auto_increment,
            user_id int(11) not null,
            ad_id int(11) not null,
            dimension varchar(255) not null,
            date_created datetime not null default NOW(),
            primary key(id),
            foreign key(ad_id) references ads(id),
            foreign key(user_id) references users(id)
            ");
        echo $this->createTable(json_encode($param));
    }

    /**
     * dimensions table
     */
    private function dimensionsTable(){
        $param = array("name"=>'dimensions',
            'value'=> "
            id int(11) not null auto_increment,
            card_rate varchar(255) not null,
            discount_rate varchar(255) not null,
            dimension varchar(255) not null,
            ad_id int(11) null,
            date_created datetime not null default NOW(),
            primary key(id),
            foreign key(ad_id) references ads(id)
            ");
        echo $this->createTable(json_encode($param));
    }

    /**
     * create demo data
     */
    private function demoData(){
        /**
         * @user data
         */

        $p = sha1(sha1('admin'));
        $param = array('table'=>'users','data'=>
            "
            first_name = 'admin',
            last_name  = 'admin',
            phone_number  = '0692424684',
            email  = 'frankslayer1@gmail.com',
            password  = '$p'
            ");

        echo $this->insert(json_encode($param));

        /**
         * @campaign data
         */
        $param = array('table'=>'campaign','data'=>
            "
            name = 'ML Project'
            ");

        echo $this->insert(json_encode($param));

        /**
         * @ads data
         */
        $param = array('table'=>'ads','data'=>
            "
            title = 'Lorem Ipsum is simply dummy text of the printing.',
            desc_ad = 'Lorem Ipsum has been the industry standard dummy text ever since the 1500s',
            format = 'JPG',
            type = 'banner',
            media = '150x80.png',
            campaign_id =1
            ");

        echo $this->insert(json_encode($param));

        /**
         * @dimensions data
         */
        $param = array('table'=>'dimensions','data'=>
            "
            card_rate = '1500',
            discount_rate = '1200',
            dimension = '1920x300',
            ad_id = '1'
            ");

        echo $this->insert(json_encode($param));

        /**
         * @dimensions data
         */
        $param = array('table'=>'cart','data'=>
            "
            user_id = '1',
            ad_id = '1',
            dimension = '1920x300'
            ");

        echo $this->insert(json_encode($param));
    }

    /**
     * create db,tables and install demo data
     */
    public function install(){
        echo "<h3 style='color:brown'>Installing .....</h3>";

//        echo "<p style='color:brown'>Creating database .....</p>";
        $this->createDB('ml');
        //print_r(error_get_last());


        echo "<p style='color:brown'>Creating database tables.....</p>";
        $this->userTable();
        $this->campaignTable();
        $this->adsTable();
        $this->cartTable();
        $this->dimensionsTable();
        echo "<p style='color:brown'>Installing demo contents .....</p>";
        $this->demoData();

        echo "<a href='$this->URL'><< Back to home page</a>";
    }

    private function url(){
        $page_url   = 'http';
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
            $page_url .= 's';
        }
        $page_url = $page_url.'://'.$_SERVER['SERVER_NAME'];

        return $page_url;
    }
}

new Tables();
