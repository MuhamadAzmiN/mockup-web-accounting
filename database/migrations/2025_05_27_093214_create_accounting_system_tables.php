<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Create companies table
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('updated_by')->nullable();
        });

        // Create branches table
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('code');
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('updated_by')->nullable();
            
            $table->unique(['company_id', 'code'], 'branches_code_unique');
        });

        // Create accounts table
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->enum('type', ['Asset', 'Liabilitas', 'Ekuitas', 'Pendapatan', 'Beban']);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('updated_by')->nullable();
            $table->unsignedInteger('branch_id')->nullable();
            
            $table->foreign('parent_id')->references('id')->on('accounts')->onDelete('set null');
            $table->foreign('branch_id')->references('id')->on('branches');
        });

        // Create journal_event_templates table
        Schema::create('journal_event_templates', function (Blueprint $table) {
            $table->id();
            $table->string('event_code')->unique();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('updated_by')->nullable();
        });

        // Create journal_event_lines table
        Schema::create('journal_event_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('journal_event_templates')->onDelete('cascade');
            $table->foreignId('account_id')->constrained('accounts');
            $table->boolean('is_debit');
            $table->string('formula');
            $table->integer('position')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('created_by')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('updated_by')->nullable();
        });

        // Create journals table
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->date('journal_date');
            $table->text('description')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('created_by')->nullable();
            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('branch_id')->references('id')->on('branches');
        });

        // Create journal_entries table
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('journal_id')->nullable();
            $table->unsignedInteger('account_id')->nullable();
            $table->decimal('debit', 14, 2)->default(0);
            $table->decimal('credit', 14, 2)->default(0);
            $table->string('source_event')->nullable();
            $table->string('source_ref_id')->nullable();
            
            $table->foreign('journal_id')->references('id')->on('journals')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts');
        });

        // Create views
        $this->createViews();
        
        // Insert sample data
        $this->insertSampleData();
    }

    public function down()
{
    // Drop views first
    DB::statement('DROP VIEW IF EXISTS vw_trial_balance');
    DB::statement('DROP VIEW IF EXISTS vw_profit_and_loss');
    DB::statement('DROP VIEW IF EXISTS vw_general_ledger');
    DB::statement('DROP VIEW IF EXISTS vw_balance_sheet_summary');
    DB::statement('DROP VIEW IF EXISTS vw_balance_sheet_horizontal');
    DB::statement('DROP VIEW IF EXISTS vw_balance_sheet');
    
    // Drop tables in reverse order
    Schema::dropIfExists('journal_entries');
    Schema::dropIfExists('journals');
    Schema::dropIfExists('journal_event_lines');
    Schema::dropIfExists('journal_event_templates');
    Schema::dropIfExists('accounts');
    Schema::dropIfExists('branches');
    Schema::dropIfExists('companies');
}

