<?php

namespace App\Core;

class Validator
{
    protected array $data;
    protected array $rules;
    protected array $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data  = $data;
        $this->rules = $rules;
        $this->validate();
    }

    protected function validate(): void
    {
        foreach ($this->rules as $field => $rules) {
            $rules = explode('|', $rules);
            foreach ($rules as $rule) {
                if ($rule === 'required' && empty(trim($this->data[$field] ?? ''))) {
                    $this->errors[$field][] = __('validation_required', ['field' => __('field_' . $field)]);
                }

                if ($rule === 'phone' && !preg_match('/^\d{11}$/', $this->data[$field] ?? '')) {
                    $this->errors[$field][] = __('phone_invalid');
                }

                if (str_starts_with($rule, 'not')) {
                    $not = (int)explode(':', $rule)[1];
                    if($not == $this->data[$field]){
                        $this->errors[$field][] = __('validation_required', ['field' => __('field_' . $field)]);
                    }

                }
                if (str_starts_with($rule, 'min')) {
                    $min = (int)explode(':', $rule)[1];
                    if (strlen($this->data[$field] ?? '') < $min) {
                        $this->errors[$field][] = __('min_length', ['min' => $min]);
                    }
                }
                if (str_starts_with($rule, 'max')) {
                    $max = (int)explode(':', $rule)[1];
                    if (strlen($this->data[$field] ?? '') > $max) {
                        $this->errors[$field][] = __('max_length', ['max' => $max]);
                    }
                }
            }
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
