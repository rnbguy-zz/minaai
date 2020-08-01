<?php //include("Natural-Grammar/class.grammar.php");?>
<h1>Writer-Pal</h1>
<h2>A content writer's best pal</h2>
<img src="pup.jpg" style="width:10%;">
<form method="post" name="scrap_form" id="scrap_form" action="">
<br/>Enter Article Keywords/Title (e.g. dog care tips): <input type="input" name="website_heading" id="website_heading" value="dog care tips">
<br/>Your Email:<input type="input" name="email" id="email">
<Br/>
Word limit: <select name="wordlimit" id="wordlimit" form="scrap_form">
  <option value="3000">3000</option>
  <option value="5000">5000</option>
  <option value="8000">8000</option>
  <option value="10000">10000</option>
</select>
<input type="submit" name="submit" value="Write!" >

</form>
<br/>
<?php

error_log("email:".$_POST["email"]);
error_log("topic:".$_POST["website_heading"]);
//echo shell_exec("python3 /var/www/html/radar/Text-Rewrite-NLP/rewrite.py 'i love dogs'");

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
$wordcount=0;


$json_string = "https://api.twingly.com/blog/search/api/v3/search";
//echo $json_string;
$query_array = array (
    'q' => 'fields:title '.str_replace(' ','+',$_POST["website_heading"]),
    'apikey' => '49843C6C-57CC-41D2-B8BE-851C4D0EE77F'
);
$query = http_build_query($query_array);
$jsondata=file_get_contents($json_string . '?' . $query);

$xml = simplexml_load_string($jsondata);
$json = json_encode($xml);
$obj = json_decode($json,TRUE);
//echo $json;

//associations
$json_string = 'https://api.wordassociations.net/associations/v1.0/json/search?apikey=8054aeba-b5ff-4de5-a509-c2145f4f9b6e&text='.$_POST["website_heading"].'&lang=en';
//echo $json_string;
$jsondata = file_get_contents($json_string);
$objass = json_decode($jsondata,true);
//echo json_encode($jsondata);
$ass = array();
array_push($ass,$_POST["website_heading"]);
foreach ($objass[response] as $json) {
 foreach ($json as $response) {
  foreach ($response as $mywords) {
	//echo "words:".$mywords[item];
	array_push($ass,$mywords[item]);
		}
	}
 }


//heading
echo "<h1>".$_POST["website_heading"]."</h1>";
echo "In this article we'll talk about why you should consider ".$_POST["website_heading"]." and why this is important these days.";


foreach ($obj['post'] as $url) {

			//remove non-ascii characters from articles
			$strings = preg_replace('/[^\x20-\x7E]/','',$url[text]);

			if(strposa($strings, $ass, 1) && stripos($strings, '.com') === false && stripos($strings, 'movie') === false 
&& substr_count($strings, ' ') > 20 && $wordcount < 100 && stripos($strings, '$') === false && stripos($strings, 'http') === false
&& stripos($strings, '@') === false)
			{
			//echo $strings."<br><br/>";
			echo shell_exec("python3 /var/www/html/radar/Text-Rewrite-NLP/rewrite.py '".substr($strings, 0, 500)."'");
			echo "...<br><br/>";
			//echo $strings;
			$wordcount += substr_count($strings, ' ');

    			}//end if
		}//end for each.
echo '

<br/><img src="blurr2.png" style="width:100%;">
<br/><img src="blurr2.png" style="width:100%;">
<br/><img src="blurr2.png" style="width:100%;">
<br><h2>Register for Full Article.</h2>
';
	}





	function strposa($haystack, $needles=array(), $offset=0) {
        	$chr = array();
        	foreach($needles as $needle) {
                	$res = strpos($haystack, $needle, $offset);
                	if ($res !== false) $chr[$needle] = $res;
        	}
        	if(empty($chr)) return false;
        	return min($chr);
	}

?>


