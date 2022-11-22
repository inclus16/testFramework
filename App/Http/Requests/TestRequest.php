<?php


namespace App\Http\Requests;


use Ds\Map;
use System\Http\Dto\Rule;
use System\Http\Requests\AppRequest;
use System\Http\Validation\Rules\Required;

class TestRequest extends AppRequest
{

    public function getRules(): Map
    {
        return new Map([
            'field' => new Rule(Required::class)
        ]);
    }
}