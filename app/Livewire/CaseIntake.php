<?php

namespace App\Livewire;

use App\Models\ClientCase;
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
    public ?array $client_raw = null;
    public array $required_documents = [];
    public ?int $application_case_id = null;
    public ?int $client_id = null;
    public array $uploaded_files = [];
    public array $files = [];

    #[Validate('sometimes|string|max:255')]
    public string $applicant_full_name = '';

    #[Validate('sometimes|email|max:255')]
    public string $applicant_email = '';

    public function updatedContractNumber(ContractLookup $contract_lookup): void
    {
        if (trim($this->contract_number) === '') {
            $this->client_raw = null;
            $this->required_documents = [];
            return;
        }

        $this->client_raw = $contract_lookup->lookup($this->contract_number)->getRawData() ?? null;
        $this->applicant_full_name = $this->client_raw['applicant']['full_name'] ?? '';
        $this->applicant_email = $this->client_raw['applicant']['email'] ?? '';
        $this->required_documents = $this->client_raw['required_documents'] ?? [];
    }

    public function ensureCase(): void
    {
        if ($this->application_case_id) {
            return;
        }
        $client = (new ContractLookup)->make($this->client_raw)->createClient();
        $this->client_id = $client->id;

        $case = ClientCase::create([
            'client_id' => $this->client_id,
            'contract_number' => $this->contract_number,
            'status' => 'in_progress',
        ]);
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

            $classification = $this->classifyDocumentAi($file->getClientOriginalName(), $file->getMimeType());

            $record = CaseFile::create([
                'case_id' => $this->application_case_id,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'path' => $path,
                'ai_detected_type' => $classification['type'] ?? null,
            ]);

            $this->uploaded_files[] = [
                'id' => $record->id,
                'name' => $record->original_name,
                'type' => $record->ai_detected_type,
                'url' => Storage::disk('public')->url($record->path),
            ];
        }

        $this->files = [];
    }

    private function classifyDocumentAi(string $name, ?string $mime): array
    {
        // Stub: very naive rules; replace with a real AI API integration.
        $n = strtolower($name);
        if (str_contains($n, 'passport')) return ['type' => 'passport', 'confidence' => 0.92];
        if (str_contains($n, 'bank')) return ['type' => 'bank_statement', 'confidence' => 0.88];
        if (str_contains($n, 'resume') || str_contains($n, 'cv')) return ['type' => 'resume', 'confidence' => 0.86];
        if (str_contains($n, 'photo') || str_contains($n, 'picture')) return ['type' => 'photo', 'confidence' => 0.84];
        return ['type' => 'unknown', 'confidence' => 0.50];
    }

    public function render()
    {
        return view('livewire.case-intake');
    }
}


