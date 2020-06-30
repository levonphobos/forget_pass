<?php
if (isset($_POST['email'])) {

// Create connection
    $conn = new mysqli('localhost', 'root', '', 'login');
// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $conn->real_escape_string($_POST['email']);

    $sql = $conn->query("SELECT id FROM reset WHERE email='$email'");

    if ($sql->num_rows > 0) {
        $token = 'jhbkjhbkj3hb4jh3bj4hbr3jh4r34rjhbjhbj34r';
        $token = str_shuffle($token);
        $token = substr($token, 0, 10);

        $conn->query("UPDATE reset SET reset_token='$token', reset_token_expire =DATE_ADD(NOW(), INTERVAL 5 MINUTE)
WHERE email='$email'");

        $to = $_POST['email'];
        $subject = "Reset Password";
        $code= uniqid();
        $url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/resetPassword.php?email=$email&token=$token";
        $txt = '
<h3>
You are requested a password reset
</h3>
<p>Click <a href="'.$url.'">this link</a> to do so</p>
';
        $headers = "From: lauramamunc11@gmail.com";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
        $headers .= 'From: <webmaster@example.com>' . "\r\n";
        $headers .= 'Cc: myboss@example.com' . "\r\n";
        if(mail($to,$subject,$txt, $headers)){
            exit(json_encode(array('status' => 1, 'msg' => 'Please Check Your Email Inbox!')));
        } else{
            exit(json_encode(array('status' => 0, 'msg' => 'Something Went Wrong! Please Try Again!')));
        }

    } else {
        exit(json_encode(array('status' => 0, 'msg' => 'Please Check Your Inputs!')));
    }
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
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="text" class="form-control" id="email" aria-describedby="emailHelp">
            </div>
            <button id="send" class="btn btn-primary">Send</button>
            <br><br>
            <p id="response"></p>
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
<script>
    $(document).ready(function () {
        let email = $('#email');
        $('#send').on('click', () => {
            if (email.val() !== '') {
                email.css('border', '1px solid green');
                $.ajax({
                    url: 'index.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        email: email.val()
                    }, success: response => {
                        if (!response.success) {
                            $('#response').html(response.msg).css('color', 'red');
                        } else {
                            $('#response').html(response.msg).css('color', 'green');
                        }
                    }
                })
            } else {
                email.css('border', '1px solid red');
            }
        })
    });
</script>

</body>
</html>