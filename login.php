<?php
session_start();
if(isset($_GET['logout'])){
  session_destroy();
session_unset();
header('location:login.php');
}
;
$conn = mysqli_connect('test', 'test', 'test', 'test') or die(mysqli_error($conn));
if ($conn) {
    if (isset($_POST['submit'])) {
        if (!empty($_POST['email']) and !empty($_POST['password'])) {
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $sql = "SELECT * FROM users WHERE email='$email' and password='$password'";
            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
            if (mysqli_num_rows($result) > 0) {
                while ($line = mysqli_fetch_assoc($result)) {
                    $_SESSION['idUser']=$line['idUser'];
                    $_SESSION['name']=$line['name'];
                    $_SESSION['email']=$line['email'];
                    $_SESSION['image']=$line['image'];
                    header('location:homePage.php');
                }
            }else{
                header('location:login.php?incorrect');
            }
        }else{
            header('location:login.php?fill');
        }
    }


?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">

    </head>
    <style>
        .note {
            text-align: center;
            height: 80px;
            background-color: #00acee;
            color: #fff;
            font-weight: bold;
            line-height: 80px;
        }

        .form-content {
            padding: 5%;
            border: 1px solid #ced4da;
            margin-bottom: 2%;
        }

        .form-control {
            border-radius: 1.5rem;
        }

        .btnSubmit {
            border: none;
            border-radius: 1.5rem;
            padding: 1%;
            width: 20%;
            cursor: pointer;
            background: #00acee;
            color: #fff;
        }
    </style>

    <body>
        <div class="container register-form">
            <div class="form">
                <div class="note">
                    <p><i class="fab fa-twitter" style="font-size: 2rem;"></i> Login to twitter</p>
                </div>
                <form action="login.php" method="POST">
                    <div class="form-content">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Email *" value="" name="email" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Your Password *" value="" name="password" autocomplete="off" />
                                </div>

                            </div>
                        </div>
                        <input type="submit" class="btnSubmit" value="SignUp" name="submit">
                    </div>
                </form>
                <?php
                if(isset($_GET['incorrect'])){
                    echo'
                    <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>DANGER</strong> Error login/password.
                  </div>';
                }
                if(isset($_GET['fill'])){
                    echo'
                    <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>DANGER</strong> Fill all the blanks.
                  </div>';
                }
                ?>
            </div>
        </div>
    </body>

    </html>
<?php
}

?>
