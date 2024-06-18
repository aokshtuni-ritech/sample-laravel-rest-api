<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminEmployeeController extends Controller
{
    public function getAll(Request $request)
    {
        $employees = Employee::query()
            ->orderBy('updated_at', 'DESC')
            ->get();

        return $this->respond(EmployeeResource::collection($employees));
    }
    public function show(Employee $employee)
    {
        return $this->respond(
            new EmployeeResource($employee)
        );
    }
}
