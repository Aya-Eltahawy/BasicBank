<?php
function connection(){
    $conn = mysqli_connect('localhost', 'root', '', 'sparks_bank');
    $select_query = "SELECT * from users";
    $myq = mysqli_query($conn, $select_query);

    $num = mysqli_num_rows($myq);
    if($num > 0){
        //while ($num > 0){
            $data = mysqli_fetch_all($myq);
            echo '<br>';
            return $data;
    }
}

?>
