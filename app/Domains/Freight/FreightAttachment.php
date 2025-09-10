<?php

namespace App\Domains\Freight;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Users\User;

class FreightAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'freight_id',
        'file_path',
        'file_name',
        'mime_type',
        'size',
        'description',
        'uploaded_by',
    ];

    public function freight()
    {
        return $this->belongsTo(Freight::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    protected static function booted()
    {
        parent::booted();

        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }
}

