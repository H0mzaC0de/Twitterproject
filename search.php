<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'twitter') or die(mysqli_error($conn));
if ($conn) {
    if (!empty($_POST['searchV'])) {
        $search = $_POST['searchV'];
        $sql = "SELECT * from users where name like '%$search%'";
        $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
        if (mysqli_num_rows($result) > 0) {
            while ($line = mysqli_fetch_assoc($result)) {
?>
                <div class="userDiv" style="width:100%;;position:relative;height:10vh" id="friend<?php echo $line['idUser'] ?>">
                    <img src="uploads/<?php echo $line['image'] ?>" alt="" style="width:40px;height:40px;border-radius:50%;position:absolute;left:0;top:20px">
                    <span style="position: absolute;left:50px;top:25px;"><?php echo $line['name'] ?></span>

                    <button class="sidebar__tweet" style="width:60px;height:20px;position:absolute;top:45px;left:0;cursor:pointer" onclick="unfollow(<?php echo $line['idUser'] ?>)">Unfollow</button>
                    <button class="sidebar__tweet" style="width:65px;height:20px;position:absolute;top:45px;left:65px;cursor:pointer">Message</button>

                </div>
<?php
            }
        }
    }
}

?>