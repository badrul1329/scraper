<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Sunra\PhpSimple\HtmlDomParser;

ini_set('max_execution_time', 300);
define('MAX_FILE_SIZE', 900000);

class homeController extends Controller {

    public function index() {
//Placed hotel names
//        $url = 'https://www.tripadvisor.com';
//        $html = HtmlDomParser::file_get_html($url);
//        $ret = $html->find('div[class=featured wrap]');
//        foreach ($ret as $div) {
//            $categoryinfo = $div->find('li[class=sprite-middot]');
//            foreach ($categoryinfo as $data) {
//                $categoryName = $data->plaintext;
//                $categoryLink = $url . $data->first_child()->href;
//                $categories[] = array('categoryName' => $categoryName, 'categoryLink' => $categoryLink);
//            }
//        }
//        $result['categories'] = $categories;

//get hotelinfo from category
        $url = 'https://www.tripadvisor.com/Hotels-g29092-Anaheim_California-Hotels.html';
        //$url = 'https://www.tripadvisor.com/Hotels-g29092-oa60-Anaheim_California-Hotels.html#ACCOM_OVERVIEW';
//        $url = 'https://www.tripadvisor.com/Hotels-g29092-oa90-Anaheim_California-Hotels.html#ACCOM_OVERVIEW';

        $domain = parse_url($url, PHP_URL_HOST);
        
        do{
            $html = HtmlDomParser::file_get_html($url);
            $ret = $html->find('div[class=listing_info]');
            foreach ($ret as $div) {
                $hotelName = $div->find('div[class=listing_title]', 0)->first_child()->plaintext;
                $hotelLink = 'https://' . $domain . $div->find('div[class=listing_title]', 0)->first_child()->href;
                $hotelReviews = $div->find('[class=more]', 0)->plaintext;
                $hotelRate = $div->find('img[class=sprite-ratings]', 0)->alt;
                $Tags = array();
                foreach ($div->find('a[class=tag]') as $data) {
                    $Tags[] = $data->plaintext;
                }
                $hotelTags = $Tags;
                unset($Tags);
                $hotels[] = array('hotelName' => $hotelName, 'hotelLink' => $hotelLink, 'hotelReviews' => $hotelReviews, 'hotelRate' => $hotelRate, 'hotelTags' => $hotelTags);
            }
            if(!empty($html->find('div[class=pagination]',0)->childNodes(1)->href)){
                $url='https://'.$domain.$html->find('div[class=pagination]',0)->childNodes(1)->href;
                echo $url."<br/>";
            }
        }while(!empty($html->find('div[class=pagination]',0)->childNodes(1)->href));
        $result['hotels'] = $hotels;
        

//        $result['link'] = $html->find('div[class=pagination]',0)->childNodes(1)->href;
        $result['link'] = $html->find('a[class=next]',0);

        
        return view('scraper', compact('result'));
    }

}
