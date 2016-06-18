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


        
        return view('scraper', compact('result'));
    }

}
