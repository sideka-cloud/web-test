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
// if ip not show can use this code
//   $ip = exec("hostname -I | awk '{print $1}'");
// Check PHP version
    $phpversion = phpversion();

function getClientIP() {
    // Check for the most common header used by proxies
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_client = $_SERVER['HTTP_CLIENT_IP'];
    // Check for headers sent by the proxy server
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip_client = $_SERVER['HTTP_X_FORWARDED_FOR'];
    // Check for the remote address from the server
    } else {
        $ip_client = $_SERVER['REMOTE_ADDR'];
    }
    
    // In case of multiple IPs (comma-separated), use the first one
    $ipArray = explode(',', $ip_client);
    $ip_client = trim($ipArray[0]);
    
    return $ip_client;
}

// Get the client's IP address
$clientIP = getClientIP();


// Function to get the number of CPU cores
function getCpuCores() {
    $cpuCores = shell_exec('cat /proc/cpuinfo | grep -c "processor"');
    return trim($cpuCores);
}

// Function to get total RAM in GB
function getTotalRam() {
    $ramInfo = shell_exec('free -m | grep "Mem:"');
    $ramInfoArray = preg_split('/\s+/', trim($ramInfo));
    $totalRamMB = $ramInfoArray[1]; // Total RAM in MB
    $totalRamGB = $totalRamMB / 1024; // Convert MB to GB
    return round($totalRamGB, 2); // Round to 2 decimal places
}

// Get and display the CPU and RAM information
$cpuCores = getCpuCores();
$totalRam = getTotalRam();

// Function to get the OS name and version using php_uname
function getOsInfo() {
    $osName = php_uname('s');
    $osVersion = php_uname('r');
    return "$osName $osVersion";
}

// Function to get detailed OS version for Linux
function getLinuxOsVersion() {
    $osRelease = @file_get_contents('/etc/os-release');
    $osDetails = [];
    if ($osRelease !== false) {
        $lines = explode("\n", $osRelease);
        foreach ($lines as $line) {
            if (strpos($line, 'PRETTY_NAME') !== false) {
                $osDetails['PRETTY_NAME'] = explode('=', $line)[1];
            }
        }
    }
    return $osDetails['PRETTY_NAME'] ?? 'Unknown OS Version';
}

// Get and display the OS information
$osInfo = getOsInfo();
$osVersion = getLinuxOsVersion();

// Function to get Apache version
function getApacheVersion() {
    $apacheVersion = shell_exec('apachectl -v 2>&1');
    if (empty($apacheVersion)) {
        $apacheVersion = shell_exec('httpd -v 2>&1');
    }
    // Extract the server version from the output
    preg_match('/Server version:\s*([^\r\n]+)/', $apacheVersion, $matches);
    if (isset($matches[1])) {
        return $matches[1];
    }

    return $apacheVersion;
}

// Function to get Nginx version
function getNginxVersion() {
    $nginxVersion = shell_exec('nginx -v 2>&1');
    return $nginxVersion;
}

// Function to check if a command returns a valid response
function isRunning($output) {
    return !empty($output) && strpos($output, 'not found') === false;
}

// Get Apache and Nginx versions
$apacheVersion = getApacheVersion();
$nginxVersion = getNginxVersion();
?>

<header>
    <h1>Welcome To My Site!</h1>
    <h3>sys-ops.id</h3>
</header>
<main>
    <img src="img/cat4.png" alt="cat" />
    <p>Server hostname: <?php echo $hostname; ?></p>
    <p>Server IP address: <?php echo $ip; ?></p>
    <p>Client IP address: <?php echo $clientIP; ?></p>
    <p>CPU: <?php echo $cpuCores; ?> Core | RAM: <?php echo $totalRam ?> GB</p>
    <p>Kernel: <?php echo $osInfo; ?></p>
    <p>OS Info: <?php echo $osVersion; ?></p>
    <p>PHP version: <?php echo $phpversion; ?></p>
    <p>Web Server: <?php  if (isRunning($apacheVersion)) { echo $apacheVersion; } elseif (isRunning($nginxVersion)) { echo $nginxVersion; } else { echo "";} ?></p>
</main>
<footer>
    <h5>&copy; 2024 sys-ops.id</h5>
</footer>
</body>
</html>
