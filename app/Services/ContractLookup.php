<?php

namespace App\Services;

class ContractLookup
{
    public function lookup(string $contractNumber): array
    {
        sleep(1);
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


