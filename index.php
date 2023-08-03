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
  $ip = $_SERVER['SERVER_ADDR'];
  // Check PHP version
  $phpversion = phpversion();
  ?>
  <header>
    <h1>Welcome To My Page!</h1>
    <h3>image v2</h3>
  </header>
  <main>
    <img src="img/cat1.png" alt="cat" />
    <p>Server hostname: <?php echo $hostname; ?></p>
    <p>Server IP address: <?php echo $ip; ?></p>
    <p>PHP version: <?php echo $phpversion; ?></p>
  </main>
  <footer>
    <p>&copy; 2023 sys-ops.id</p>
  </footer>
</body>
</html>
