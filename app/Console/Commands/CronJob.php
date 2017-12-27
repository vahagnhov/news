<?php

namespace App\Console\Commands;

use App\Article;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Session;


class CronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CronJob:cronjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parsing Data Changed Successfully';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('max_execution_time', 300);//change maximum execution time 300(default 180)
        $client = new Client();
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 60,
        ));
        $client->setClient($guzzleClient);

        for ($i = 1; $i <= 100; $i++) {
            $goutteClient = $client->request('GET', 'http://www.tert.am/en/news/' . $i);
            $goutteClient->filter('#right-col div.news-list .news-blocks')->each(function ($node) {
                $dbUrl = array();
                $articles = DB::table('articles')->get();
                $url = $node->filter('.news-blocks h4 a')->attr('href');
                foreach ($articles as $article) {
                    $dbUrl[] = $article->url;
                }
                if (!in_array($url, $dbUrl)) {
                    $title = $node->filter('.news-blocks h4 a')->text();
                    $description = $node->filter('.news-blocks p.nl-anot a')->text();
                    $photo = $node->filter('.news-blocks a img')->attr('src');
                    $dateAndCategory = $node->filter('.news-blocks p.nl-dates:not(a)')->text();
                    $dateStr = substr($dateAndCategory, 11, 8);
                    $dateInt = strtotime($dateStr);
                    $date = date('Y-m-d', $dateInt);
                    $image = public_path('/upload/photo/' . $photo); // get all image names
                    if (file_exists($image)) {
                        unlink($image); // delete image
                    }
                    $extension = pathinfo($photo, PATHINFO_EXTENSION);
                    $imgName = str_random(10) . str_slug(substr($title, -7), '-') . "." . $extension;
                    $img = file_get_contents($photo);
                    $save = file_put_contents(public_path("upload/photo/" . $imgName), $img);
                    if ($save) {
                        DB::table('articles')->insert(
                            ['title' => $title, 'description' => $description, 'photo' => $imgName, 'date' => $date, 'url' => $url]
                        );
                    }

                }
            });

        }
        $this->info('Parsing Data Changed Successfully!');
    }
}
