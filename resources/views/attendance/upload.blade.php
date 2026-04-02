@extends('layouts.app')

@section('title', 'Upload Attendance')

@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    <h1 class="page-title">Upload Attendance</h1>
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Attendance</a></li>
            <li class="breadcrumb-item active" aria-current="page">Upload</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->

<div class="row">
    <div class="col-lg-6 offset-lg-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Attendance File (.dat / .csv)</h3>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form id="attendanceUploadForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="company_id" class="form-label">Select Company</label>
                        <div class="select-wrapper">
                            <select name="company_id" id="company_id" class="form-control" required>
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="file" class="form-label">Attendance File</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".dat,.csv" required>
                        <small class="text-muted">Accepted formats: .dat (space-separated) or .csv</small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Process File</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Progress -->
<div class="mt-4 d-none" id="progressSection">
    <div class="progress">
        <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-green-1" style="width:0%" style="color:green;background:green;"></div>
    </div>
    <div class="text-center mt-2">
        <span id="progressText">0%</span>
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.getElementById('attendanceUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        axios.post("{{ route('attendance.store') }}", formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {

                document.getElementById('progressSection').classList.remove('d-none');

                checkProgress(response.data.upload_id);
            })
            .catch(function(error) {
                console.error(error);
                alert('Upload failed');
            });
    });


    const progressUrl = "{{ route('attendance.progress', ':id') }}";

    function checkProgress(id) {

        let interval = setInterval(function() {

            axios.get(progressUrl.replace(':id', id))
                .then(function(response) {

                    let res = response.data;

                    document.getElementById('progressBar').style.width = res.progress + '%';
                    document.getElementById('progressText').innerText = res.progress + '%';

                    if (res.status === 'completed') {
                        clearInterval(interval);
                        location.reload();
                    }

                })
                .catch(function(error) {
                    console.error(error);
                });

        }, 2000);
    }
</script>
@endsection