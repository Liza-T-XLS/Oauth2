<?php
    $state = md5(time());
    session_start();
    $_SESSION['state'] = $state;
?>
    <p>To discover the origin of this project log in with</p>
    <a class="OauthLink discord" href="https://discord.com/api/oauth2/authorize?client_id=<?= $_ENV['DISCORD_CLIENT_ID'] ?>&redirect_uri=<?= urlencode($_ENV['DISCORD_REDIRECT_URI'])?>&response_type=code&scope=identify%20email&state=<?= $state ?>" title="connect via Discord">Discord</a>
    <a class="OauthLink google" href="https://accounts.google.com/o/oauth2/v2/auth?scope=profile&access_type=online&response_type=code&redirect_uri=<?= urlencode($_ENV['GOOGLE_REDIRECT_URI'])?>&client_id=<?= $_ENV['GOOGLE_CLIENT_ID'] ?>&state=<?= $state ?>" title="connect via Google">Google</a>
    <a class="OauthLink github" href="https://github.com/login/oauth/authorize?client_id=<?= $_ENV['GITHUB_CLIENT_ID'] ?>&redirect_uri=<?= $_ENV['GITHUB_REDIRECT_URI'] ?>&scope=read:user&state=<?= $state ?>">GitHub</a>
    <img class="homeImg" src="/assets/img/authentication.svg" alt="" />
