<?php

namespace App\Console\Commands;

use App\Services\KeishaService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

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
     * @throws GuzzleException
     */
    public function handle(): int
    {
        $keisha = new KeishaService();
        $pricing = $keisha->getPricing()->toArray();

        if (count($pricing)) {
            $client = $this->getGoogleClient();
            $service = new \Google_Service_Sheets($client);
            $spreadsheetId = config('services.google.sheet_id');
            $range = 'Details!F2:H12';

            // get values
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();

            $values[1][1] = $pricing['BNB'];
            $values[2][1] = $pricing['ACH'];
            $values[3][1] = $pricing['AXS'];
            $values[4][1] = $pricing['BTC'];
            $values[5][0] = $pricing['BUSD'];
            $values[5][2] = $pricing['AUD'];
            $values[6][1] = $pricing['SOL'];
            $values[10][1] = $pricing['GMT'];

            $requestBody = new \Google_Service_Sheets_ValueRange([
                'values' => $values
            ]);

            $params = [
                'valueInputOption' => 'RAW'
            ];

            $service->spreadsheets_values->update($spreadsheetId, $range, $requestBody, $params);
            echo "SUCCESS \n";
            return Command::SUCCESS;
        }

        return Command::FAILURE;
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
