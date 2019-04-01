<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Google_Client;
use Google_Service_Sheets;

class ImportRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import-routes:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports routs from external csv file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets API PHP Quickstart');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
//        $client->setAuthConfig('credentials.json');
        $client->setAuthConfig('credentials.json');
        $client->setAccessType('offline');


        return $client;
    }

    function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {

        $str = str_replace('-', '', ucwords($string, '-'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get the API client and construct the service object.
        $client = $this->getClient();
        $service = new Google_Service_Sheets($client);

        $spreadsheetId = '1MCcyHPVnMqKcJqwY0rYnESTlP_g19_OEqJHTcv0BAqY';
        $range = 'Sheet1!A1:B';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        if ($values) {
            foreach ($values as $value) {
                $this->info($value[0]);
            }
        }
    }
}
