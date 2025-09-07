<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InternalApiController extends Controller
{
    public function contractLookup(Request $request): JsonResponse
    {
        $contract = (string) $request->get('contract_number');

        if ($contract === '') {
            return response()->json(['message' => 'contract_number is required'], 422);
        }

        // Mocked response; replace with real internal API call later.
        $applicant = [
            'full_name' => 'John Doe',
            'email' => 'john.doe@example.com',
        ];

        $requiredDocuments = [
            'passport',
            'bank_statement',
            'resume',
            'photo',
        ];

        return response()->json([
            'applicant' => $applicant,
            'required_documents' => $requiredDocuments,
        ]);
    }
}


