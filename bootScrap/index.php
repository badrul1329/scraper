<?php
ini_set('display_errors', 'on');
ini_set('max_execution_time', 300);
include('simple_html_dom.php');

//$url='https://www.tripadvisor.com/TravelersChoice-Hotels-cLuxury-g1';
//$domain = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));
//$html = file_get_html($url);
//$ret = $html->find('div[class=c_content]');
////get Caegory with links
//foreach ($ret as $div) {
//    $categoryName=$div->first_child()->childNodes(1)->plaintext;
//    $categoryLink='https://www.'.$domain.$div->first_child()->href; 
//    //get hotel name and Link and reviews
//    $categories[] = array('categoryName'=>$categoryName,'categoryLink'=>$categoryLink);
//}
//$result=array('categories'=>$categories);

//get hotel name & link
//$url='https://www.tripadvisor.com/TravelersChoice-Hotels-cLuxury-g1';
//$domain = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));
//$html = file_get_html($url);
//$ret = $html->find('div[class=winnerName]');
//foreach($ret as $data){
//    $hotelName=$data->first_child()->plaintext;
//    $hotelLink='https://www.'.$domain.$data->first_child()->first_child()->href;
//    $hotels[]=array('hotelName'=>$hotelName,'hotelLink'=>$hotelLink);
//}
//$result=array('hotels'=>$hotels);

//get hotel reviews
$url='https://www.tripadvisor.com/Hotel_Review-g309226-d4367721-Reviews-Nayara_Springs-La_Fortuna_de_San_Carlos_Arenal_Volcano_National_Park_Province_of_Alaju.html';
$domain = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));

do{
    $html = file_get_html($url);
    $ret = $html->find('div[class=reviewSelector]');
    foreach ($ret as $data) {
        $title = $data->find('div.quote', 0)->plaintext;
        $body = $data->find('div.entry', 0)->first_child()->plaintext;
//        $reviews[] = array('title' => $title, 'body' => $body,'next'=>$url);
        $reviews[] = array('title' => $title, 'body' => $body);
    }
	
    if ($next = $html->find('a[class=next]', 0)) {
        $url = 'https://www.' . $domain . $next->href;  
    }
}while($html->find('a[class=next]',0));

$result=array('reviews'=>$reviews);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Content Finder</title>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="css/bootstrap.min.css" >

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            <h1><u>Content Finder</u></h1>
            <div class="row">

                <div class="col-lg-12">
                    <form action="" method="post">
                        <input type="url" class="form-control" placeholder="https://www.sitename.com/..." name="url" required="required">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Class Name" name="className" required="required">
                            <span class="input-group-btn">
                                <input class="btn btn-success" type="submit" value="Go!" name="btn">
                            </span>
                        </div>

                    </form>
                </div>
            </div>
            <br/>
            <div class="row">
                <div class="panel panel-success">
                    <div class="panel-heading">Result</div>
                    <div class="panel-body">
                        <?php
                        echo "<pre>";
                        print_r($result);
                        ?>
                    </div>
                </div>
            </div>
        </div>


        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>