<?php

namespace App\Services;
use App\Models\Client;
use App\Models\ClientCase;
use Illuminate\Support\Facades\Http;

class ContractLookup
{
    private ?string $contract_number = null;
    private ?array $raw_data = null;
    private ?array $raw_case = null;
    private array $required_documents = [];
    private ?Client $client = null;
    private ?ClientCase $case = null;

    public function lookup(string $contract_number)
    {
        $this->contract_number = $contract_number;

        $domain = rtrim(env('CRM_ENDPOINT'), '/');
        $url = $domain.'/main/api/data/v9.0/new_evaluationforms';
        $username = env('CRM_USERNAME');
        $password = env('CRM_PASSWORD');

        // $response = Http::withOptions([
        //     'auth' => [$username, $password, 'ntlm', 'domain' => $domain]
        // ])->get($url, [
        //     '$select' => 'new_azmayeshgahdaran,new_bazneshastegan,new_dowlatcarmand,new_education,new_karfarmayansherkat,new_martial,new_mashaghelazad,new_mojeran,new_name,new_name1,new_name2,new_name3,new_name4,new_name5,new_name6,new_numberofpassengers,new_otherdocumenteducation,new_pezeshkdarayematab,new_pezeshkdarbimarestan,new_rezayatname2,new_sakhtemansazan,new_typeofvisa,new_typetahsili,new_vikalayedarayedaftar,new_vokalayeshagheldaftar',
        //     '$filter' => "new_contractnumber2 eq '$contract_number'",
        //     '$expand' => 'new_contractnumber($select=new_mobilephone)'
        // ]);

        // $this->raw_data = json_decode($response->body(), true);

        sleep(2);
        $this->raw_data = json_decode('{
    "@odata.context": "http://192.168.110.23/main/api/data/v9.0/$metadata#new_evaluationforms(new_azmayeshgahdaran,new_bazneshastegan,new_dowlatcarmand,new_education,new_karfarmayansherkat,new_martial,new_mashaghelazad,new_mojeran,new_name,new_name1,new_name2,new_name3,new_name4,new_name5,new_name6,new_numberofpassengers,new_otherdocumenteducation,new_pezeshkdarayematab,new_pezeshkdarbimarestan,new_rezayatname2,new_sakhtemansazan,new_typeofvisa,new_typetahsili,new_vikalayedarayedaftar,new_vokalayeshagheldaftar,new_contractnumber(new_mobilephone))",
    "value": [
        {
            "@odata.etag": "W/\"37952131\"",
            "new_azmayeshgahdaran": false,
            "new_bazneshastegan": false,
            "new_dowlatcarmand": false,
            "new_education": false,
            "new_karfarmayansherkat": false,
            "new_martial": false,
            "new_mashaghelazad": false,
            "new_mojeran": false,
            "new_name": "آیدا ربیعی",
            "new_name1": "آیدا ربیعی",
            "new_name2": null,
            "new_name3": null,
            "new_name4": null,
            "new_name5": null,
            "new_name6": null,
            "new_numberofpassengers": 1,
            "new_otherdocumenteducation": null,
            "new_pezeshkdarayematab": true,
            "new_pezeshkdarbimarestan": false,
            "new_rezayatname2": false,
            "new_sakhtemansazan": false,
            "new_typeofvisa": 100000002,
            "new_typetahsili": false,
            "new_vikalayedarayedaftar": false,
            "new_vokalayeshagheldaftar": false,
            "new_evaluationformid": "dfef8507-1792-f011-9034-005056bfe138",
            "new_contractnumber": {
                "new_mobilephone": "09196610502"
            }
        }
    ]
}', true);

        $this->client = null;
        $this->case = null;

        return $this;
    }

    public function make(array $raw_data, string $contract_number)
    {
        $this->raw_data = $raw_data;
        $this->contract_number = $contract_number;

        return $this;
    }

    public function isSuccessful()
    {
        return $this->raw_data
            && isset($this->raw_data['value'])
            && isset($this->raw_data['value'][0])
            && is_array($this->raw_data['value'][0])
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
            $data = $this->raw_data['value'][0];

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
                    100000000 => 'invitation',
                    100000001 => 'tourist_with_voucher_and_ticket',
                    100000002 => 'tourist_with_company_invitation',
                    default => 'unknown',
                };
            }

            $raw_case = [
                'contract_number' => $this->contract_number,
                'client_full_name' => $data['new_name'] ?? null,
                'client_mobile' => $data['new_contractnumber']['new_mobilephone'] ?? null,
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

            $is_business = $raw_case['is_azmayeshgahdaran']
                || $raw_case['is_karfarmayansherkat']
                || $raw_case['is_mashaghelazad']
                || $raw_case['is_mojeran']
                || $raw_case['is_pezeshkdarayematab']
                || $raw_case['is_sakhtemansazan']
                || $raw_case['is_vokalayedarayedaftar'];

            $raw_case['is_business'] = $is_business;

            $required_documents = [
                'id',
                'travel_history',
                'client',
                'financial',
                'purpose_of_travel',
            ];

            if ($is_business) {
                $required_documents[] = 'business';
            }

            if ($type_of_visa == 'invitation') {
                $required_documents = [
                    'invitation',
                    'proof_of_relationship'
                ];
            }

            $raw_case['required_documents'] = $required_documents;

            $this->required_documents = $required_documents;

            $this->raw_case = $raw_case;
        }

        return $this->raw_case;
    }

    public function getRequiredDocuments()
    {
        if (!count($this->required_documents) && $this->isSuccessful()) {
            $this->getRawCase();
        }

        return $this->required_documents;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function createClient()
    {
        if ($this->isSuccessful() && !$this->client) {
            $raw_case = $this->getRawCase();

            $this->client = Client::firstOrCreate(
                ['mobile' => $raw_case['client_mobile']]
            );
        }

        return $this->client;
    }

    public function getCase()
    {
        return $this->case;
    }

    public function createCase()
    {
        if ($this->isSuccessful() && !$this->case) {
            $raw_case = $this->getRawCase();

            if ($this->client) {
                $raw_case['client_id'] = $this->client->id;
            }

            $this->case = ClientCase::create($raw_case);
        }

        return $this->case;
    }
}


