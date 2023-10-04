<?php

namespace App\Models;

use App\Models\User;
use App\Models\Lead;
use App\Models\Service;
use App\Models\View;
use App\Models\Sponsorship;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Apartment extends Model
{
    use HasFactory;
    protected $fillable = ['title','slug','n_rooms','n_beds','n_bathrooms','mq','address','latitude','longitude','img','description','price','user_id', 'visible'];

    public static function generateSlug($title)
    {
        return Str::slug($title, '-');
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function services() {
        return $this->belongsToMany(Service::class);
    }

    public function sponsorships() {
        return $this->belongsToMany(Sponsorship::class)->withPivot('start_date', 'end_date');
    }

    public function leads() {
        return $this->hasMany(Lead::class);
    }

    public function views() {
        return $this->hasMany(View::class);
    }
}
