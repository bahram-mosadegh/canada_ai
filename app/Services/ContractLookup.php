<?php

namespace App\Services;
use App\Models\Client;
use Illuminate\Support\Facades\Http;

class ContractLookup
{
    private ?array $raw_data = null;
    private ?array $raw_case = null;
    private array $required_documents = [];
    private ?Client $client = null;

    public function lookup(string $contractNumber)
    {
        $domain = rtrim(env('CRM_ENDPOINT'), '/');
        $url = $domain.'/main/api/data/v9.0/new_evaluationforms';
        $username = env('CRM_USERNAME');
        $password = env('CRM_PASSWORD');

        $response = Http::withOptions([
            'auth' => [$username, $password, 'ntlm', 'domain' => $domain]
        ])->get($url, [
            '$select' => 'new_azmayeshgahdaran,new_bazneshastegan,new_dowlatcarmand,new_education,new_karfarmayansherkat,new_martial,new_mashaghelazad,new_mojeran,new_name,new_name1,new_name2,new_name3,new_name4,new_name5,new_name6,new_numberofpassengers,new_otherdocumenteducation,new_pezeshkdarayematab,new_pezeshkdarbimarestan,new_rezayatname2,new_sakhtemansazan,new_typeofvisa,new_typetahsili,new_vikalayedarayedaftar,new_vokalayeshagheldaftar',
            '$filter' => "new_contractnumber2 eq '$contractNumber'",
            '$expand' => 'new_contractnumber($select=new_mobilephone)'
        ]);

        dd($domain, $url, $username, $password, $response->body());

        $this->raw_data = json_decode($response->body(), true);

        return $this;
    }

    public function make(array $raw_data)
    {
        $this->raw_data = $raw_data;

        return $this;
    }

    public function isSuccessful()
    {
        return $this->raw_data
            && isset($this->raw_data['value'])
            && is_array($this->raw_data['value'])
            && count($this->raw_data['value']);
    }

    public function getRawData()
    {
        return $this->raw_data;
    }

    public function getRawCase()
    {
        $this->raw_case = null;

        if ($this->isSuccessful()) {
            $data = $this->raw_case['value'];

            $applicant_names = [];
            if (isset($data['new_name1']) && $data['new_name1']) {
                $applicant_names[] = $data['new_name1'];
            }
            if (isset($data['new_name2']) && $data['new_name2']) {
                $applicant_names[] = $data['new_name2'];
            }
            if (isset($data['new_name3']) && $data['new_name3']) {
                $applicant_names[] = $data['new_name3'];
            }
            if (isset($data['new_name4']) && $data['new_name4']) {
                $applicant_names[] = $data['new_name4'];
            }
            if (isset($data['new_name5']) && $data['new_name5']) {
                $applicant_names[] = $data['new_name5'];
            }
            if (isset($data['new_name6']) && $data['new_name6']) {
                $applicant_names[] = $data['new_name6'];
            }

            $type_of_visa = 'unknown';
            if (isset($data['new_typeofvisa']) && $data['new_typeofvisa']) {
                $type_of_visa = match ($data['new_typeofvisa']) {
                    '100000000' => 'invitation',
                    '100000001' => 'tourist_with_voucher_and_ticket',
                    '100000002' => 'Tourist_with_company_invitation',
                    default => 'unknown',
                };
            }

            $this->raw_case = [
                'number_of_applicants' => $data['new_numberofpassengers'] ?? 0,
                'applicant_names' => $applicant_names,
                'type_of_visa' => $type_of_visa,
                'is_married' => $data['new_martial'] ?? false,
                'is_azmayeshgahdaran' => $data['new_azmayeshgahdaran'] ?? false,
                'is_bazneshastegan' => $data['new_bazneshastegan'] ?? false,
                'is_dowlatcarmand' => $data['new_dowlatcarmand'] ?? false,
                'is_karfarmayansherkat' => $data['new_karfarmayansherkat'] ?? false,
                'is_mashaghelazad' => $data['new_mashaghelazad'] ?? false,
                'is_mojeran' => $data['new_mojeran'] ?? false,
                'is_pezeshkdarayematab' => $data['new_pezeshkdarayematab'] ?? false,
                'is_pezeshkdarbimarestan' => $data['new_pezeshkdarbimarestan'] ?? false,
                'is_sakhtemansazan' => $data['new_sakhtemansazan'] ?? false,
                'is_vokalayedarayedaftar' => $data['new_vikalayedarayedaftar'] ?? false,
                'is_vokalayeshagheldaftar' => $data['new_vokalayeshagheldaftar'] ?? false,
            ];
        }

        return $this->raw_case;
    }

    public function getRequiredDocuments()
    {
        $this->required_documents = [];

        if ($this->isSuccessful()) {
            $this->required_documents = [
                'passport',
                'bank_statement',
                'resume',
                'photo',
            ];
        }

        return $this->required_documents;
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


