<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Mail\LowStockSummaryMail;
use Illuminate\Support\Facades\Mail;

class SendLowStockEmails extends Command
{
    protected $signature = 'notify:lowstock
                            {--threshold=10 : Quantity threshold for low stock}
                            {--limit=50 : Limit number of items per email}';

    protected $description = 'Send low-stock alert email immediately to admin when cron runs.';

    public function handle()
    {
        $threshold = (int) $this->option('threshold');
        $limit = (int) $this->option('limit');

        // 🔍 Find items below threshold
      
        $lowStockItems = Item::lowStock()->get();

        if ($lowStockItems->isEmpty()) {
            $this->info('✅ No low-stock items found. Nothing to email.');
            return 0;
        }

        // 📨 Single admin email (configure below or use .env)
        // $adminEmail = "mehlakrish07@gmail.com"; 
        $adminName  = config('app.admin_name', 'Admin');
        $adminEmail = config('app.admin_email', 'hs875147@gmail.com');

        // ✅ Limit number of items in email (optional)
        $itemsToSend = $lowStockItems->take($limit);

        // Send immediately (not queued)
        Mail::to($adminEmail)->send(new LowStockSummaryMail($itemsToSend, $adminName));

        $this->info("📧 Low-stock email sent successfully to: {$adminEmail}");
        $this->info('✅ Command completed.');

        return 0;
    }
}
