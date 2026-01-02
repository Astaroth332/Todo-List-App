<?php
// env.php — pure PHP, no Composer required

function load_env($path = '.env')
{
    if (!file_exists($path)) {
        die(".env file not found at: $path");
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        // Skip comments
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        // Only process lines that look like KEY=VALUE
        if (strpos($line, '=') === false) {
            continue;
        }

        list($key, $value) = explode('=', $line, 2);

        $key   = trim($key);
        $value = trim($value);

        // Remove surrounding quotes if exist
        if (preg_match('/^"(.*)"$/', $value, $m)) $value = $m[1];
        if (preg_match("/^'(.*)'$/", $value, $m)) $value = $m[1];

        // Put into environment (so $_ENV and getenv() work)
        if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

// Load it automatically
load_env(__DIR__ . '/.env');   // change path if .env is somewhere else