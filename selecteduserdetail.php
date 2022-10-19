<?php
$id = $_GET['id'];

if (isset($_POST['submit'])){
    $receiver_id = $_POST['to'];
    $amount = $_POST['amount'];

    $conn = mysqli_connect('localhost', 'root', '', 'sparks_bank');
    $query_senderq = "SELECT * FROM users where id = $id";
    $query_receiverq = "SELECT * FROM users where id = $receiver_id";

    $query_sender = mysqli_query($conn, $query_senderq);
    $query_receiver = mysqli_query($conn, $query_receiverq);

    $sql1 = mysqli_fetch_assoc($query_sender);
    $sql2 = mysqli_fetch_assoc($query_receiver);

    $balance1 = $sql1['balance'] - $amount;
    //$balance2 = $sql2['balance'] + $amount;

    if ($balance1 < 0){
        echo '<script type="text/javascript">';
        echo ' alert("Bad Luck! Insufficient Balance")';  // showing an alert box.
        echo '</script>';
    }
    elseif ($amount < 0){
        echo '<script type="text/javascript">';
        echo ' alert("Oops! Negative values cannot be transferred")';  // showing an alert box.
        echo '</script>';
    }
    elseif ($amount == 0){
        echo "<script type='text/javascript'>";
        echo "alert('Oops! Zero value cannot be transferred')";
        echo "</script>";
    }
    else{
        $query_update1 = "UPDATE users SET balance = $balance1 where id = $id";
        mysqli_query($conn, $query_update1);

        $balance1 = $sql2['balance'] + $amount;
        $query_update2 = "UPDATE users SET balance = $balance1 where id = $receiver_id";
        mysqli_query($conn, $query_update2);

        $name_sender = $sql1['name'];
        $name_receiver = $sql2['name'];

        $date = date('d-m-Y h:i:s');
        echo $date;
        $insert_query = "INSERT INTO transactions (sender, receiver, amount) VALUES ('$name_sender', '$name_receiver', $amount)";
        $myq = mysqli_query($conn, $insert_query);

        $balance1 = 0;
        $amount = 0;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">

    <style type="text/css">
    	
		button{
			border:none;
			background: #d9d9d9;
		}
	    button:hover{
			background-color:#777E8B;
			transform: scale(1.1);
			color:white;
		}

    </style>
</head>

<body>
<?php
//include 'config.php';
include 'navbar.php';

?>

	<div class="container">
        <h2 class="text-center pt-4">Transaction</h2>

            <form method="post" name="tcredit" class="tabletext" action="#" ><br>
        <div>
            <table class="table table-striped table-condensed table-bordered">
                <tr>
                    <th class="text-center">Id</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Balance</th>
                </tr>
            <?php
            $conn = mysqli_connect('localhost', 'root', '', 'sparks_bank');
            $select_query = "SELECT id, name, email, Balance from users where id= $id";
            $myq = mysqli_query($conn, $select_query);

            $selectall_query = "SELECT * from users";
            $myqall = mysqli_query($conn, $selectall_query);

            $num = mysqli_num_rows($myq);
            if($num > 0){
                $data = mysqli_fetch_assoc($myq);
                echo '<br>';
            }

            echo '<tr>';
            echo '<td class="py-2">'.$data['id'].'</td>';
            echo '<td class="py-2">'.$data['name'].'</td>';
            echo '<td class="py-2">'.$data['email'].'</td>';
            echo '<td class="py-2">'.$data['Balance'].'</td>';
            echo '</tr>';

            ?>
            </table>
        </div>
        <br>
        <label>Transfer To:</label>
        <select name="to" class="form-control" required>
            <option value="" disabled selected>Choose</option>
            <?php
            while ($datall = mysqli_fetch_assoc($myqall)){
                if ($datall['id'] != $data['id']){
                $ids = $datall['id'];
            ?>
                    <option class="table" value="<?php echo $ids?>" >
                        <?php
                        echo $datall['name'] . '(Balance:';
                        echo $datall['balance'] . ')';
                        ?>

                    </option>
               <?php }?>
            <?php }?>


            <div>
        </select>
        <br>
        <br>
            <label>Amount:</label>
            <input type="number" class="form-control" name="amount" required>   
            <br><br>
                <div class="text-center" >
            <button class="btn mt-3" name="submit" type="submit" id="myBtn">Transfer</button>
            </div>
        </form>
    </div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>