<?php
  session_start();
?>

<?php if(!isset($_SESSION['username'])) : ?>
  <p>You're not logged in so you can't see the secret</p>
  <?php elseif(isset($_SESSION['username'])) : ?>
    <p class="secretWelcome">You made it <?= $_SESSION['username'] ?>!</p>
    <p class="secret">
      As promised, here is the story of this project:</br></br>
      I had a job interview and was told that I may have to take a test and that the subject of the test was going to be <a href="https://oauth.net/2/" title="https://oauth.net/2/" target="_blank" rel="noopener noreferrer">Oauth 2.0</a>.</br>
      I knew nothing about it so I did some searching for two days ("oh I see, that's the thing we use every day!").</br>
      Eventually, I was not given the opportunity to take the test.</br>
      So I thought, why not make the best of it and use the newly acquired knowledge to build a mini-project that can show recruiters that even though I don't know it all I am capable of learning :)</br></br>
      You can check out the project's code <a href="https://github.com/Liza-T-XLS/Oauth2" title="https://github.com/Liza-T-XLS/Oauth2" target="_blank" rel="noopener noreferrer">here</a> and my résumé <a href="https://liza-t-xls.netlify.app/index-eng.html" title="Liza-t-xls" target="_blank" rel="noopener noreferrer">here</a>.
    </p>
<?php endif; ?>