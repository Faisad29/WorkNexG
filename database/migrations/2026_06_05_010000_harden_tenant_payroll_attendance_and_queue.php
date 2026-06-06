<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_records', function (Blueprint $table): void {
            $table->uuid('override_request_id')->nullable()->after('attendance_date');
            $table->string('idempotency_key', 64)->nullable()->after('sync_status');
            $table->index(['org_id', 'employee_id', 'idempotency_key'], 'idx_attendance_idempotency');
        });

        Schema::create('attendance_override_requests', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('org_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignUuid('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('attendance_date');
            $table->string('reason', 500);
            $table->string('status')->default('override_request');
            $table->foreignUuid('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['org_id', 'employee_id', 'attendance_date'], 'idx_override_org_employee_date');
            $table->index(['org_id', 'status'], 'idx_override_org_status');
        });

        Schema::table('attendance_records', function (Blueprint $table): void {
            $table->foreign('override_request_id')
                ->references('id')
                ->on('attendance_override_requests')
                ->nullOnDelete();
        });

        Schema::table('payrolls', function (Blueprint $table): void {
            $table->timestamp('generated_at')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('generated_at');
            $table->timestamp('locked_at')->nullable()->after('approved_at');
            $table->timestamp('paid_at')->nullable()->after('locked_at');
            $table->jsonb('attendance_snapshot')->nullable()->after('paid_at');
            $table->jsonb('calculation_metadata')->nullable()->after('attendance_snapshot');
        });

        Schema::table('payroll_items', function (Blueprint $table): void {
            $table->jsonb('attendance_snapshot')->nullable()->after('status');
            $table->jsonb('calculation_metadata')->nullable()->after('attendance_snapshot');
            $table->index(['org_id', 'payroll_id'], 'idx_payroll_items_org_payroll');
        });

        Schema::create('failed_jobs', function (Blueprint $table): void {
            $table->uuid('uuid')->primary();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table): void {
            $table->dropForeign(['override_request_id']);
            $table->dropIndex('idx_attendance_idempotency');
            $table->dropColumn(['override_request_id', 'idempotency_key']);
        });

        Schema::dropIfExists('attendance_override_requests');

        Schema::table('payrolls', function (Blueprint $table): void {
            $table->dropColumn([
                'generated_at',
                'approved_at',
                'locked_at',
                'paid_at',
                'attendance_snapshot',
                'calculation_metadata',
            ]);
        });

        Schema::table('payroll_items', function (Blueprint $table): void {
            $table->dropIndex('idx_payroll_items_org_payroll');
            $table->dropColumn(['attendance_snapshot', 'calculation_metadata']);
        });

        Schema::dropIfExists('failed_jobs');
    }
};