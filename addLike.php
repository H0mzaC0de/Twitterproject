<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'twitter') or die(mysqli_error($conn));
if ($conn) {
    if (!empty($_POST['idPost']) and !empty($_POST['idUser'])) {
        $idUser = $_POST['idUser'];
        $idPost = $_POST['idPost'];
        $sql2 = "SELECT idUser,idPost from likes where idPost=$idPost and idUser=$idUser";
        $sql = "INSERT INTO likes(idUser,idPost) values('$idUser','$idPost')";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
        $result2 = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
        if (mysqli_num_rows($result2) == 0) {
            if ($result > 0) {
                echo 1;
            } else {
                echo 0;
            }
        }else{
            $sql3="DELETE from likes where idPost=$idPost and iduser=$idUser";
            $result3=mysqli_query($conn,$sql3) or die(mysqli_error($conn));
            if(mysqli_affected_rows($conn)>0){
                echo 2;
            }
        }
    }
}
