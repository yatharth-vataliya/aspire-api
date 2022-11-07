<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $fillable = [
        "admin_id",
        "user_id",
        "loan_id",
        "term_pay_date",
        "term_amount",
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class, "user_id", "id");
    }

}
