<?php

use App\Enums\RequestType;
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
        Schema::create('request_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('integration_id')->nullable();
            $table->enum('type', RequestType::values())
                ->default(RequestType::POST->value);
            $table->mediumText('uri');
            $table->json('payload');
            $table->char('status', 3)->nullable();
            $table->text('response')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->nullOnDelete();

            $table->foreign('integration_id')
                ->references('id')
                ->on('integrations')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_logs');
    }
};
