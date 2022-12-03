<?php

namespace Acelle\Model;

use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name', 'email', 'country_id',
        'address', 'phone'
    ];

    /**
     * customer.
     */
    public function customer()
    {
        return $this->belongsTo('Acelle\Model\Customer');
    }

    /**
     * country.
     */
    public function country()
    {
        return $this->belongsTo('Acelle\Model\Country');
    }

    /**
     * Get default billing address.
     *
     * @var object
     */
    public function updateAll($request)
    {
        $this->fill($request->all());

        // make validator
        $validator = \Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'country_id' => 'required',
            'address' => 'required',
        ]);

        // redirect if fails
        if ($validator->fails()) {
            return [$validator, $this];
        }

        $this->customer_id = $request->user()->customer->id;

        $this->save();

        return [$validator, $this];
    }

    /**
     * Copy from contact.
     *
     * @var object
     */
    public function copyFromContact()
    {
        if ($this->customer && $this->customer->contact) {
            $this->fill($this->customer->contact->toArray());
        }
    }
}
