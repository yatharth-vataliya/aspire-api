<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "amount_required",
        "loan_terms"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

}
