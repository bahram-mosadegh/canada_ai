<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\CaseFile;

class AnalyseFileAI implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $file_id)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = CaseFile::find($this->file_id);

        if ($file->status == 'pending') {
            sleep(2);

            $file->update([
                'status' => 'analysing'
            ]);

            sleep(rand(1, 5));

            $types = [
                'passport',
                'bank_statement',
                'resume',
                'photo',
            ];

            $file->update([
                'status' => 'analysed',
                'ai_detected_type' => $types[rand(0, (count($types)-1))]
            ]);
        }
    }
}
