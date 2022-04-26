<?php

namespace App\Traits;

use App\Models\Inventory\ContactBank;
use App\Models\Inventory\ContactEmail;
use App\Models\Master\Bank;
use App\Models\User;

trait ContactDetail
{
    use MasterData;

    /**
     * @param $banks
     * @param $contact_id
     * @return void
     */
    public function storeContactBank($banks, $contact_id)
    {
        foreach ($banks as $bank) {
            if ($bank['name']) {
                ContactBank::updateOrCreate([
                    'bank_id' => array_key_exists('name', $bank) ? $this->bankIdByName($bank['name']) : null,
                    'contact_account_name' => array_key_exists('contact_account_name', $bank)
                        ? $bank['contact_account_name'] : null,
                    'contact_account_number' => array_key_exists('contact_account_number', $bank)
                        ? $bank['contact_account_number'] : null,
                    'contact_id' => $contact_id,
                ], [
                    'branch' => array_key_exists('branch', $bank)
                        ? $bank['branch'] : null,
                ]);
            }
        }
    }

    /**
     * @param $email
     * @param $contact_id
     * @return void
     */
    public function storeContactEmail($email, $contact_id)
    {
        foreach ($email as $item) {
            if ($item['email']) {
                ContactEmail::updateOrCreate([
                    'email' => array_key_exists('email', $item) ? $item['email'] : null,
                    'contact_id' => $contact_id,
                ]);
            }
        }
    }

    /**
     * @param $form
     * @return void
     */
    public function createUser($form)
    {
        $usr = User::where('username', $form['email'])->first();
        $password = ($usr) ? $usr->password : null;
        $user = User::updateOrCreate(
            ['email' => $form['email'], 'username' => $form['email']],
            [
                'name' => $form['name'],
                'password' => (array_key_exists('password', $form)) ? bcrypt($form['password']) : $password,
            ]
        );

        $this->processUserRolePermission('Customer', $user);
    }

    /**
     * @param $bank
     * @return mixed
     */
    public function bankIdByName($bank)
    {
        $bank = Bank::where('name', '=', $bank)->first();
        return $bank->id;
    }
}
