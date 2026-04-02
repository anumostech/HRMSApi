<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class Employee extends Authenticatable
{
    use SoftDeletes, Notifiable;

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('status', 'active');
        });
    }

    /**
     * Scope a query to include inactive employees.
     */
    public function scopeWithInactive($query)
    {
        return $query->withoutGlobalScope('active');
    }

    /**
     * Scope a query to only inactive employees.
     */
    public function scopeOnlyInactive($query)
    {
        return $query->withoutGlobalScope('active')->where('status', 'inactive');
    }

    /**
     * Retrieve the model for a bound value.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->withInactive()->where($field ?? $this->getRouteKeyName(), $value)->first();
    }

    /**
     * The username field for authentication.
     */
    public function username()
    {
        return 'company_email';
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'organization_id',
        'employee_id',
        'designation_id',
        'department_id',
        'company_id',
        'dob',
        'joining_date',
        'gender',
        'special_days',
        'passport_full_name',
        'passport_number',
        'passport_issued_from',
        'passport_issued_date',
        'passport_expiry_date',
        'place_of_birth',
        'father_name',
        'mother_name',
        'address',
        'passport_1st_page',
        'passport_2nd_page',
        'passport_outer_page',
        'passport_id_page',
        'visa_number',
        'visa_issued_date',
        'visa_expiry_date',
        'visa_page',
        'labor_number',
        'labor_issued_date',
        'labor_expiry_date',
        'labor_card',
        'eid_number',
        'eid_issued_date',
        'eid_expiry_date',
        'eid_1st_page',
        'eid_2nd_page',
        'dependents',
        'educational_1st_page',
        'educational_2nd_page',
        'company_mobile_number',
        'personal_number',
        'other_number',
        'home_country_number',
        'company_email',
        'personal_email',
        'home_country_id_proof',
        'status',
        'total_leaves_allocated',
        'password',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'special_days' => 'array',
        'password' => 'hashed',
    ];

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && file_exists(storage_path('app/public/' . $this->avatar))) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=6366f1&background=eef2ff';
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

     public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class, 'userid', 'id');
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat('d-m-Y', $value)
            ->format('Y-m-d');
    }
}
