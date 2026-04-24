<?php
session_start();
$msg_error='';
if(isset($_SESSION['msg']))
{
    $msg_error=$_SESSION['msg'];
    unset($_SESSION['msg']);
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/form-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.css">
    <title>Document</title>
</head>
    <style>
        :root {
            --admin-primary: #e65100;
            --admin-primary-light: #fb8c00;
        }
        body {
            background-color: #fff3e0;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            width: 100%;
            max-width: 450px;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(230, 81, 0, 0.2);
        }
        .login-card .card-title {
            color: var(--admin-primary);
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
        }
        .admin-btn {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-light)) !important;
            width: 100%;
            border-radius: 25px;
            height: 45px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .input-field input:focus {
            border-bottom: 1px solid var(--admin-primary) !important;
            box-shadow: 0 1px 0 0 var(--admin-primary) !important;
        }
        .input-field input:focus + label {
            color: var(--admin-primary) !important;
        }
        .error-msg {
            color: #d32f2f;
            background: #ffebee;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: 500;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="card login-card white">
        <h4 class="card-title">HealthCare MS <br><span style="font-size: 1.5rem; opacity: 0.8; color: #fb8c00;">Admin Portal</span></h4>
        
        <form action="login-admin.php" method="post">
            <?php
                if(!empty($msg_error)){
                    echo '<div class="error-msg">'.$msg_error.'</div>';
                }
            ?>

            <div class="row" style="margin-bottom: 10px;">
                <div class="input-field col s12">
                    <i class="material-icons prefix" style="color: #fb8c00;">email</i>
                    <input name="email" id="email" type="email" class="validate" required>
                    <label for="email">Admin Email</label>
                </div>
            </div>

            <div class="row" style="margin-bottom: 30px;">
                <div class="input-field col s12">
                    <i class="material-icons prefix" style="color: #fb8c00;">lock</i>
                    <input id="password" name="password" type="password" class="validate" required>
                    <label for="password">Password</label>
                </div>
            </div>

            <div class="row" style="margin-bottom: 0;">
                <div class="col s12 center-align">
                    <button type="submit" class="waves-effect waves-light btn admin-btn">Login to Dashboard</button>
                </div>
            </div>
            
            <div class="center-align" style="margin-top: 20px;">
                <a href="../index.php" style="color: #fb8c00; font-size: 0.9rem;">&larr; Back to Main Site</a>
            </div>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.js"></script>
</body>
</html>