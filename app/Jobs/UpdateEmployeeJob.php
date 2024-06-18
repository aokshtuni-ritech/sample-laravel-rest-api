<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateEmployeeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Employee $employee
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(EmployeeService $service): void
    {
        $service->updateEmployeeToIntegration($this->employee);
    }
}