private function createViews()
{
    // Create vw_balance_sheet view
    DB::statement("
        CREATE OR REPLACE VIEW vw_balance_sheet AS
        SELECT 
            a.id AS account_id,
            a.code AS account_code,
            a.name AS account_name,
            a.type AS account_type,
            j.company_id,
            j.branch_id,
            SUM(je.debit) AS total_debit,
            SUM(je.credit) AS total_credit,
            SUM(je.debit - je.credit) AS balance
        FROM journal_entries je
        JOIN journals j ON je.journal_id = j.id
        JOIN accounts a ON je.account_id = a.id
        WHERE a.type IN ('Asset', 'Liabilitas', 'Ekuitas')
        GROUP BY a.id, a.code, a.name, a.type, j.company_id, j.branch_id
    ");

    // Create vw_balance_sheet_horizontal view
    DB::statement("
        CREATE OR REPLACE VIEW vw_balance_sheet_horizontal AS
        WITH raw_data AS (
            SELECT 
                a.id AS account_id,
                a.code AS account_code,
                a.name AS account_name,
                a.type AS account_type,
                j.company_id,
                j.branch_id,
                COALESCE(SUM(je.debit), 0) AS total_debit,
                COALESCE(SUM(je.credit), 0) AS total_credit,
                COALESCE(SUM(je.debit - je.credit), 0) AS balance
            FROM journal_entries je
            JOIN accounts a ON je.account_id = a.id
            JOIN journals j ON je.journal_id = j.id
            WHERE a.type IN ('Asset', 'Liabilitas', 'Ekuitas')
            GROUP BY a.id, a.code, a.name, a.type, j.company_id, j.branch_id
        )
        SELECT
            raw_data.company_id,
            raw_data.branch_id,
            CASE WHEN raw_data.account_type = 'Asset' THEN raw_data.account_code ELSE NULL END AS asset_code,
            CASE WHEN raw_data.account_type = 'Asset' THEN raw_data.account_name ELSE NULL END AS asset_name,
            CASE WHEN raw_data.account_type = 'Asset' THEN raw_data.balance ELSE NULL END AS asset_balance,
            CASE WHEN raw_data.account_type IN ('Liabilitas', 'Ekuitas') THEN raw_data.account_code ELSE NULL END AS liability_equity_code,
            CASE WHEN raw_data.account_type IN ('Liabilitas', 'Ekuitas') THEN raw_data.account_name ELSE NULL END AS liability_equity_name,
            CASE WHEN raw_data.account_type IN ('Liabilitas', 'Ekuitas') THEN raw_data.balance ELSE NULL END AS liability_equity_balance
        FROM raw_data
        ORDER BY raw_data.company_id, raw_data.branch_id, raw_data.account_type, raw_data.account_code
    ");

    // Create vw_balance_sheet_summary view
    DB::statement("
        CREATE OR REPLACE VIEW vw_balance_sheet_summary AS
        SELECT 
            j.company_id,
            j.branch_id,
            SUM(CASE WHEN a.type = 'Asset' THEN je.debit - je.credit ELSE 0 END) AS total_asset,
            SUM(CASE WHEN a.type IN ('Liabilitas', 'Ekuitas') THEN je.credit - je.debit ELSE 0 END) AS total_liability_equity
        FROM journal_entries je
        JOIN accounts a ON je.account_id = a.id
        JOIN journals j ON je.journal_id = j.id
        GROUP BY j.company_id, j.branch_id
    ");

    // Create vw_general_ledger view
    DB::statement("
        CREATE OR REPLACE VIEW vw_general_ledger AS
        SELECT 
            a.id AS account_id,
            a.code AS account_code,
            a.name AS account_name,
            j.id AS journal_id,
            j.journal_date,
            j.description AS journal_description,
            j.company_id,
            j.branch_id,
            je.debit,
            je.credit,
            SUM(je.debit - je.credit) OVER (
                PARTITION BY j.company_id, j.branch_id, a.id 
                ORDER BY j.journal_date, je.id 
                ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
            ) AS running_balance
        FROM journal_entries je
        JOIN journals j ON je.journal_id = j.id
        JOIN accounts a ON je.account_id = a.id
    ");

    // Create vw_profit_and_loss view
        DB::statement("
            CREATE OR REPLACE VIEW vw_profit_and_loss AS
            SELECT 
                a.id AS account_id,
                a.code AS account_code,
                a.name AS account_name,
                a.type AS account_type,
                j.company_id,
                j.branch_id,
                SUM(je.debit) AS total_debit,
                SUM(je.credit) AS total_credit,
                CASE
                    WHEN a.type = 'Pendapatan' THEN SUM(je.credit - je.debit)
                    WHEN a.type = 'Beban' THEN SUM(je.debit - je.credit)
                    ELSE 0
                END AS net_value
            FROM journal_entries je
            JOIN accounts a ON je.account_id = a.id
            JOIN journals j ON je.journal_id = j.id
            WHERE a.type IN ('Pendapatan', 'Beban')
            GROUP BY a.id, a.code, a.name, a.type, j.company_id, j.branch_id
        ");

    // Create vw_trial_balance view
    DB::statement("
        CREATE OR REPLACE VIEW vw_trial_balance AS
        SELECT 
            a.id AS account_id,
            a.code AS account_code,
            a.name AS account_name,
            a.type AS account_type,
            j.company_id,
            j.branch_id,
            SUM(je.debit) AS total_debit,
            SUM(je.credit) AS total_credit,
            SUM(je.debit - je.credit) AS balance
        FROM journal_entries je
        JOIN accounts a ON je.account_id = a.id
        JOIN journals j ON je.journal_id = j.id
        GROUP BY a.id, a.code, a.name, a.type, j.company_id, j.branch_id
    ");
}

private function insertSampleData()
{
    // Insert accounts
    DB::table('accounts')->insert([
        ['id' => 101, 'code' => '1-101', 'name' => 'Kas', 'type' => 'Asset', 'parent_id' => null, 'created_by' => 'system'],
        ['id' => 102, 'code' => '1-102', 'name' => 'Piutang Usaha', 'type' => 'Asset', 'parent_id' => null, 'created_by' => 'system'],
        ['id' => 201, 'code' => '2-201', 'name' => 'Utang Usaha', 'type' => 'Liabilitas', 'parent_id' => null, 'created_by' => 'system'],
        ['id' => 301, 'code' => '3-301', 'name' => 'Modal Pemilik', 'type' => 'Ekuitas', 'parent_id' => null, 'created_by' => 'system'],
        ['id' => 401, 'code' => '4-401', 'name' => 'Pendapatan Penjualan', 'type' => 'Pendapatan', 'parent_id' => null, 'created_by' => 'system'],
        ['id' => 501, 'code' => '5-501', 'name' => 'Beban ATK', 'type' => 'Beban', 'parent_id' => null, 'created_by' => 'system'],
    ]);

    // Insert companies
    DB::table('companies')->insert([
        ['id' => 1, 'code' => 'CMP001', 'name' => 'PT Satu Maju', 'address' => 'Jl. Sudirman No.1', 'phone' => '02112345678', 'email' => 'info@satumaju.co.id', 'created_by' => 'system'],
        ['id' => 2, 'code' => 'CMP002', 'name' => 'PT Dua Jaya', 'address' => 'Jl. Thamrin No.2', 'phone' => '02187654321', 'email' => 'contact@duajaya.com', 'created_by' => 'system'],
    ]);

    // Insert branches
    DB::table('branches')->insert([
        ['id' => 1, 'company_id' => 1, 'code' => 'BR001', 'name' => 'Cabang Jakarta', 'address' => 'Jakarta Pusat', 'phone' => '021112233', 'email' => 'jakarta@satumaju.co.id', 'created_by' => 'system'],
        ['id' => 2, 'company_id' => 1, 'code' => 'BR002', 'name' => 'Cabang Bandung', 'address' => 'Bandung', 'phone' => '022334455', 'email' => 'bandung@satumaju.co.id', 'created_by' => 'system'],
        ['id' => 3, 'company_id' => 2, 'code' => 'BR001', 'name' => 'Cabang Surabaya', 'address' => 'Surabaya', 'phone' => '031556677', 'email' => 'surabaya@duajaya.com', 'created_by' => 'system'],
    ]);

    // Insert journal event templates
    DB::table('journal_event_templates')->insert([
        ['id' => 1, 'event_code' => 'PEMBELIAN_TUNAI', 'description' => 'Pembelian barang secara tunai', 'created_by' => 'system'],
        ['id' => 2, 'event_code' => 'PENJUALAN_TUNAI', 'description' => 'Penjualan barang secara tunai', 'created_by' => 'system'],
        ['id' => 3, 'event_code' => 'PEMBAYARAN_UTANG', 'description' => 'Pembayaran utang ke supplier', 'created_by' => 'system'],
        ['id' => 4, 'event_code' => 'PENERIMAAN_PIUTANG', 'description' => 'Penerimaan pembayaran dari pelanggan', 'created_by' => 'system'],
    ]);

    // Insert journal event lines
    DB::table('journal_event_lines')->insert([
        ['id' => 1, 'template_id' => 1, 'account_id' => 501, 'is_debit' => true, 'formula' => 'amount', 'position' => 1, 'created_by' => 'system'],
        ['id' => 2, 'template_id' => 1, 'account_id' => 101, 'is_debit' => false, 'formula' => 'amount', 'position' => 2, 'created_by' => 'system'],
        ['id' => 3, 'template_id' => 2, 'account_id' => 101, 'is_debit' => true, 'formula' => 'amount', 'position' => 1, 'created_by' => 'system'],
        ['id' => 4, 'template_id' => 2, 'account_id' => 401, 'is_debit' => false, 'formula' => 'amount', 'position' => 2, 'created_by' => 'system'],
        ['id' => 5, 'template_id' => 3, 'account_id' => 201, 'is_debit' => true, 'formula' => 'amount', 'position' => 1, 'created_by' => 'system'],
        ['id' => 6, 'template_id' => 3, 'account_id' => 101, 'is_debit' => false, 'formula' => 'amount', 'position' => 2, 'created_by' => 'system'],
        ['id' => 7, 'template_id' => 4, 'account_id' => 101, 'is_debit' => true, 'formula' => 'amount', 'position' => 1, 'created_by' => 'system'],
        ['id' => 8, 'template_id' => 4, 'account_id' => 102, 'is_debit' => false, 'formula' => 'amount', 'position' => 2, 'created_by' => 'system'],
    ]);

    // Insert journals
    // DB::table('journals')->insert([
    //     ['id' => 1, 'journal_date' => '2025-05-23', 'description' => 'Penjualan tunai 1.000.000', 'created_by' => 'system', 'company_id' => 1, 'branch_id' => 1],
    // ]);

    // // Insert journal entries
    // DB::table('journal_entries')->insert([
    //     ['id' => 1, 'journal_id' => 1, 'account_id' => 101, 'debit' => 1000000.00, 'credit' => 0.00, 'source_event' => 'PENJUALAN_TUNAI', 'source_ref_id' => 'INV-001'],
    //     ['id' => 2, 'journal_id' => 1, 'account_id' => 401, 'debit' => 0.00, 'credit' => 1000000.00, 'source_event' => 'PENJUALAN_TUNAI', 'source_ref_id' => 'INV-001'],
    // ]);
}
};