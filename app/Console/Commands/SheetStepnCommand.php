<?php

namespace App\Console\Commands;

use App\Services\KeishaService;
use App\Services\SamirService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class SheetStepnCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheet:stepn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interacts with STEPN Google Sheet';

    /**
     * Execute the console command.
     *
     * @throws GuzzleException
     */
    public function handle()
    {
        $this->updatePricing();
    }

    private function updatePricing()
    {
        $keisha = new KeishaService();
        $pricing = $keisha->getStepnPricing()->toArray();

        if (count($pricing)) {
            $client = $this->getGoogleClient();
            $service = new \Google_Service_Sheets($client);
            $spreadsheetId = config('services.google.sheet_id_stepn');
            $range = 'Pricing!B3:C8';

            // get values
            $response = $service->spreadsheets_values->get($spreadsheetId, $range, ['valueRenderOption' => 'UNFORMATTED_VALUE']);
            $values = $response->getValues();

            $values[0][0] = $pricing['SOL'];
            $values[1][0] = $pricing['GST'];
            $values[2][0] = $pricing['GMT'];
            $values[3][1] = $pricing['COMFORT1'];
            $values[4][1] = $pricing['COMFORT2'];
            $values[5][1] = $pricing['COMFORT3'];

            $requestBody = new \Google_Service_Sheets_ValueRange([
                'values' => $values
            ]);

            $params = [
                'valueInputOption' => 'RAW'
            ];

            $service->spreadsheets_values->update($spreadsheetId, $range, $requestBody, $params);
            echo "SUCCESS \n";
        }
    }

    public function getGoogleClient()
    {
        $client = new \Google_Client();
        $client->setRedirectUri('http://livia.5oaqkapv5e-e9249vmxw3kr.p.temp-site.link/auth/callback');
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
