<?php

namespace App\Livewire;

use App\Models\ClientCase;
use App\Jobs\AnalyseFileAI;
use App\Models\CaseFile;
use App\Models\Client;
use App\Services\ContractLookup;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CaseIntake extends Component
{
    use WithFileUploads;

    public string $contract_number = '';
    public ?array $contract_raw = null;
    public ?array $case_raw = null;
    public bool $contract_lookup_successful = false;
    public array $required_documents = [];
    public ?int $application_case_id = null;
    public ?int $client_id = null;
    public array $uploaded_files = [];
    public array $files = [];

    public function updatedContractNumber(ContractLookup $contract_lookup): void
    {
        if (trim($this->contract_number) === '') {
            $this->contract_raw = null;
            $this->case_raw = null;
            $this->required_documents = [];
            return;
        }

        $contract = $contract_lookup->lookup($this->contract_number);

        $this->contract_lookup_successful = $contract->isSuccessful();

        $this->contract_raw = $contract->getRawData();
        $this->case_raw = $contract->getRawCase();
        $this->required_documents = $contract->getRequiredDocuments();
    }

    public function ensureCase(): void
    {
        if ($this->application_case_id) {
            return;
        }

        $case = (new ContractLookup)->make($this->contract_raw, $this->contract_number)->createCase();

        $this->application_case_id = $case->id;
    }

    public function updatedFiles(): void
    {
        $this->ensureCase();

        foreach ($this->files as $file) {
            if (! method_exists($file, 'store')) {
                continue;
            }
            $path = $file->store('cases/' . $this->application_case_id, 'public');

            $case_file = CaseFile::create([
                'case_id' => $this->application_case_id,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'status' => 'pending',
                'path' => $path
            ]);

            AnalyseFileAI::dispatch($case_file->id);

            $this->uploaded_files[] = [
                'id' => $case_file->id,
                'name' => $case_file->original_name,
                'type' => null,
                'status' => 'pending',
                'url' => Storage::disk('public')->url($case_file->path),
            ];
        }

        $this->files = [];
    }

    public function checkFileStatus()
    {
        if (count($this->uploaded_files)) {
            $files = CaseFile::whereIn('id', collect($this->uploaded_files)->pluck('id'))->get();

            $uploaded_files = $this->uploaded_files;

            foreach ($uploaded_files as &$uploaded_file) {
                $db_file = $files->firstWhere('id', $uploaded_file['id']);
                $uploaded_file['status'] = $db_file->status;
                $uploaded_file['type'] = $db_file->ai_detected_type;
            }

            $this->uploaded_files = $uploaded_files;
        }
    }

    public function render()
    {
        return view('livewire.case-intake');
    }
}


