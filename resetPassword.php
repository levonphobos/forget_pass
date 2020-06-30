<?php

if(isset($_GET['email']) && isset($_GET['token'])){
    $conn = new mysqli('localhost', 'root', '', 'login');
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $conn->real_escape_string($_GET['email']);
    $token = $conn->real_escape_string($_GET['token']);

    $sql = $conn->query("SELECT id FROM reset WHERE email='$email' AND reset_token='$token' AND
reset_token<>'' AND reset_token_expire > NOW()");
    if($sql->num_rows > 0){
        if(isset($_POST['password'])){
            $newPassword = $_POST['password'];
            $sql = $conn->query("UPDATE reset SET reset_token='', password='$newPassword' WHERE email='$email'");
            if($sql === TRUE){
                $response = '<p class="text-success">Your Password Was Changed</p>';
            } else {
                $response = "<p class=\"text-warning\">$conn->error</p>";
            }
        }
    } else {
        $conn->query("UPDATE reset SET reset_token=''");
        header('location:index.php');
    }

} else {
    header('location:index.php');
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4 pt-5">
            <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="pass">New Password</label>
                <input type="password" class="form-control" name="password" id="pass" aria-describedby="emailHelp">
            </div>
            <button id="send" type="submit" class="btn btn-primary">Save</button>
            <br><br>
            </form>
            <div><?php if(isset($response)){echo $response; } ?></div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
</body>
</html>