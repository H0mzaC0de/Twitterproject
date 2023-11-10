<?php
session_start();
$conn=mysqli_connect('test','test','test','test') or die(mysqli_error($conn));
if($conn){
    $ok=0;
    if(!empty($_POST['commentText'])){
        $comment=$_POST['commentText'];
        $idPost=$_POST['idPost'];
        $idUser=$_POST['idUser'];
        $sql="INSERT INTO comments(idUser,idPost,comment) values('$idUser','$idPost','$comment')";
        $result=mysqli_query($conn,$sql) or die(mysqli_error($conn));
        if($result>0){
            $ok=1;
        }else{
            $ok=0;
        }
        if($ok=1){
            $sql2="SELECT comments.*,users.image,users.name from comments,users where idPost=$idPost and users.idUser=comments.idUser and comments.idUser=$idUser 
            order by comments.idComment  desc limit 1";
            $result2=mysqli_query($conn,$sql2) or die(mysqli_error($conn));
            if(mysqli_num_rows($result2)>0){
                while($line=mysqli_fetch_assoc($result2)){
                    ?>
                     <div class="comments"  style="position:relative;width:700px;height:100px;border:1px solid black;" >
              <img src="uploads/<?php echo $line['image']?>" alt="" style="width:40px;height:40px;border-radius:50%;position:absolute;left:0;top:10px">
              <span id="text" style="position:absolute;left:60px;top:10px;font-size:1.3rem"><?php echo $line['name']?></span> 
              <span id="text" style="position:absolute;left:60px;top:35px;font-size:1.5rem"><?php echo $line['comment']?></span>
              <span id="time" style="position:absolute;left:10px;top:60px;font-size:1rem;color:grey"><?php echo $line['date']?></span>
            </div>
                    
                    <?php
                }
            }
        }
    }
}
?>
