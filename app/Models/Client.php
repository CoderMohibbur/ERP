<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'company_name',
        'industry_type',
        'website',
        'tax_id',
        'status',
        'custom_fields',
        'created_by',
        'updated_by',
    ];

    /**
     * Cast fields
     */
    protected $casts = [
        'custom_fields' => 'array',
        'status'        => 'string',
    ];

    /**
     * 🔗 Relationship: Created By User
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * 🔗 Relationship: Updated By User
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * 🔍 Scope: Only Active Clients
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * 🔍 Scope: Filter by Company Name
     */
    public function scopeCompany($query, $company)
    {
        return $query->where('company_name', 'like', "%{$company}%");
    }

    /**
     * 💡 Accessor: Get custom field value easily
     */
    public function getCustomField($key)
    {
        return $this->custom_fields[$key] ?? null;
    }

    /**
     * 💡 Mutator: Set individual custom field
     */
    public function setCustomField($key, $value)
    {
        $custom = $this->custom_fields ?? [];
        $custom[$key] = $value;
        $this->custom_fields = $custom;
    }


    // app/Models/Client.php

    public function contacts()
    {
        return $this->hasMany(\App\Models\ClientContact::class);
    }

    public function notes()
    {
        return $this->hasMany(ClientNote::class);
    }
}
