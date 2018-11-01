<?php
/**
 * Created by PhpStorm.
 * User: reddeath
 * Date: 10/30/2018
 * Time: 1:28 PM
 */

class  Dashboard extends session {
    public $URi;

    public function __construct()
    {
        parent::__construct();

        $http = $_SERVER['REQUEST_URI'];
        $this->URi = explode('/',$http);
    }

    public function page($page = false){
        return strtolower($this->URi[$page]);
    }

    public function contents($id = false,$user_id = false)
    {
        $data = array();
        $id = ($id) ? "WHERE c.id = '$id'" : '';
        $user_id = ($user_id) ? "WHERE c.user_id = '$user_id'" : "WHERE c.user_id = '$this->id'";
        $both = '';
        if($id && !$id) {
            $both =  "$id AND c.user_id = '$user_id'";
            $id = '';
            $user_id = '';
        }

        $param = array('query'=>
            "SELECT *,c.id as c_id FROM cart as c LEFT JOIN ads as a ON(c.ad_id = a.id) $id $user_id $both"
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
                $c_id = $datum['c_id'];
                $desc = $datum['desc_ad'];
                $format = $datum['format'];
                $type = $datum['banner'];
                $media = $datum['media'];
                $campaign_id = $datum['campaign_id'];
                $campaign_name = $datum['name'];

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
                    $date = date('Y/m/d',strtotime($row['date_created']));
                }

                $result .= "<tr>
                <td>$title</td>
                <td>New</td>
                <td>$$discount_rate</td>
                <td class=\"text-center\"><a class='btn btn-info btn-xs' onclick='issueInvoice();'><span class=\"glyphicon glyphicon-dollar\"></span> Pay</a> <a onclick='issueInvoice();' class=\"btn btn-danger btn-xs\"><span class=\"glyphicon glyphicon-remove\"></span> Del</a></td>
                
            </tr>";

            }

            $data = $result;
        }else{
            $data = "<tr>                
                <td>No records was found! </td></tr>";
        }

        return $data;
    }
}

$db = new Dashboard();
?>

<div class="wrapper">
    <aside class="main_sidebar">
        <ul>
            <li><i class="fa fa-arrows"></i><a href="#">arrows</a></li>
            <li><i class="fa fa-battery-2"></i><a href="#">battery</a></li>
            <li class="active"><i class="fa fa-bell"></i><a href="#">bell</a></li>
            <li><i class="fa fa-bicycle"></i><a href="#">bicycle</a></li>
            <li><i class="fa fa-circle"></i><a href="#">circle</a></li>
            <li><i class="fa fa-crosshairs"></i><a href="#">crosshairs</a></li>
            <li><i class="fa fa-deaf"></i><a href="#">deaf</a></li>
            <li><i class="fa fa-desktop"></i><a href="#">desktop</a></li>
            <li><i class="fa fa-dot-circle-o"></i><a href="#">dot</a></li>
            <li><i class="fa fa-folder"></i><a href="#">folder</a></li>
        </ul>
    </aside>
    <div class="mian">
    </div>
</div>

<div class="container" id="dashboard">
    <div class="row col-md-12 col-md-offset-1 custyle">
        <table class="table table-striped custab ">
            <thead>
            <a class="btn btn-primary btn-xs pull-right"><b>Ads</b> List</a>
            <tr>
                <th>Title</th>
                <th>Filter</th>
                <th>Total Price</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <?php echo $db->contents(false,$db->page(false,$db->id));?>
        </table>
    </div>

</div>