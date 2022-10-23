<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use thiagoalessio\TesseractOCR\TesseractOCR;
use aymanrb\UnstructuredTextParser\TextParser;
use Illuminate\Support\Facades\Storage;

class ImageToText extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Image To Text Implement';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $textToParse = (new TesseractOCR($this->argument('path')))->run();
        $textToParse = preg_replace( "/\r|\n/", "", $textToParse );
        $parser = new TextParser(resource_path('text'));
        $result = $parser
            ->parseText($textToParse, true)
            ->getParsedRawData();

        $this->info(json_encode($result));

        return Command::SUCCESS;
    }
}
