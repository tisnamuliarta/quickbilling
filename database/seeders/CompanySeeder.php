<?php

namespace Database\Seeders;

use App\Models\Settings\Company;
use App\Models\Settings\UserCompany;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = Company::create([
           'name' => 'CRM'
        ]);

        $user = User::where('username', 'manager')->first();

        UserCompany::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'user_type' => 'Administrator'
        ]);
    }
}
