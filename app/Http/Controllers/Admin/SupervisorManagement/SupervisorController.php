<?php

namespace App\Http\Controllers\Admin\SupervisorManagement;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupervisorStoreRequest;

class SupervisorController extends Controller
{
    public function index()
    {
        //
    }

    public function store(SupervisorStoreRequest $request)
    {
        // Admin::create($request->validated());
        $this->returnSuccess('Supervisor added successfully');
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
