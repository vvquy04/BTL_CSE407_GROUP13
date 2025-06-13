<?php

/**
 * Checkout Page Test Script
 * Kiểm tra trực tiếp trang checkout
 */

echo "=== KIỂM TRA TRANG CHECKOUT ===\n\n";

$testUrl = 'http://127.0.0.1:8000/checkout';

// Test 1: Kiểm tra server có đang chạy
echo "1. Kiểm tra server Laravel...\n";
$serverTest = @file_get_contents('http://127.0.0.1:8000/', false, stream_context_create([
    'http' => ['timeout' => 5, 'ignore_errors' => true]
]));

if ($serverTest !== false) {
    echo "✓ Server Laravel đang hoạt động\n";
} else {
    echo "✗ Server Laravel không hoạt động\n";
    exit(1);
}

// Test 2: Kiểm tra trang checkout
echo "\n2. Kiểm tra trang checkout...\n";
$checkoutTest = @file_get_contents($testUrl, false, stream_context_create([
    'http' => [
        'timeout' => 10,
        'ignore_errors' => true,
        'method' => 'GET',
        'header' => [
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        ]
    ]
]));

if ($checkoutTest !== false) {
    echo "✓ Trang checkout có thể truy cập\n";
    
    // Kiểm tra nội dung response
    if (strpos($checkoutTest, 'Thông tin giao hàng') !== false) {
        echo "✓ Nội dung trang checkout hợp lệ\n";
    } else {
        echo "⚠ Có thể trang checkout hiển thị không đúng\n";
        echo "Nội dung trả về (100 ký tự đầu): " . substr($checkoutTest, 0, 100) . "...\n";
    }
} else {
    echo "✗ Không thể truy cập trang checkout\n";
    
    // Kiểm tra response headers
    if (isset($http_response_header)) {
        echo "Response Headers:\n";
        foreach ($http_response_header as $header) {
            echo "  $header\n";
        }
    }
}

// Test 3: Kiểm tra các route liên quan
echo "\n3. Kiểm tra các route liên quan...\n";

$relatedRoutes = [
    '/gio-hang' => 'Trang giỏ hàng',
    '/login-checkout' => 'Trang đăng nhập checkout'
];

foreach ($relatedRoutes as $route => $description) {
    $url = 'http://127.0.0.1:8000' . $route;
    $result = @file_get_contents($url, false, stream_context_create([
        'http' => ['timeout' => 5, 'ignore_errors' => true]
    ]));
    
    if ($result !== false) {
        echo "✓ $description: Hoạt động\n";
    } else {
        echo "✗ $description: Lỗi\n";
    }
}

echo "\n4. Hướng dẫn kiểm tra thủ công...\n";
echo "- Mở trình duyệt và truy cập: $testUrl\n";
echo "- Kiểm tra Console (F12) để xem lỗi JavaScript\n";
echo "- Kiểm tra Network tab để xem request/response\n";
echo "- Kiểm tra file log Laravel: storage/logs/laravel.log\n";

echo "\n=== KẾT THÚC KIỂM TRA ===\n";
?>
