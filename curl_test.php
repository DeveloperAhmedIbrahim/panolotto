<?php
echo "PHP Version: " . phpversion() . "<br>";
echo "Project Path: " . __DIR__ . "<br>";

curl_init();

if (function_exists('curl_init')) {
    echo "✅ cURL is available<br>";
} else {
    echo "❌ cURL is NOT available<br>";
}

// Check if Laravel HTTP is available
try {
    $response = \Illuminate\Support\Facades\Http::get('https://httpbin.org/get');
    echo "✅ Laravel HTTP Client works<br>";
} catch (\Exception $e) {
    echo "❌ Laravel HTTP Client error: " . $e->getMessage() . "<br>";
}
?>