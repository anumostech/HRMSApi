@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Late Warning Notification</div>
                <div class="card-body">
                    <h2>Warning: Multiple Late Entries</h2>
                    <p>Dear HR/Admin,</p>
                    <p>This is to notify you that the following employee has been late 3 or more times during this month.</p>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
                        <p><strong>Employee Name:</strong> {{ $employee->name }}</p>
                        <p><strong>Department:</strong> {{ $employee->department }}</p>
                        <p><strong>Late Count This Month:</strong> {{ $lateCount }}</p>
                    </div>

                    <p>Please take necessary action as per company policy.</p>
                    
                    <p>Best regards,<br>{{ config('app.name') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
