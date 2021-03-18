<?php
session_set_cookie_params(array('samesite' => 'Lax'));
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oauth2</title>
    <link href="/assets/css/reset.css" type="text/css" rel="stylesheet">
    <link href="/assets/css/style.css" type="text/css" rel="stylesheet">
</head>
<body>
    <header>
        <?php if(isset($_SESSION['username'])) : ?>
            <div class="logout">
                <a class="logoutLink" href="/logout" title="logout"><img class="logoutIcon" src="/assets/img/logout.svg" alt="logout"></a>
            </div>
        <?php endif; ?>
        <h1><a href="/" title="home">Oauth2</a></h1>
    </header>
    <main>