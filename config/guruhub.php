<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin contact (payment confirmation & public footer)
    |--------------------------------------------------------------------------
    */
    'admin' => [
        'phone_display' => env('GURUHUB_ADMIN_PHONE_DISPLAY', '+81 70-8418-2215'),
        // Digits only for wa.me / tel: links (country code, no +)
        'phone_e164' => env('GURUHUB_ADMIN_PHONE_E164', '817084182215'),
        'email' => env('GURUHUB_ADMIN_EMAIL', 'guruhubku@gmail.com'),
    ],

];
