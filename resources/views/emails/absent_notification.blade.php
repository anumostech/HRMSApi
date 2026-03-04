@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Absent Notification</div>
                <div class="card-body">
                    <h2>Notification: Employee Absent Today</h2>
                    <p>Dear HR/Admin,</p>
                    <p>This is to notify you that the following employee has not punched in today.</p>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
                        <p><strong>Employee Name:</strong> {{ $employee->name }}</p>
                        <p><strong>Department:</strong> {{ $employee->department }}</p>
                        <p><strong>Date:</strong> {{ date('d-m-Y') }}</p>
                    </div>

                    <p>Please check for any approved leave or follow up with the employee.</p>
                    
                    <p>Best regards,<br>{{ config('app.name') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
