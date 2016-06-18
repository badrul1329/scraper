<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Sunra\PhpSimple\HtmlDomParser;

ini_set('max_execution_time', 300);

class homeController extends Controller {

    public function index() {


        $url = 'https://www.tripadvisor.com/TravelersChoice-Hotels-cLuxury-g1';
        $domain = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));
        $html = HtmlDomParser::file_get_html($url);
        $ret = $html->find('div[class=c_content]');

//get Category with links
        foreach ($ret as $div) {
            $categoryName = $div->first_child()->childNodes(1)->plaintext;
            $categoryLink = 'https://www.' . $domain . $div->first_child()->href;
            $categories[] = array('categoryName' => $categoryName, 'categoryLink' => $categoryLink);
        }
        $result['categories'] = $categories;

//      get hotel name & link
        $url = 'https://www.tripadvisor.com/TravelersChoice-Hotels-cLuxury-g1';
        $html = HtmlDomParser::file_get_html($url);
        $ret = $html->find('div[class=winnerName]');
        foreach ($ret as $data) {
            $hotelName = $data->first_child()->plaintext;
            $hotelLink = 'https://www.' . $domain . $data->first_child()->first_child()->href;
            $hotels[] = array('hotelName' => $hotelName, 'hotelLink' => $hotelLink);
        }
        $result['hotels'] = $hotels;

//      get hotel reviews
        $url = 'https://www.tripadvisor.com/Hotel_Review-g309226-d4367721-Reviews-Nayara_Springs-La_Fortuna_de_San_Carlos_Arenal_Volcano_National_Park_Province_of_Alaju.html';

        do {
            $html = HtmlDomParser::file_get_html($url);
            $ret = $html->find('div[class=reviewSelector]');
            foreach ($ret as $data) {
                $title = $data->find('div.quote', 0)->plaintext;
                $body = $data->find('div.entry', 0)->first_child()->plaintext;
                $reviews[] = array('title' => $title, 'body' => $body);
            }
            $url = 'https://www.' . $domain . $html->find('a[class=next]', 0)->href;
        } while (!empty($html->find('a[class=next]', 0)->href));

        $result['reviews'] = $reviews;

        return view('scraper', compact('result'));
    }

}
