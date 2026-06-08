<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAccount extends Model
{
    use HasFactory;

    protected $table = 'company_accounts';
    
    protected $fillable = [
        'bank_name',
        'account_number',
        'account_name',
        'branch',
        'is_active'
    ];
}