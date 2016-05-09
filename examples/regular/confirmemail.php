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
            
            $token = filter_input(INPUT_GET,"token", FILTER_SANITIZE_STRING);
            
            $auth->confirmEmail($token);
            
            
        ?>
        <nav class="navbar navbar-default">
            <div class="container">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="index.php" >Home</a>
                    </li>
                    <li>
                        <a href="signup.php">Sign up</a>
                    </li>
                    <li>
                        <a href="login.php">Login</a>
                    </li>
                    <li>
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
                        <h1>Login!</h1>
                        <span class="text-danger">
                            <?php echo $auth->getMessage(); ?>
                        </span>
                        <form method="post">
                            <div class="form-group">
                                <label for="user">
                                    Username
                                </label>
                                <input type="text" class="form-control" id="user" name="username">
                            </div>
                            <div class="form-group">
                                <label for="password">
                                    Username
                                </label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <button type="submit" class="btn btn-success">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
