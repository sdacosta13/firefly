<?php
    $username = $_POST['username'];
    $password = $_POST['password'];
    $requestType = $_SERVER['REQUEST_METHOD'];

    $username = stripcslashes($username);
    $password = stripcslashes($password);

    if ($requestType == 'POST' && $username == 'Authey' && $password == '0913'){
        echo "Welcome, $username";
    }else if ($requestType == 'POST'){
        // header("Location: http://localhost:63342/groupWeb/login.html");
        echo ("<p>username or password incorrect</p>");
    }
?>
