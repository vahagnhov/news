<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Session;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function parse()
    {
        ini_set('max_execution_time', 300);//change maximum execution time 300(default 180)
        $client = new Client();
        $guzzleClient = new GuzzleClient(array(
            'timeout' => 60,
        ));
        $client->setClient($guzzleClient);


        for ($i = 1; $i <= 100; $i++) {
            $goutteClient = $client->request('GET', 'http://www.tert.am/en/news/' . $i);
            $goutteClient->filter('.inner-content .list--max-height-none')->each(function ($node) {
                $dbUrl = array();
                $articles = DB::table('articles')->get();
                $url = $node->filter('.list__inner-box a')->attr('href');
                foreach ($articles as $article) {
                    $dbUrl[] = $article->url;
                }
                if (!in_array($url, $dbUrl)) {
                    $title = $node->filter('.list__inner-box span.list__title')->text();
                    $description = $node->filter('.list__inner-box span.list__description')->text();
                    $photo = $node->filter('.list__inner-box a img')->attr('src');
                    $dateAndCategory = $node->filter('.list__inner-box span.list__date')->text();

                    $dateStr = substr($dateAndCategory, 11, 8);
                    $dateInt = strtotime($dateStr);
                    $date = date('Y-m-d', $dateInt);

                    $image = public_path('/upload/photo/' . $photo); // get all image names

                    if (file_exists($image)) {
                        @unlink($image); // delete image
                    }

                    $extension = pathinfo($photo, PATHINFO_EXTENSION);
                    $imgName = str_random(10) . str_slug(substr($title, -7), '-') . "." . $extension;
                    $img = file_get_contents("https://www.tert.am".$photo);


                    $save = file_put_contents(public_path("upload/photo/" . $imgName), $img);
                    if ($save) {
                        DB::table('articles')->insert(
                            [
                                'title' => $title,
                                'description' => $description,
                                'photo' => $imgName,
                                'date' => $date,
                                'url' => $url
                            ]
                        );
                    }

                }
            });

        }
        return redirect('admin/article')->with('status', 'Parsing successfully finished');
    }

}