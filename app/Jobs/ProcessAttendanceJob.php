<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\AttendanceUpload;
use App\Models\AttendanceLog;
use Carbon\Carbon;

class ProcessAttendanceJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $uploadId;
    public $companyId;

    public function __construct($uploadId, $companyId)
    {
        $this->uploadId = $uploadId;
        $this->companyId = $companyId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $upload = AttendanceUpload::find($this->uploadId);

        if (!$upload) {
            return;
        }

        $upload->update(['status' => 'processing']);

        $path = storage_path('app/private/' . $upload->file_path);

        $handle = fopen($path, "r");

        $data = [];

        $total = 0;

        // First pass → count lines
        while (fgets($handle)) {
            $total++;
        }

        rewind($handle);

        $upload->update(['total_records' => $total]);

        $processed = 0;
        $grouped = [];

        while (($line = fgets($handle)) !== false) {

            $line = trim($line);
            if (!$line) continue;

            $parts = preg_split('/\s+/', $line);

            if (count($parts) < 3) continue;

            $userid = $parts[0];
            $timestamp = $parts[1] . ' ' . $parts[2];

            $date = Carbon::parse($timestamp)->format('Y-m-d');

            $key = $userid . '_' . $date;

            $grouped[$key][] = $timestamp;

            $processed++;

            //Update progress every 100 records
            if ($processed % 100 == 0) {
                $upload->update([
                    'processed_records' => $processed,
                    'progress' => round(($processed / $total) * 100)
                ]);
            }
        }

        fclose($handle);

        // Process grouped data
        foreach ($grouped as $key => $timestamps) {

            sort($timestamps);

            [$userid, $date] = explode('_', $key);

            $punchIn = $timestamps[0];
            $punchOut = count($timestamps) > 1 ? end($timestamps) : null;

            AttendanceLog::updateOrCreate(
                [
                    'userid' => $userid,
                    'log_date' => $date,
                    'company_id' => $this->companyId
                ],
                [
                    'punch_in' => $punchIn,
                    'punch_out' => $punchOut
                ]
            );
        }

        $upload->update([
            'status' => 'completed',
            'progress' => 100
        ]);
    }
}
