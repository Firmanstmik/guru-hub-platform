<?php

namespace App\Support;

class AdminContact
{
    public static function phoneDisplay(): string
    {
        return (string) config('guruhub.admin.phone_display', '+81 70-8418-2215');
    }

    public static function phoneE164(): string
    {
        return (string) config('guruhub.admin.phone_e164', '817084182215');
    }

    public static function email(): string
    {
        return (string) config('guruhub.admin.email', 'guruhubku@gmail.com');
    }

    public static function telUrl(): string
    {
        return 'tel:+'.self::phoneE164();
    }

    public static function whatsappUrl(?string $message = null): string
    {
        $url = 'https://wa.me/'.self::phoneE164();

        if (filled($message)) {
            $url .= '?text='.rawurlencode($message);
        }

        return $url;
    }

    public static function paymentConfirmUrl(string $invoiceCode): string
    {
        return self::whatsappUrl(
            "Halo Admin, saya ingin konfirmasi pembayaran kelas untuk invoice: {$invoiceCode}"
        );
    }
}
