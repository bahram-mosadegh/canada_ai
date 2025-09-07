<?php

namespace App\Services;

class ContractLookup
{
    public function lookup(string $contractNumber): array
    {
        // Mocked response; replace with real internal API integration.
        return [
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
    }
}


