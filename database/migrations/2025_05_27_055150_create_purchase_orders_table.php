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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id(); // ID PO
            $table->string('po_number')->unique(); // Nomor Purchase Order
            $table->date('order_date'); // Tanggal dibuatp
            $table->decimal('total_amount', 18, 2)->default(0); // Total pembelian
            $table->string('company_id')->nullable(); // Nama Perusahaan
            $table->foreignId('created_by')->nullable();
            $table->foreignId('branch_id')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
