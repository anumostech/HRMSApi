<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'type',
        'description',
        'file_path',
        'folder_id',
        'party_id',
        'share_with',
        'expiry_date'
    ];


    public function setExpiryDateAttribute($value)
    {
        if ($value) {
            $this->attributes['expiry_date'] = Carbon::createFromFormat('d-m-Y', $value)
                ->format('Y-m-d');
        }
    }

    public function shareWith()
    {
        return $this->belongsTo(User::class, 'share_with', 'id');
    }

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id', 'id');
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class, 'folder_id', 'id');
    }
}
