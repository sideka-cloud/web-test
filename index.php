<!DOCTYPE html>
<html>
<head>
  <title>sys-ops.id</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <?php
  // Get server hostname and IP address
  $hostname = gethostname();
  $ip = ($_SERVER['SERVER_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_ADDR'] === '::1') ? $_SERVER['REMOTE_ADDR'] : $_SERVER['SERVER_ADDR'];
  // Check PHP version
  $phpversion = phpversion();
  ?>
  <header>
    <h1>Welcome To My Site!</h1>
    <h3>image v4 - Feb 2024</h3>
  </header>
  <main>
    <img src="img/cat4.png" alt="cat" />
    <p>Server hostname: <?php echo $hostname; ?></p>
    <p>Server IP address: <?php echo $ip; ?></p>
    <p>PHP version: <?php echo $phpversion; ?></p>
  </main>
  <footer>
    <h5>&copy; 2024 sys-ops.id</h5>
  </footer>
</body>
</html>
