<?php

namespace App\Http\Controllers\Cron;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Log};
use App\Http\Controllers\Controller;
use App\Models\{InventoryAlert, Product};

class InventoryController extends Controller
{
    public function checkInventoryAlerts(Request $request)
    {
        // if (!$this->isAuthorizedCronRequest($request)) {
        //     return response('Unauthorized', 401);
        // }

        $this->outputHeader($request);

        $bypass = filter_var($request->get('bypass', false), FILTER_VALIDATE_BOOLEAN);
        $now = now();

        try {
            if (!$bypass && $now->format('H') !== '06') {
                $nextRun = $now->copy()->addDay()->setTime(6, 0, 0);

                $msg = "⏭️ Skipped: This task runs daily at 06:00 AM. Next schedule at {$nextRun->toDateTimeString()}";

                $this->output($msg);

                Log::info('Inventory alerts cron skipped', [
                    'reason' => 'not scheduled time',
                    'current_time' => $now->toDateTimeString(),
                    'next_run' => $nextRun->toDateTimeString(),
                ]);

                return;
            }

            $this->output("🚀 Starting inventory alerts check process...");
            $this->output("Parameters: bypass={$bypass}");

            $results = $this->generateInventoryAlerts();

            $this->displayResults($results);

            Log::info('Inventory alerts check completed successfully', [
                'bypass' => $bypass,
                'results' => $results,
                'timestamp' => $now->toDateTimeString(),
            ]);

            $this->output("✅ Inventory alerts check completed!");
        } catch (\Throwable $e) {
            $this->output("❌ Error: " . $e->getMessage());

            Log::error('Inventory alerts check failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => now()->toDateTimeString(),
            ]);
        }

        $this->output("Finished at: " . now()->toDateTimeString());
    }

    private function generateInventoryAlerts(): array
    {
        $results = [
            'total_products_checked' => 0,
            'alerts_created' => 0,
            'low_stock' => 0,
            'reorder_point' => 0,
            'max_stock' => 0,
            'details' => []
        ];

        DB::beginTransaction();

        try {
            $products = Product::where('is_active', true)->get();
            $results['total_products_checked'] = $products->count();

            $this->output("📦 Checking {$results['total_products_checked']} active products...");

            foreach ($products as $product) {
                if ($product->stock_quantity < $product->min_stock) {
                    $created = $this->createAlertIfNotExists(
                        $product,
                        'low_stock',
                        "Product '{$product->name}' is below minimum stock. Current: {$product->stock_quantity}, Min: {$product->min_stock}"
                    );

                    if ($created) {
                        $results['alerts_created']++;
                        $results['low_stock']++;
                        $results['details'][] = [
                            'product' => $product->name,
                            'type' => 'low_stock',
                            'current_stock' => $product->stock_quantity,
                            'threshold' => $product->min_stock
                        ];
                    }
                }

                if ($product->stock_quantity <= $product->reorder_point && $product->stock_quantity >= $product->min_stock) {
                    $created = $this->createAlertIfNotExists(
                        $product,
                        'reorder_point',
                        "Product '{$product->name}' has reached reorder point. Current: {$product->stock_quantity}, Reorder at: {$product->reorder_point}"
                    );

                    if ($created) {
                        $results['alerts_created']++;
                        $results['reorder_point']++;
                        $results['details'][] = [
                            'product' => $product->name,
                            'type' => 'reorder_point',
                            'current_stock' => $product->stock_quantity,
                            'threshold' => $product->reorder_point
                        ];
                    }
                }

                if ($product->stock_quantity > $product->max_stock) {
                    $created = $this->createAlertIfNotExists(
                        $product,
                        'max_stock',
                        "Product '{$product->name}' exceeds maximum stock. Current: {$product->stock_quantity}, Max: {$product->max_stock}"
                    );

                    if ($created) {
                        $results['alerts_created']++;
                        $results['max_stock']++;
                        $results['details'][] = [
                            'product' => $product->name,
                            'type' => 'max_stock',
                            'current_stock' => $product->stock_quantity,
                            'threshold' => $product->max_stock
                        ];
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $results;
    }

    private function createAlertIfNotExists(Product $product, string $alertType, string $message): bool
    {
        $existingAlert = InventoryAlert::where('product_id', $product->id)
            ->where('alert_type', $alertType)
            ->where('is_resolved', false)
            ->first();

        if ($existingAlert) {
            return false;
        }

        InventoryAlert::create([
            'product_id' => $product->id,
            'alert_type' => $alertType,
            'message' => $message,
            'is_resolved' => false,
        ]);

        return true;
    }

    private function isAuthorizedCronRequest(Request $request): bool
    {
        if (app()->environment(['local', 'development'])) {
            return true;
        }

        $cronToken = config('app.cron_token');
        if ($cronToken && $request->header('X-Cron-Token') === $cronToken) {
            return true;
        }

        $allowedIPs = config('app.cron_allowed_ips', []);
        if (!empty($allowedIPs) && in_array($request->ip(), $allowedIPs)) {
            return true;
        }

        if (app()->environment('local') && $request->ip() === '127.0.0.1') {
            return true;
        }

        return false;
    }

    private function outputHeader(Request $request): void
    {
        if (!$request->ajax()) {
            $this->output('<h3>🔍 Inventory Alerts Check - Cron Task</h3>');
            $this->output('<p>[ Schedule: Daily at 06:00 AM ]</p>');
            $this->output('<p>[ Cron cmd: <code>0 6 * * * curl --silent -H "X-Cron-Token: YOUR_TOKEN" "' . $request->fullUrl() . '" >/dev/null 2>&1</code>]</p>');
            $this->output('<p>[ Started at: ' . now()->toDateTimeString() . ']</p>');
            $this->output('<hr>');
        }
    }

    private function displayResults(array $results): void
    {
        $this->output('<h4>📊 Execution Summary:</h4>');
        $this->output("📦 Total products checked: {$results['total_products_checked']}");
        $this->output("🔔 Total alerts created: {$results['alerts_created']}");
        $this->output("  ├─ 🔴 Low Stock: {$results['low_stock']}");
        $this->output("  ├─ 🟡 Reorder Point: {$results['reorder_point']}");
        $this->output("  └─ 🟠 Max Stock (Overstock): {$results['max_stock']}");

        if ($results['alerts_created'] > 0) {
            $this->output('<h4>⚠️ Alert Details:</h4>');

            foreach ($results['details'] as $detail) {
                $icon = match ($detail['type']) {
                    'low_stock' => '🔴',
                    'reorder_point' => '🟡',
                    'max_stock' => '🟠',
                    default => '⚪'
                };

                $this->output("{$icon} {$detail['product']} - {$detail['type']} (Current: {$detail['current_stock']}, Threshold: {$detail['threshold']})");
            }
        } else {
            $this->output("✅ All products are within safe stock levels!");
        }
    }

    private function output(string $message): void
    {
        echo $message . PHP_EOL;
        if (php_sapi_name() !== 'cli') {
            echo "<br>\n";
        }
    }
}
