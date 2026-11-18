<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Items Table
        if (!Schema::hasTable('items')) {
            Schema::create('items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('category')->nullable();
                $table->decimal('cost_price', 12, 2)->default(0);
                $table->decimal('price', 12, 2)->default(0);
                $table->integer('quantity')->default(0);
                $table->string('sku')->nullable()->unique();
                $table->string('brand')->nullable();
                $table->string('weight')->nullable();
                $table->string('dimensions')->nullable();
                $table->string('status')->default('publish');
                $table->json('tags')->nullable();
                $table->decimal('sale_price', 12, 2)->nullable();
                $table->decimal('avg_cost', 12, 2)->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 2. Item Batches
        if (!Schema::hasTable('item_batches')) {
            Schema::create('item_batches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
                $table->string('batch_no')->nullable();
                $table->unsignedBigInteger('purchase_id')->nullable();
                $table->decimal('cost_price', 12, 2)->default(0);
                $table->integer('quantity')->default(0);
                $table->integer('remaining_quantity')->default(0);
                $table->timestamps();
            });
        }

        // 3. Stock History
        if (!Schema::hasTable('stock_history')) {
            Schema::create('stock_history', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('item_id');
                $table->string('type')->default('in');
                $table->integer('old_quantity')->default(0);
                $table->integer('new_quantity')->default(0);
                $table->integer('change_amount')->default(0);
                $table->decimal('cost_price', 12, 2)->nullable();
                $table->unsignedBigInteger('changed_by')->nullable();
                $table->string('reason')->nullable();
                $table->timestamps();
            });
        }

        // 4. Categories
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('short_code')->nullable();
                $table->timestamps();
            });
        }

        // 5. Products
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('code')->nullable();
                $table->integer('quantity')->default(0);
                $table->integer('quantity_alert')->default(10);
                $table->decimal('buying_price', 12, 2)->default(0);
                $table->decimal('selling_price', 12, 2)->default(0);
                $table->decimal('tax', 12, 2)->default(0);
                $table->integer('tax_type')->default(0);
                $table->text('notes')->nullable();
                $table->string('product_image')->nullable();
                $table->unsignedBigInteger('category_id')->nullable();
                $table->unsignedBigInteger('unit_id')->nullable();
                $table->timestamps();
            });
        }

        // 6. Invoices
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->date('invoice_date')->nullable();
                $table->string('invoice_no')->nullable();
                $table->unsignedBigInteger('product_id')->nullable();
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->decimal('total_profit', 12, 2)->default(0);
                $table->string('payment_mode')->nullable();
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('status')->nullable();
                $table->timestamps();
            });
        }

        // 7. Invoice Items
        if (!Schema::hasTable('invoice_items')) {
            Schema::create('invoice_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('invoice_id');
                $table->unsignedBigInteger('product_id');
                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 12, 2)->default(0);
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('profit', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        // 8. Expenses
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('store_id')->nullable();
                $table->string('category')->nullable();
                $table->decimal('amount', 12, 2)->default(0);
                $table->date('expense_date')->nullable();
                $table->text('note')->nullable();
                $table->timestamps();
            });
        }

        // 9. Purchases
        if (!Schema::hasTable('purchases')) {
            Schema::create('purchases', function (Blueprint $table) {
                $table->id();
                $table->date('purchase_date')->nullable();
                $table->string('purchase_no')->nullable();
                $table->unsignedBigInteger('supplier_id')->nullable();
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 10. Supplier Returns
        if (!Schema::hasTable('supplier_returns')) {
            Schema::create('supplier_returns', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('store_id')->nullable();
                $table->date('return_date')->nullable();
                $table->decimal('total_refund_amount', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_returns');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('stock_history');
        Schema::dropIfExists('item_batches');
        Schema::dropIfExists('items');
    }
};
