<?php

namespace App\Models;

use App\Domain\PatientPaymentRequest\Requesters\RequesterContract;
use App\Domain\PatientPaymentRequest\Verifiers\VerifierContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPaymentRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function verify(VerifierContract $verifier)
    {
        return $verifier->verify($this);
    }


    public function request(RequesterContract $requester)
    {
        return $requester->request($this);
    }

    public function payment()
    {
        return $this->belongsTo(PatientPayment::class, 'patient_payment_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
