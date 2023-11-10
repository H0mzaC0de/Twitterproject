<?php
function validateAge($birthday)
{
    // convert user input date to string and +18 years;
    // compare user input date with current date;

    if (time() < strtotime('+18 years', strtotime($birthday))) {
        return false;
    }
    return true;
}
$conn = mysqli_connect('test', 'test', 'test', 'test') or die(mysqli_error($conn));
if ($conn) {
    if (isset($_POST['submit'])) {
        if (!empty($_POST['name']) and !empty($_POST['tel']) and !empty($_POST['email']) and !empty($_POST['password']) and !empty($_POST['passwordCheck']) and !empty($_POST['ddn']) and !empty($_FILES['image'])) {
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $tel = mysqli_real_escape_string($conn, $_POST['tel']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $passwordCheck = mysqli_real_escape_string($conn, $_POST['passwordCheck']);
            $ddn = mysqli_real_escape_string($conn, $_POST['ddn']);
            $img_name = $_FILES['image']['name'];
            $img_size = $_FILES['image']['size'];
            $img_error = $_FILES['image']['error'];
            $tmp_name = $_FILES['image']['tmp_name'];

            if (validateAge($ddn)) {
                if ($img_error === 0) {
                    if ($img_size < 1250000) {
                        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                        $img_ex_lc = strtolower($img_ex);
                        $allowed_exs = array('png', 'jpeg', 'jpg');
                        if (in_array($img_ex_lc, $allowed_exs)) {
                            $new_img_name=uniqid("IMG-",true).'.'.$img_ex_lc;
                            $img_upload_path='uploads/'.$new_img_name;
                            move_uploaded_file($tmp_name, $img_upload_path);
                            if ($password == $passwordCheck) {
                                $sql = "INSERT INTO users(name,email,tel,password,ddn,image) VALUES('$name','$email','$tel','$password','$ddn','$new_img_name')";
                                $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
                                if ($result > 0) {
                                    header('location:signup.php?success');
                                } else {
                                    header('location:signup.php?error');
                                }
                            } else {
                                header('location:signup.php?unmatchedPsds');
                            }
                        }else{
                            header('location:signup.php?wrongExs');
                        }
                    }else{
                        header('location:signup.php?bigFile');
                    }
                }else{
                    header('location:signup.php?ErrorUpload');
                }
            } else {
                header('location:signup.php?ageNotAllowed');
            }
        }else{
            header('location:signup.php?fill');
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
                    <p><i class="fab fa-twitter" style="font-size: 2rem;"></i> Sign Up to twitter</p>
                </div>
                <form action="signup.php" method="POST" enctype="multipart/form-data">
                    <div class="form-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Your Full Name *" value="" name="name" autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Phone Number *" value="" name="tel" autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Email *" value="" name="email" autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <input type="date" class="form-control" placeholder="Email *" value="" name="ddn" autocomplete="off" />
                                    <span style="font-size: smaller; color:grey">Your birthday</span>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Your Password *" value="" name="password" autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Confirm Password *" value="" name="passwordCheck" autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <input type="file" class="form-control" placeholder="Email *" value="" name="image" autocomplete="off" />
                                    <span style="font-size: smaller; color:grey">Your Profile Picture</span>
                                </div>
                            </div>
                        </div>
                        <input type="submit" class="btnSubmit" value="SignUp" name="submit">
                    </div>
                </form>
                <?php
                if(isset($_GET['fill'])){
                    echo '  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>DANGER</strong> Fill the blanks.
                  </div>';
                }
                if(isset($_GET['error'])){
                    echo '  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>DANGER</strong> ther was an error.
                  </div>';
                }
                if(isset($_GET['unmatchedPsds'])){
                    echo '  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>DANGER</strong> Unmatched Passwords.
                  </div>';
                }
                if(isset($_GET['wrongExs'])){
                    echo '  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>DANGER</strong> Unsupported Extension.
                  </div>';
                }
                if(isset($_GET['bigFile'])){
                    echo '  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>DANGER</strong> File too large.
                  </div>';
                }
                if(isset($_GET['ErrorUpload'])){
                    echo '  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>DANGER</strong> Error Uploading.
                  </div>';
                }
                if(isset($_GET['ageNotAllowed'])){
                    echo '  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>DANGER</strong> Age Not Allowed.
                  </div>';
                }
                if(isset($_GET['success'])){
                    echo '  <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Success</strong> You have been signed up!.
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
