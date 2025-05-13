<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class AccountStructureRule implements Rule
{
    protected $company;

    public function __construct($company)
    {
        $this->company = $company;
    }

    public function passes($attribute, $value)
    {
        if (!$this->company) {
            return false;
        }

        $structure = $this->company->account_structure;
        $segments = explode('-', $structure);
        $valueSegments = explode('-', $value);

        if (count($valueSegments) !== count($segments) && $structure !== (string) strlen($value)) {
            return false;
        }

        if (count($segments) > 1) {
            foreach ($segments as $index => $length) {
                if (strlen($valueSegments[$index] ?? '') != $length) {
                    return false;
                }
            }
        } elseif (strlen($value) != $structure) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return __('The code must match the account structure defined in the company.');
    }
}
