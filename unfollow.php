<?php 
session_start();
$conn=mysqli_connect('test','test','test','test') or die(mysqli_error($conn));
if($conn){
    if(!empty($_POST['idUser']) and !empty($_POST['hnaya'])){
        $idUser=$_POST['idUser'];
        $hnaya=$_POST['hnaya'];
        $sql="DELETE from follow where idUserFollower='$hnaya' and idUserFollowed='$idUser'";
        $sql2="SELECT * from users where idUser='$idUser'";
        $result2=mysqli_query($conn,$sql2);
        $line=mysqli_fetch_assoc($result2);
        $result=mysqli_query($conn,$sql) or die(mysqli_error($conn));
        if(mysqli_affected_rows($conn)>0){

            ?>
            <div class="userDiv" style="width:100%;;position:relative;height:10vh" id="user<?php echo $line['idUser']?>">
        <img src="uploads/<?php echo $line['image']?>" alt="" style="width:40px;height:40px;border-radius:50%;position:absolute;left:0;top:20px">
        <span style="position: absolute;left:50px;top:25px;"><?php echo $line['name']?></span>

        <button class="sidebar__tweet" style="width:60px;height:20px;position:absolute;top:45px;left:0;cursor:pointer" onclick="addFollow(<?php echo $line['idUser']?>)">Follow</button>
        <button class="sidebar__tweet" style="width:65px;height:20px;position:absolute;top:45px;left:65px;cursor:pointer">Message</button>
       
        </div>
            <?php
        }
    }
}


?>
