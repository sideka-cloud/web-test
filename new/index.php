<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sys-ops.id</title>
    <link href="tailwind.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

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
    // Try different common commands to get the Apache version
    $commands = [
        'apachectl -v 2>&1',
        'httpd -v 2>&1',
        '/usr/sbin/httpd -v 2>&1',
        '/usr/bin/httpd -v 2>&1'
    ];

    foreach ($commands as $command) {
        $apacheVersion = shell_exec($command);
        if (!empty($apacheVersion)) {
            // Extract the server version from the output
            preg_match('/Server version:\s*(.*)/', $apacheVersion, $matches);
            if (isset($matches[1])) {
                return $matches[1];
            }
        }
    }
    
    // If all commands fail, return an empty string
    return '';
}

// Function to get Nginx version
function getNginxVersion() {
    if (file_exists('/usr/sbin/nginx')) {
        $nginxVersion = shell_exec('/usr/sbin/nginx -v 2>&1');
        if (empty($nginxVersion)) {
        $nginxVersion = shell_exec('nginx -v 2>&1');
        }
        preg_match('/nginx\/([^\s]+)/', $nginxVersion, $matches);
        if (isset($matches[0])) {
            return $matches[0];
        }
        return $nginxVersion;
    }
    return 'nginx not found';
}

// Function to check if a command returns a valid response
function isRunning($output) {
    return !empty($output) && strpos($output, 'not found') === false;
}

// Get Apache and Nginx versions
$apacheVersion = getApacheVersion();
$nginxVersion = getNginxVersion();
?>

<div class="parallax">
    <div class="gradient-background"></div>
    <div id="particles-js"></div>
    <div class="container mx-auto p-5 flex flex-wrap md:flex-nowrap items-center bg-white shadow-lg relative z-10">
        <div class="w-full md:w-1/2 p-5 order-1 md:order-1">
            <h1 class="text-4xl font-bold text-gray-800 flex justify-center items-center">SYS-OPS.ID</h1></br>
            <p class="mt-4 text-gray-600">Server Hostname: <?php echo $hostname; ?> </p>
            <p class="mt-4 text-gray-600">Server IP Address: <?php echo $ip; ?> </p>
            <p class="mt-4 text-gray-600">Client IP Address: <?php echo $clientIP; ?> </p>
            <p class="mt-4 text-gray-600">CPU: <?php echo $cpuCores; ?> Core | RAM: <?php echo $totalRam ?> GB </p>
            <p class="mt-4 text-gray-600">Kernel: <?php echo $osInfo; ?> </p>
            <p class="mt-4 text-gray-600">OS: <?php echo $osVersion; ?> </p>
            <p class="mt-4 text-gray-600">PHP Version: <?php echo $phpversion; ?> </p>
            <p class="mt-4 text-gray-600">Server: <?php  if (isRunning($apacheVersion)) { echo $apacheVersion; } elseif (isRunning($nginxVersion)) { echo $nginxVersion; } else { echo "";} ?> </p>
        </div>
        <div class="w-full md:w-1/3 p-5 order-2 md:order-2 flex justify-center items-center">
            <img src="cat.png" alt="Cat Image" class="max-w-full md:max-w-1/2 h-auto">
        </div>
    </div>
</div>

<script src="particles.min.js"></script>
<script src="script.js"></script>

</body>
</html>
