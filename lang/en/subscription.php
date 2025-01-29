<?php

return [
    'subscription' => 'Subscription',
    'subscriptions' => 'Subscriptions',
    'module_title' => 'Manage all Subscriptions',
    'module_description' => 'Subscription is the amount of money, you charge from your client regularily to use the
    service.',
    'range_exists' => 'Subscription for the tenant already exists between :start and :end.',
    'could_not_perform_if_is_online' => 'Could not perform this operation if it is online subscription.',
    'could_not_perform_if_not_last_subscription' => 'Could not perform this operation if it is not last subscription.',
    'frequency_not_allowed' => ':attribute frequency not allowed for this plan.',
    'props' => [
        'date' => 'Date',
        'online' => 'Online',
        'amount' => 'Amount',
        'name' => 'Customer Name',
        'email' => 'Customer Email',
        'code_number' => 'Invoice Number',
        'start_date' => 'Start Date',
        'expiry_date' => 'Expiry Date',
        'show_failed' => 'Show Failed',
    ],
    'statuses' => [
        'failed' => 'Failed',
        'success' => 'Success',
    ],
    'config' => [
        'props' => [
            'invoice_number_prefix' => 'Invoice Prefix',
            'invoice_number_digit' => 'Invoice Digit',
            'invoice_number_suffix' => 'Invoice Suffix',
            'invoice_terms' => 'Invoice Terms',
            'company_name' => 'Company Name',
            'company_address' => 'Company Address',
            'company_email' => 'Company Email',
            'company_phone' => 'Company Phone',
            'tax_id_label' => 'Tax ID Label',
            'tax_id_number' => 'Tax ID Number',
            'enable_payment_gateway' => 'Enable :attribute',
            'payment_gateway_mode' => ':attribute Mode',
            'payment_gateway_client' => ':attribute Client',
            'payment_gateway_secret' => ':attribute Secret',
            'payment_gateway_notification' => ':attribute Notification',
        ],
    ],
];
