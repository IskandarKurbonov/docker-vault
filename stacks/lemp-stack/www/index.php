<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LEMP Stack - Docker Vault</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 800px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1em;
        }
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .status-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        .status-card h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.1em;
        }
        .status-card p {
            color: #666;
            font-size: 0.9em;
        }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info-section {
            background: #e9ecef;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .info-section h3 {
            color: #333;
            margin-bottom: 15px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #555;
        }
        .value {
            color: #667eea;
            font-family: 'Courier New', monospace;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 LEMP Stack Running!</h1>
        <p class="subtitle">Docker Vault - Production Ready Configuration</p>

        <div class="status-grid">
            <?php
            // Test MySQL Connection
            $mysql_status = 'error';
            try {
                $host = getenv('MYSQL_HOST') ?: 'mysql';
                $db = getenv('MYSQL_DATABASE') ?: 'myapp';
                $user = getenv('MYSQL_USER') ?: 'myuser';
                $pass = getenv('MYSQL_PASSWORD') ?: '';

                $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
                $mysql_status = 'success';
                $mysql_version = $pdo->query('SELECT VERSION()')->fetchColumn();
            } catch (PDOException $e) {
                $mysql_error = $e->getMessage();
                $mysql_version = 'N/A';
            }
            ?>

            <div class="status-card">
                <h3>MySQL</h3>
                <p class="<?php echo $mysql_status; ?>">
                    <?php echo $mysql_status === 'success' ? '✓ Connected' : '✗ Failed'; ?>
                </p>
                <p style="margin-top: 5px; font-size: 0.8em;">
                    Version: <?php echo $mysql_version; ?>
                </p>
            </div>

            <?php
            // Test Redis Connection
            $redis_status = 'error';
            try {
                $redis_host = getenv('REDIS_HOST') ?: 'redis';
                $redis = new Redis();
                $redis->connect($redis_host, 6379);
                $redis_status = 'success';
                $redis_info = $redis->info();
                $redis_version = $redis_info['redis_version'] ?? 'Unknown';
            } catch (Exception $e) {
                $redis_error = $e->getMessage();
                $redis_version = 'N/A';
            }
            ?>

            <div class="status-card">
                <h3>Redis</h3>
                <p class="<?php echo $redis_status; ?>">
                    <?php echo $redis_status === 'success' ? '✓ Connected' : '✗ Failed'; ?>
                </p>
                <p style="margin-top: 5px; font-size: 0.8em;">
                    Version: <?php echo $redis_version; ?>
                </p>
            </div>

            <div class="status-card">
                <h3>PHP</h3>
                <p class="success">✓ Running</p>
                <p style="margin-top: 5px; font-size: 0.8em;">
                    Version: <?php echo PHP_VERSION; ?>
                </p>
            </div>

            <div class="status-card">
                <h3>Nginx</h3>
                <p class="success">✓ Running</p>
                <p style="margin-top: 5px; font-size: 0.8em;">
                    Reverse Proxy
                </p>
            </div>
        </div>

        <div class="info-section">
            <h3>System Information</h3>
            <div class="info-item">
                <span class="label">Server Software:</span>
                <span class="value"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></span>
            </div>
            <div class="info-item">
                <span class="label">PHP Version:</span>
                <span class="value"><?php echo PHP_VERSION; ?></span>
            </div>
            <div class="info-item">
                <span class="label">Document Root:</span>
                <span class="value"><?php echo $_SERVER['DOCUMENT_ROOT']; ?></span>
            </div>
            <div class="info-item">
                <span class="label">Server Time:</span>
                <span class="value"><?php echo date('Y-m-d H:i:s'); ?></span>
            </div>
        </div>

        <div class="info-section">
            <h3>Loaded PHP Extensions</h3>
            <div style="margin-top: 10px;">
                <?php
                $extensions = get_loaded_extensions();
                $important_extensions = ['mysqli', 'pdo_mysql', 'redis', 'gd', 'zip', 'opcache', 'mbstring'];
                foreach ($important_extensions as $ext) {
                    $loaded = in_array($ext, $extensions);
                    echo '<span class="badge ' . ($loaded ? 'badge-success' : 'badge-danger') . '">';
                    echo ($loaded ? '✓' : '✗') . ' ' . $ext;
                    echo '</span> ';
                }
                ?>
            </div>
        </div>

        <div class="footer">
            <p><strong>Docker Vault</strong> by Iskandar Kurbonov</p>
            <p style="margin-top: 10px; font-size: 0.9em;">
                Production-ready Docker Compose stacks
            </p>
        </div>
    </div>
</body>
</html>
