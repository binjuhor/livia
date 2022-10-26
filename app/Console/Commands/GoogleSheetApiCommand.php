<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GoogleSheetApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interacts with Google Sheet';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::debug('start update sheet 1 data');
        $client = $this->getGoogleClient();
        $service = new Google_Service_Sheets($client);
        $spreadsheetId = env('GOOGLE_SHEET_ID');
        $range = 'Sheet1!A2:D';

        // get values
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        print_r($values);

        // add/edit values
        $data = [
            [
                'column A2',
                'column B2',
                'column C2',
                'column D2',
            ],
            [
                'column A3',
                'column B3',
                'column C3',
                'column D3',
            ],
        ];
        $requestBody = new \Google_Service_Sheets_ValueRange([
            'values' => $data
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        $service->spreadsheets_values->update($spreadsheetId, $range, $requestBody, $params);
        echo "SUCCESS \n";
        Log::debug('update sheet 1 data success');
        return Command::SUCCESS;
    }

    public function getGoogleClient()
    {
        $client = new \Google_Client();
        $client->setRedirectUri('http://livia.chillbits.com/auth/callback');
        $client->setApplicationName('Anna White');
        $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
        $client->setAuthConfig(config_path('credentials.json'));
        $client->setAccessType('offline');

        $tokenPath = storage_path('app/token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }

            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        return $client;
    }
}
