<?php

return [
    'exchange_rates_url'     => 'https://minfin.com.ua/api/currency/rates/banks/',
    'exchange_rates_nbu_url' => 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?json',
    'banks_list_url'         => 'https://finance.ua/banks/api/organizationsList?locale=uk',
    'banks_branches_url'     => 'https://finance.ua/api/organization/v1/branches?locale=uk&slug=',

    'allowed_banks' => ['oschadbank', 'privatbank', 'ukrgasbank', 'otp-bank', 'ukreximbank'],
];
