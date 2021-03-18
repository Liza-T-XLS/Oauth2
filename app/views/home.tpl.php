    <p>To discover the origin of this project log in with</p>
    <a class="OauthLink discord" href="https://discord.com/api/oauth2/authorize?client_id=<?= $_ENV['CLIENT_ID'] ?>&redirect_uri=http%3A%2F%2Flocalhost%3A8000%2Fconnect&response_type=code&scope=identify%20email" title="connect via Discord">Discord</a>
    <a class="OauthLink google" href="https://accounts.google.com/o/oauth2/v2/auth?scope=profile&access_type=online&response_type=code&redirect_uri=<?= urlencode('http://localhost:8000/connect-via-google')?>&client_id=<?= $_ENV['GOOGLE_CLIENT_ID'] ?>" title="connect via Google">Google</a>
    <img class="homeImg" src="/assets/img/authentication.svg" alt="" />
