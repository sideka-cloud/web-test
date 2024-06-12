const express = require('express');
const os = require('os');
const { exec } = require('child_process');

const app = express();
const port = 8080;

// Serve static files from the public directory
app.use(express.static('public'));

// Middleware to get the client's IP address
app.use((req, res, next) => {
    let clientIp = req.headers['x-forwarded-for'] || req.socket.remoteAddress;    if (clientIp.startsWith('::ffff:')) {
        clientIp = clientIp.split('::ffff:')[1];
    }
    req.clientIp = clientIp;
    next();
});

// Route to get network information
app.get('/network-info', (req, res) => {
    // Get the server's IP address
    const networkInterfaces = os.networkInterfaces();
    let serverIp = '';
    for (const interfaceDetails of Object.values(networkInterfaces)) {
        for (const details of interfaceDetails) {
            if (details.family === 'IPv4' && !details.internal) {
                serverIp = details.address;
                break;
            }
        }
    }

    // Get the hostname
    const hostname = os.hostname();

    // Send network information as JSON
    res.json({
        serverIp: serverIp,
        clientIp: req.clientIp,
        hostname: hostname
    });
});

// Route to get system information including kernel version
app.get('/system-info', (req, res) => {
    // Get system information
    const systemInfo = {
        cpuCores: os.cpus().length,
        totalMemory: os.totalmem()
    };

    // Execute shell command to get kernel version
    exec('uname -r', (err, stdout, stderr) => {
        if (err) {
            console.error(`Error getting kernel version: ${err}`);
            systemInfo.kernelVersion = 'Unknown';
        } else {
            systemInfo.kernelVersion = stdout.trim();
        }

        // Send system information as JSON after kernel version is retrieved
        res.json(systemInfo);
    });
});

app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});

