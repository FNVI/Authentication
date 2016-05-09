<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        
        <meta charset="UTF-8">
        <title>Authentication example</title>
        
        <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
        
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" type="text/javascript"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript"></script>
            
    </head>
    <body>
        <?php
            include 'settings.php';
            include '../../vendor/autoload.php';
            
            use FNVi\Authentication\Auth;
            
            $auth = new Auth();
            $auth->requiresLogin();
            
            $user = $auth->getUser();
            
        ?>
        <nav class="navbar navbar-default">
            <div class="container">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="index.php">Home</a>
                    </li>
                    <li>
                        <a href="signup.php">Sign up</a>
                    </li>
                    <li>
                        <a href="login.php">Login</a>
                    </li>
                    <li class="active">
                        <a href="profile.php">Profile</a>
                    </li>
                    <li>
                        <a href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container" style="padding-top: 80px;">
            <div class="jumbotron">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <h1>Current User!</h1>
                            <div class="form-group">
                                <label>
                                    Username
                                </label>
                                <label class="form-control">
                                    <?php echo $user->getUsername(); ?>
                                </label>
                            </div>
                            <div class="form-group">
                                <label>
                                    Email
                                </label>
                                <label class="form-control">
                                    <?php echo $user->email; ?>
                                </label>
                            </div>
                        <a href="logout.php" class="btn btn-danger">
                            log out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
