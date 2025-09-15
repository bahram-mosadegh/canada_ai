<?php

namespace App\Services;
use App\Models\Client;

class ContractLookup
{
    private ?array $raw_data = null;
    private ?Client $client = null;

    public function lookup(string $contractNumber)
    {
        sleep(1);
        $this->raw_data = [
            'applicant' => [
                'full_name' => 'John Doe',
                'email' => 'john.doe@example.com',
            ],
            'required_documents' => [
                'passport',
                'bank_statement',
                'resume',
                'photo',
            ],
        ];

        return $this;
    }

    public function make(array $raw_data)
    {
        $this->raw_data = $raw_data;

        return $this;
    }

    public function getRawData()
    {
        return $this->raw_data;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function createClient()
    {
        if ($this->raw_data) {
            $this->client = Client::firstOrCreate(
                ['email' => $this->raw_data['applicant']['email']],
                ['full_name' => $this->raw_data['applicant']['full_name']]
            );
        }

        return $this->client;
    }
}


