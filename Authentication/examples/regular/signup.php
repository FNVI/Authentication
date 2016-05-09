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
            
            use FNVi\Authentication\Schemas\User;
            
            $args = [
                "username"=>FILTER_SANITIZE_STRING,
                "email"=>FILTER_SANITIZE_STRING,
                "password"=>FILTER_SANITIZE_STRING
            ];
            
            $post_vars = filter_input_array(INPUT_POST,$args);
            
            if(!empty($_POST)){
                $user = new User($post_vars["username"],$post_vars["password"]);
                $user->email = $post_vars["email"];
                $user->store();
            }
            
        ?>
        <nav class="navbar navbar-default">
            <div class="container">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="index.php">Home</a>
                    </li>
                    <li class="active">
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
                        <h1>Signup!</h1>
                        <form method="post">
                            <div class="form-group">
                                <label for="user">
                                    Username
                                </label>
                                <input type="text" class="form-control" id="user" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="email">
                                    Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">
                                    Password
                                </label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-success">Signup</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
