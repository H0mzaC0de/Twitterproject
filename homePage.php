<?php
session_start();
$conn=mysqli_connect('test','test','test','test') or die(mysqli_error($conn));
if($conn){
  $reponse='';
if(isset($_POST['submit'])){
  if(!empty($_POST['description']) and !empty($_FILES['image'])){
      $img_name=$_FILES['image']['name'];
        $img_size=$_FILES['image']['size'];
        $img_error=$_FILES['image']['error'];
        $tmp_name=$_FILES['image']['tmp_name'];
        $description=$_POST['description'];
        $idUser=$_SESSION['idUser'];
        if($img_error===0){
          if($img_size<1250000){
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exs = array('png', 'jpeg', 'jpg');
            if(in_array($img_ex_lc,$allowed_exs)){
              $new_img_name=uniqid("IMG-",true).'.'.$img_ex_lc;
              $img_upload_path='uploadsPost/'.$new_img_name;
              move_uploaded_file($tmp_name, $img_upload_path);
              $sql="INSERT INTO posts(idUser,image,description) VALUES ('$idUser','$new_img_name','$description')";
              $result=mysqli_query($conn,$sql) or die(mysqli_error($conn));
              if($result>0){
                $reponse='<span style="color:green">Post Added</span>';
              }else{
                $reponse='<span style="color:red">Error</span>';
              }
            }else{
              $reponse='<span style="color:red">Unsupported Extension</span>';
            }
          }else{
            $reponse='<span style="color:red">File Too large</span>';
          }
        }else{
          $reponse='<span style="color:red">there was an error uploading</span>';
        }
  }else{
    $reponse='<span style="color:red">Fill all the blanks</span>';
  }
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Twitter Clone - Final</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
      crossorigin="anonymous"
    />
  </head>
  <body>
    <!-- sidebar starts -->
    <div class="sidebar">
      <i class="fab fa-twitter"></i>
      <div class="sidebarOption active">
        <span class="material-icons"> home </span>
        <h2>Home</h2>
      </div>

      <div class="sidebarOption">
        <span class="material-icons"> search </span>
        <h2>Explore</h2>
      </div>

      

      <div class="sidebarOption">
        <span class="material-icons"> mail_outline </span>
        <h2>Messages</h2>
      </div>


      <div class="sidebarOption">
        <span class="material-icons"> perm_identity </span>
        <h2>Profile</h2>
      </div>
      <div class="sidebarOption">
        <span class="material-icons"> perm_identity </span>
        <h2><a href="login.php?logout" style="text-decoration: none;color:black">Logout</a></h2>
      </div>

      <button class="sidebar__tweet">Tweet</button>
    </div>
    <!-- sidebar ends -->

    <!-- feed starts -->
    <div class="feed" >
      <div class="feed__header">
        <h2>Home</h2>
      </div>

      <!-- tweetbox starts -->
      <form action="homePage.php" method="POST" enctype="multipart/form-data">
      <div class="tweetBox">
          <div class="tweetbox__input">
            <img
              src="https://i.pinimg.com/originals/a6/58/32/a65832155622ac173337874f02b218fb.png"
              alt=""
            />
            <input type="text" placeholder="What's happening?" name="description" id="description"/>
            <input type="file" name="image" id="image"/>
          </div>
          <input type="submit" class="tweetBox__tweetButton" value="Tweet" name="submit">
          <?php echo $reponse;?>
      </div>
    </form>
      <!-- tweetbox ends -->

      <!-- post starts -->
      <div class="feed" id="feed">
       <?php
       $idUser=$_SESSION['idUser'];
       $sql4="SELECT posts.*, users.name,users.idUser,users.image as 'uimage',users.email from users,follow,posts
       where posts.idUser=users.idUser
       and posts.idUser=follow.idUserFollowed
       and follow.idUserFollower=$idUser
       order by posts.date desc";
       $result4=mysqli_query($conn,$sql4) or die(mysqli_error($conn));
       if(mysqli_num_rows($result4)>0){
         while($line4=mysqli_fetch_assoc($result4)){

         
       
       ?>
      <div class="post" id="post<?php echo $line4['idPost']?>">
        <div class="post__avatar">
          <img
            src="uploads/<?php echo $line4['uimage']?>"
            alt=""
          />
        </div>

        <div class="post__body">
          <div class="post__header">
            <div class="post__headerText">
              <h3>
              <!-- //name here --><?php echo $line4['name']?>
                <span class="post__headerSpecial"
                  ><span class="material-icons post__badge"> verified </span><?php echo $line4['email']?></span
                >
              </h3>
            </div>
            <div class="post__headerDescription">
              <p><?php echo $line4['description']?></p>
            </div>
          </div>
          <img
            src="uploadsPost/<?php echo $line4['image']?>"
            alt=""
          />
          <div class="post__footer" style="position: relative;">
            <a href="#" onclick="display(<?php echo $line4['idPost']?>)"><span class="material-icons"> repeat </span> </a>
            <?php 
            $idPost=$line4['idPost'];
            $sql5="SELECT count(idLike) as 'nbr' from likes,posts where likes.idPost=$idPost";
            $result5=mysqli_query($conn,$sql5)or die(mysqli_error($conn));
            $line5=mysqli_fetch_assoc($result5);
            ?>
            <a href="#" onclick="addLike(<?php echo $idPost?>)"style="cursor: pointer;"><span class="material-icons" id="heart<?php echo $line4['idPost']?>" > favorite_border </span></a><span id="numLikes<?php echo $line4['idPost']?>" style="position:absolute;left:540px"><?php echo $line5['nbr']?></span>
          </div>
          <div class="commentSection" id="commentSection<?php echo $line4['idPost']?>" style="display: none;">
            <input type="text" id="comment" placeholder="Add Comment">
            <button id="sendComment" onclick="comment(<?php echo $line4['idPost']?>)">Add</button>
            <a href="#" onclick="reveal(<?php echo $line4['idPost']?>)">See comments</a>
          </div>
          <div id="load<?php echo $line4['idPost']?>" style="display: none;">
          <?php
          $idPost=$line4['idPost'];
          $sql7="SELECT comments.*,users.image,users.name FROM comments,users WHERE comments.idPost=$idPost
          and comments.idUser=users.idUser";
          $result7=mysqli_query($conn,$sql7) or die(mysqli_error($conn));
          if(mysqli_num_rows($result7)>0){
            while($line7=mysqli_fetch_assoc($result7)){

          ?>
            <div class="comments"  style="position:relative;width:700px;height:100px;border:1px solid black;" >
              <img src="uploads/<?php echo $line7['image']?>" alt="" style="width:40px;height:40px;border-radius:50%;position:absolute;left:0;top:10px">
              <span id="text" style="position:absolute;left:60px;top:10px;font-size:1.3rem"><?php echo $line7['name']?></span> 
              <span id="text" style="position:absolute;left:60px;top:35px;font-size:1.5rem"><?php echo $line7['comment']?></span>
              <span id="time" style="position:absolute;left:10px;top:60px;font-size:1rem;color:grey"><?php echo $line7['date']?></span>
            </div>
            <?php
            }
          }
            ?>
          </div>
        </div>
      </div>
      <?php
      }
    }
      ?>
     
      
      <!-- post ends -->

      
    </div>
    </div>
    <!-- feed ends -->

    <!-- widgets starts -->
    <div class="widgets">
      <div class="widgets__input">
        <span class="material-icons widgets__searchIcon"> search </span>
        <input type="text" placeholder="Search Twitter" onkeyup="search()" id="searchV"/>
      </div>
<!-- search blasa -->
<div class="widgets__widgetContainer" >
        <h2>Search</h2>
        <div id="here">

        </div>
</div>

<!-- end of search blasa -->
      <!-- start of friends list -->
      <div class="widgets__widgetContainer" id="hna2">
        <h2>Friends</h2>
        <?php
        $idUser=$_SESSION['idUser'];
        $sql3="SELECT follow.*,users.name,users.idUser,users.image from follow,users
        where users.idUser=follow.idUserFollowed
         and follow.idUserFollower=$idUser";
        $result3=mysqli_query($conn,$sql3) or die(mysqli_error($conn));
        if(mysqli_num_rows($result3)>0){
          while($line3=mysqli_fetch_assoc($result3)){

          
        ?>
        <div class="userDiv" style="width:100%;;position:relative;height:10vh" id="friend<?php echo $line3['idUser']?>">
        <img src="uploads/<?php echo $line3['image']?>" alt="" style="width:40px;height:40px;border-radius:50%;position:absolute;left:0;top:20px">
        <span style="position: absolute;left:50px;top:25px;"><?php echo $line3['name']?></span>

        <button class="sidebar__tweet" style="width:60px;height:20px;position:absolute;top:45px;left:0;cursor:pointer" onclick="unfollow(<?php echo $line3['idUser']?>)">Unfollow</button>
        <button class="sidebar__tweet" style="width:65px;height:20px;position:absolute;top:45px;left:65px;cursor:pointer">Message</button>
       
        </div>
        <?php
        }
      }
        ?>

      </div>
      <!-- end of friends list -->
  <!-- start of users list -->
      <div class="widgets__widgetContainer" id="hna">
        <h2>Users</h2>
        <?php
        $idUser=$_SESSION['idUser'];
        $sql="SELECT users.* from users where 
        users.idUser != '$idUser' 
        
        and users.idUser NOT IN (select idUserFollowed from follow )";
        $result=mysqli_query($conn,$sql) or die(mysqli_error($conn));
        if(mysqli_num_rows($result)>0){
          while($line=mysqli_fetch_assoc($result)){

         
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
        ?>
      </div>
  <!-- end of users list -->
      <div class="widgets__widgetContainer">
        <h2>What's happening?</h2>
        <blockquote class="twitter-tweet">
          <p lang="en" dir="ltr">
            Sunsets don&#39;t get much better than this one over
            <a href="https://twitter.com/GrandTetonNPS?ref_src=twsrc%5Etfw">@GrandTetonNPS</a>.
            <a href="https://twitter.com/hashtag/nature?src=hash&amp;ref_src=twsrc%5Etfw"
              >#nature</a
            >
            <a href="https://twitter.com/hashtag/sunset?src=hash&amp;ref_src=twsrc%5Etfw"
              >#sunset</a
            >
            <a href="http://t.co/YuKy2rcjyU">pic.twitter.com/YuKy2rcjyU</a>
          </p>
          &mdash; US Department of the Interior (@Interior)
          <a href="https://twitter.com/Interior/status/463440424141459456?ref_src=twsrc%5Etfw"
            >May 5, 2014</a
          >
        </blockquote>
        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
      </div>
    </div>
    <!-- widgets ends -->
  </body>
  <script src="js/jquery-3.6.0.js"></script>
  <script>
     function addFollow(idUser){
      var hnaya=<?php echo $_SESSION['idUser']?>;
      $.post("addFollow.php",{idUser:idUser,hnaya:hnaya},
      function(result,status){
        $('#user'+idUser).remove();
          $('#hna2').append(result);
      });
     }

     function unfollow(idUser){
      var hnaya=<?php echo $_SESSION['idUser']?>;
      $.post("unfollow.php",{idUser:idUser,hnaya:hnaya},
      function(result,status){
          $('#friend'+idUser).remove();
          $('#hna').append(result);

      });
     }
     function addLike(idPost){
       var idUser=<?php echo $_SESSION['idUser']?>;
       $.post("addLike.php",{idPost:idPost,idUser:idUser},
       function(result,status){
         if(result==1){
           $("#heart"+idPost).css("color","red")
           var num=$("#numLikes"+idPost).html();
           var IntNum=parseInt(num);
           IntNum++;
           $("#numLikes"+idPost).html(IntNum);
         }else if(result==2){
          $("#heart"+idPost).css("color","black")
           var num=$("#numLikes"+idPost).html();
           var IntNum=parseInt(num);
           if(IntNum!=0){
             IntNum--;
           }
           
           $("#numLikes"+idPost).html(IntNum);
         }
       })
     }

     function search(){
       var searchV=$("#searchV").val();
       $.post("search.php",{searchV:searchV},
       function(result,status){
        $("#here").html(result);
       })
     }

     function display(idPost){
       $("#commentSection"+idPost).css("display","block");

     }
     function reveal(idPost){
       $("#load"+idPost).css("display","block");

     }
     function comment(idPost){
       var idUser=<?php echo $_SESSION['idUser'];?>;
       var commentText=$("#comment").val();
       if(commentText!=''){
         $.post("comment.php",{commentText:commentText,idPost:idPost,idUser:idUser},
         function(result,status){
           alert(commentText)
           $("#load"+idPost).append(result);
         });
       }
       
     }
     
  </script>
</html>
<?php
}
?>
