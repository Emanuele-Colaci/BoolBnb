<?php

namespace App\Models;

use App\Models\Apartment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class service extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function apartment() {
        return $this->belongsToMany(Apartment::class);
    }
}
