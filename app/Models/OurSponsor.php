<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurSponsor extends Model
{
    use HasFactory;
    protected $fillable = [
        'campaign_id',
        'title',
        'image',
    ];
    
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
