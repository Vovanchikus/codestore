<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Samvol\Catalog\Models\Item;

$limit = 20;
$found = [];

try {
    $query = Item::query();
    $items = $query->get();
    foreach ($items as $item) {
        $data = $item->data;
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            if (is_array($decoded)) {
                $data = $decoded;
            } else {
                $data = null;
            }
        }
        if (is_array($data) && !empty($data['_update_history'])) {
            $history = $data['_update_history'];
            $found[] = [
                'id' => $item->id,
                'display_name' => $item->display_name,
                'history_raw' => $history,
            ];
            if (count($found) >= $limit) {
                break;
            }
        }
    }
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

echo json_encode($found, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
