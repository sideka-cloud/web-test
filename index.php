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
  ?>
  <header>
    <h1>Welcome to my page!</h1>
  </header>
  <main>
    <img src="img/cat1.png" alt="cat" />
    <p>Server hostname: <?php echo $hostname; ?></p>
    <p>Server IP address: <?php echo $ip; ?></p>
  </main>
</body>
</html>
