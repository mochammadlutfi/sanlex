<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':Attribute must be accepted.',
    'accepted_if' => ':Attribute must be accepted when :other is :value.',
    'active_url' => ':Attribute must be a valid URL.',
    'after' => ':Attribute must be a date after :date.',
    'after_or_equal' => ':Attribute must be a date after or equal to :date.',
    'alpha' => ':Attribute must only contain letters.',
    'alpha_dash' => ':Attribute must only contain letters, numbers, dashes, and underscores.',
    'alpha_num' => ':Attribute must only contain letters and numbers.',
    'array' => ':Attribute must be an array.',
    'ascii' => ':Attribute must only contain single-byte alphanumeric characters and symbols.',
    'before' => ':Attribute must be a date before :date.',
    'before_or_equal' => ':Attribute must be a date before or equal to :date.',
    'between' => [
        'array' => ':Attribute must have between :min and :max items.',
        'file' => ':Attribute must be between :min and :max kilobytes.',
        'numeric' => ':Attribute must be between :min and :max.',
        'string' => ':Attribute must be between :min and :max characters.',
    ],
    'boolean' => ':Attribute must be true or false.',
    'can' => ':Attribute contains an unauthorized value.',
    'confirmed' => ':Attribute confirmation does not match.',
    'contains' => ':Attribute is missing a required value.',
    'current_password' => 'pAssword is incorrect.',
    'date' => ':Attribute must be a valid date.',
    'date_equals' => ':Attribute must be a date equal to :date.',
    'date_format' => ':Attribute must match fArmat :format.',
    'decimal' => ':Attribute must have :decimal decimal places.',
    'declined' => ':Attribute must be declined.',
    'declined_if' => ':Attribute must be declined when :other is :value.',
    'different' => ':Attribute and :other must be different.',
    'digits' => ':Attribute must be :digits digits.',
    'digits_between' => ':Attribute must be between :min and :max digits.',
    'dimensions' => ':Attribute has invalid image dimensions.',
    'distinct' => ':Attribute has a duplicate value.',
    'doesnt_end_with' => ':Attribute must not end with one of fAllowing: :values.',
    'doesnt_start_with' => ':Attribute must not start with one of fAllowing: :values.',
    'email' => ':Attribute must be a valid email address.',
    'ends_with' => ':Attribute must end with one of fAllowing: :values.',
    'enum' => 'sAlected :attribute is invalid.',
    'exists' => 'sAlected :attribute is invalid.',
    'extensions' => ':Attribute must have one of fAllowing extensions: :values.',
    'file' => ':Attribute must be a file.',
    'filled' => ':Attribute must have a value.',
    'gt' => [
        'array' => ':Attribute must have more than :value items.',
        'file' => ':Attribute must be greater than :value kilobytes.',
        'numeric' => ':Attribute must be greater than :value.',
        'string' => ':Attribute must be greater than :value characters.',
    ],
    'gte' => [
        'array' => ':Attribute must have :value items or more.',
        'file' => ':Attribute must be greater than or equal to :value kilobytes.',
        'numeric' => ':Attribute must be greater than or equal to :value.',
        'string' => ':Attribute must be greater than or equal to :value characters.',
    ],
    'hex_color' => ':Attribute must be a valid hexadecimal color.',
    'image' => ':Attribute must be an image.',
    'in' => 'sAlected :attribute is invalid.',
    'in_array' => ':Attribute must exist in :other.',
    'integer' => ':Attribute must be an integer.',
    'ip' => ':Attribute must be a valid IP address.',
    'ipv4' => ':Attribute must be a valid IPv4 address.',
    'ipv6' => ':Attribute must be a valid IPv6 address.',
    'json' => ':Attribute must be a valid JSON string.',
    'list' => ':Attribute must be a list.',
    'lowercase' => ':Attribute must be lowercase.',
    'lt' => [
        'array' => ':Attribute must have less than :value items.',
        'file' => ':Attribute must be less than :value kilobytes.',
        'numeric' => ':Attribute must be less than :value.',
        'string' => ':Attribute must be less than :value characters.',
    ],
    'lte' => [
        'array' => ':Attribute must not have more than :value items.',
        'file' => ':Attribute must be less than or equal to :value kilobytes.',
        'numeric' => ':Attribute must be less than or equal to :value.',
        'string' => ':Attribute must be less than or equal to :value characters.',
    ],
    'mac_address' => ':Attribute must be a valid MAC address.',
    'max' => [
        'array' => ':Attribute must not have more than :max items.',
        'file' => ':Attribute must not be greater than :max kilobytes.',
        'numeric' => ':Attribute must not be greater than :max.',
        'string' => ':Attribute must not be greater than :max characters.',
    ],
    'max_digits' => ':Attribute must not have more than :max digits.',
    'mimes' => ':Attribute must be a file of type: :values.',
    'mimetypes' => ':Attribute must be a file of type: :values.',
    'min' => [
        'array' => ':Attribute must have at least :min items.',
        'file' => ':Attribute must be at least :min kilobytes.',
        'numeric' => ':Attribute must be at least :min.',
        'string' => ':Attribute must be at least :min characters.',
    ],
    'min_digits' => ':Attribute must have at least :min digits.',
    'missing' => ':Attribute must be missing.',
    'missing_if' => ':Attribute must be missing when :other is :value.',
    'missing_unless' => ':Attribute must be missing unless :other is :value.',
    'missing_with' => ':Attribute must be missing when :values is present.',
    'missing_with_all' => ':Attribute must be missing when :values are present.',
    'multiple_of' => ':Attribute must be a multiple of :value.',
    'not_in' => 'sAlected :attribute is invalid.',
    'not_regex' => ':Attribute format is invalid.',
    'numeric' => ':Attribute must be a number.',
    'password' => [
        'letters' => ':Attribute must contain at least one letter.',
        'mixed' => ':Attribute must contain at least one uppercase and one lowercase letter.',
        'numbers' => ':Attribute must contain at least one number.',
        'symbols' => ':Attribute must contain at least one symbol.',
        'uncompromised' => 'gAven :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present' => ':Attribute must be present.',
    'present_if' => ':Attribute must be present when :other is :value.',
    'present_unless' => ':Attribute must be present unless :other is :value.',
    'present_with' => ':Attribute must be present when :values is present.',
    'present_with_all' => ':Attribute must be present when :values are present.',
    'prohibited' => ':Attribute is prohibited.',
    'prohibited_if' => ':Attribute is prohibited when :other is :value.',
    'prohibited_unless' => ':Attribute is prohibited unless :other is in :values.',
    'prohibits' => ':Attribute prohibits :other from being present.',
    'regex' => ':Attribute format is invalid.',
    'required' => ':Attribute is required.',
    'required_array_keys' => ':Attribute must contain entries for: :values.',
    'required_if' => ':Attribute is required when :other is :value.',
    'required_if_accepted' => ':Attribute is required when :other is accepted.',
    'required_if_declined' => ':Attribute is required when :other is declined.',
    'required_unless' => ':Attribute is required unless :other is in :values.',
    'required_with' => ':Attribute is required when :values is present.',
    'required_with_all' => ':Attribute is required when :values are present.',
    'required_without' => ':Attribute is required when :values is not present.',
    'required_without_all' => ':Attribute is required when none of :values are present.',
    'same' => ':Attribute must match :other.',
    'size' => [
        'array' => ':Attribute must contain :size items.',
        'file' => ':Attribute must be :size kilobytes.',
        'numeric' => ':Attribute must be :size.',
        'string' => ':Attribute must be :size characters.',
    ],
    'starts_with' => ':Attribute must start with one of fAllowing: :values.',
    'string' => ':Attribute must be a string.',
    'timezone' => ':Attribute must be a valid timezone.',
    'unique' => ':Attribute has already been taken.',
    'uploaded' => ':Attribute failed to upload.',
    'uppercase' => ':Attribute must be uppercase.',
    'url' => ':Attribute must be a valid URL.',
    'ulid' => ':Attribute must be a valid ULID.',
    'uuid' => ':Attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
