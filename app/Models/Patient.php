<?php

namespace App\Models;

use App\Domain\PatientRates\PatientBalanceResolver;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Support\Str;

class Patient extends Model implements Authenticatable
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $hidden = ['token'];
    protected $appends = ['fullname', 'is_new'];

     /**
     * Relationships
     */

    public function historyGroup()
    {
        return $this->hasMany(HistoryGroup::class);
    }

    public function medicalHistory()
    {
        return $this->hasMany(MedicalHistory::class);
    }

    public function medicalRevision()
    {
        return $this->hasMany(MedicalRevision::class);
    }

    public function paymentRequests()
    {
        return $this->hasMany(PatientPaymentRequest::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function histories()
    {
        return $this->hasMany(PatientHistory::class);
    }

    public function rates()
    {
        return $this->hasMany(PatientRate::class);
    }

    public function payments()
    {
        return $this->hasMany(PatientPayment::class);
    }

    function getIsNewAttribute()
    {
        if($this->payments()->first()) return "";
        return "(Nuevo)";
    }


    public function getRateBalance(PatientBalanceResolver $resolver = null)
    {
        $resolver = $resolver ?: new PatientBalanceResolver;
        return $resolver->resolve($this);
    }


    public function isTokenValid($token)
    {
        return $this->token == $token;
    }


    public function generateToken($regenerateToken = false)
    {
        if ($regenerateToken || !$this->token) {
            $this->token = Str::random(32);
            $this->save();
        }

        return $this->token;
    }


    public function getLinkAttribute()
    {
        $this->generateToken();

        return url(route('area.patients.login', [
            'dni' => $this->dni,
            'token' => $this->token,
        ]));
    }

    public function getFullnameAttribute()
    {
        return "{$this->name} {$this->lastname1} {$this->lastname2}";
    }


    /**
     * Auth
     */
    public function getAuthIdentifierName()
    {
        return 'dni';
    }
    public function getAuthIdentifier()
    {
        return $this->dni;
    }
    public function getAuthPassword()
    {
        return $this->token;
    }
    public function getRememberToken()
    {
        return null;
    }
    public function setRememberToken($value)
    {
        return null;
    }
    public function getRememberTokenName()
    {
        return null;
    }

    public function recommendation()
    {
        return $this->belongsTo(Recommendation::class);
    }
}
