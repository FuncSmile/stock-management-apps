<?php

    namespace App\Controllers\Auth;

    use CodeIgniter\Shield\Controllers\LoginController as ShieldLogin;
    use CodeIgniter\HTTP\RedirectResponse;

    class LoginController extends ShieldLogin
    {
        /**
         * Mengesampingkan aturan validasi bawaan agar menggunakan 'username'
         */
        protected function getValidationRules(): array
        {
            return [
                'username' => [
                    'label' => 'Username',
                    'rules' => 'required',
                ],
                'password' => [
                    'label' => 'Kata Sandi',
                    'rules' => 'required',
                ],
            ];
        }
    }
