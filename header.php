
<?php

$URI = URI;
$user_menu = '<ul class="dropdown-menu account">
                            <li><a ><i class="glyphicon glyphicon-menu-right"></i> Login </a></li>
                            <li><a ><i class="glyphicon glyphicon-menu-right"></i> Sign Up </a></li>
                            
                        </ul>';

$user_menu1 = '<ul class="dropdown-menu account">
                                <li><a >Login</a></li>
                                <li><a >Sign Up</a></li>
                            </ul>';

if($session->loggedIn){
    $user_menu = '
                    <ul class="dropdown-menu">
                        <li>
                            <div class="navbar-login">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <p class="text-center">
                                            <span class="glyphicon glyphicon-user icon-size"></span>
                                        </p>
                                    </div>
                                    <div class="col-lg-8">'.$user.'</strong></p>
                                        <p class="text-left small">'.$email.'</p>
                                        <p class="text-left pr">
                                            <a href="'.$URI.'/profile/'.$first_name.'" class="btn btn-primary btn-block btn-sm">Profile</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="navbar-login navbar-login-session">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <p>
                                            <a href="'.$URI.'/logout" class="btn btn-danger btn-block">Logout</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>';

    $user_menu1 = '<ul class="dropdown-menu">
                                <li><a href="'.$URI.'/profile/'.$first_name.'">Profile</a></li>
                                <li><a href="'.$URI.'/dashboard/'.$ud.'">Dashboard</a></li>
                            </ul>';
}

?>

<body>
<nav class="navbar-default top-header">
    <div class="container">
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <strong><?php echo $default_user;?></strong>
                        <span class="caret"></span>
                    </a>
                    <?php echo $user_menu;?>
                </li>
            </ul>
        </div>
    </div>
</nav>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">

        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo URI;?>"><?php echo $title;?></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li class=" <?php echo ($core->page(1) === 'home' || empty($core->page(1))) ? 'active' :'' ?>"><a href="<?php echo URI;?>">Home <span class="sr-only">(current)</span></a></li>
                </ul>

                <div class="row">

                    <div class="col-md-7">
                        <div id="custom-search-input">
                            <div class="input-group col-md-12">
                                <input type="text" class="form-control input-lg" placeholder="What are you looking for?" />
                                <span class="input-group-btn">
                                    <button class="btn btn-lg" type="button">
                                        <i class="glyphicon glyphicon-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Account <span class="caret"></span></a>
                            <?php echo $user_menu1;?>
                        </li>
                    </ul>

                </div>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </div>
</nav>

<!--Modal: Login / Register Form-->
<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" align="center">
                <span class="glyphicon glyphicon-user icon-size"></span>
<!--                <img class="img-circle" id="img_logo" src="http://penora.ga/images/images/penora.png">-->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </button>
            </div>

            <!-- Begin # DIV Form -->
            <div id="div-forms">

                <!-- Begin # Login Form -->
                <form id="login-form">
                    <div class="modal-body">
                        <div id="div-login-msg">
                            <div id="icon-login-msg" class="glyphicon glyphicon-chevron-right"></div>
                            <span id="text-login-msg">Type your email and password.</span>
                        </div>
                        <input id="login_username" class="form-control" type="text" placeholder="Type your email or phone no." required>
                        <input id="login_password" class="form-control" type="password" placeholder="Password" required>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox"> Remember me
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div>
                            <button type="submit" id="log" class="btn btn-primary btn-lg btn-block">Login</button>
                        </div>
                        <div>
                            <button id="login_lost_btn" type="button" class="btn btn-link">Lost Password?</button>
                            <button id="login_register_btn" type="button" class="btn btn-link">Register</button>
                        </div>
                    </div>
                </form>
                <!-- End # Login Form -->

                <!-- Begin | Lost Password Form -->
                <form id="lost-form" style="display:none;">
                    <div class="modal-body">
                        <div id="div-lost-msg">
                            <div id="icon-lost-msg" class="glyphicon glyphicon-chevron-right"></div>
                            <span id="text-lost-msg">Type your e-mail.</span>
                        </div>
                        <input id="lost_email" class="form-control" type="text" placeholder="E-Mail (type ERROR for error effect)" required>
                    </div>
                    <div class="modal-footer">
                        <div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Send</button>
                        </div>
                        <div>
                            <button id="lost_login_btn" type="button" class="btn btn-link">Log In</button>
                            <button id="lost_register_btn" type="button" class="btn btn-link">Register</button>
                        </div>
                    </div>
                </form>
                <!-- End | Lost Password Form -->

                <!-- Begin | Register Form -->
                <form id="register-form" style="display:none;">
                    <div class="modal-body">
                        <div id="div-register-msg">
                            <div id="icon-register-msg" class="glyphicon glyphicon-chevron-right"></div>
                            <span id="text-register-msg">Register an account.</span>
                        </div>
                        <input id="register_fn" class="form-control" type="text" placeholder="First Name" required>
                        <input id="register_ln" class="form-control" type="text" placeholder="Last Name" required>
                        <input id="register_tel" class="form-control" type="text" placeholder="Phone Number" required>
                        <input id="register_email" class="form-control" type="email" placeholder="E-Mail" required>
                        <input id="register_password" class="form-control" type="password" placeholder="Password" required>
                    </div>
                    <div class="modal-footer">
                        <div>
                            <button type="submit" id='reg' class="btn btn-primary btn-lg btn-block">Register</button>
                        </div>
                        <div>
                            <button id="register_login_btn" type="button" class="btn btn-link">Log In</button>
                            <button id="register_lost_btn" type="button" class="btn btn-link">Lost Password?</button>
                        </div>
                    </div>
                </form>
                <!-- End | Register Form -->

            </div>
            <!-- End # DIV Form -->

        </div>
    </div>
</div>
<!--Modal: Login / Register Form-->

