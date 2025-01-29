<?php

namespace Database\Seeders;

use App\Models\Config\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config = Config::firstOrCreate(['name' => 'general']);
        $config->value = [
            'app_name' => 'Mint School',
        ];
        $config->save();

        $config = Config::firstOrCreate(['name' => 'system']);
        $config->value = [
            'enable_dark_theme' => false,
            'currencies' => 'USD',
        ];
        $config->save();

        $config = Config::firstOrCreate(['name' => 'server']);
        $config->value = [
            'platform' => 'local',
            'db_prefix' => 'saas_',
        ];
        $config->save();

        $config = Config::firstOrCreate(['name' => 'site']);
        $config->value = [
            'enable_site' => true,
        ];
        $config->save();

        $config = Config::firstOrCreate(['name' => 'tenant']);
        $config->value = [
            'trial_period' => 7,
            'trial_ends_on_subscription' => true,
            'subscription_end_alert' => 7,
            'unavailable_subdomain' => 'webmaster,admin,system,test,testing',
            'default_timezone' => 'Asia/Kolkata',
            'default_currency' => 'INR',
            'default_app_name' => 'Mint School',
            'default_author' => 'ScriptMint',
            'default_footer_credit' => 'Designed with love by ScriptMint',
        ];
        $config->save();

        $config = Config::firstOrCreate(['name' => 'subscription']);
        $config->value = [
            'invoice_number_prefix' => 'MS%MONTH_NUMBER%%YEAR_SHORT%',
            'invoice_number_digit' => 3,
            'invoice_number_suffix' => '',
            'invoice_terms' => "This is computer generated invoice and does not require physical signature.\r\nItem may be subject to reverse charge in the country of receipt.",
        ];
        $config->save();
    }
}
