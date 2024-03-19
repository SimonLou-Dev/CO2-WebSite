<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class DevEuiRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if(Str::contains($value, ['-', ':', ' '])){
            $value = Str::replace(['-', ' ', ':'],'', $value);
        }

        if (!preg_match('/^[0-9a-f]{16}$/', $value)) $fail("Le texte entré n'est pas un DevEUI.");


    }
}
