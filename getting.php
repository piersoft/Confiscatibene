 <?php



class getdata {


  //monitoraggio temperatura
	public function get_forecast($where)
	{

		switch ($where) {

			 //Lecce centro
			 case "taranto":
			$json_string = file_get_contents("http://api.wunderground.com/api/b3f95b06a21229ff/forecast/lang:IT/q/pws:IPUGLIAT9.json");
			$parsed_json = json_decode($json_string);
			$temp_c1 = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'fcttext_metric'};
			$temp_c2 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[1]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[1]->{'fcttext_metric'};
			$temp_c3 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[2]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[2]->{'fcttext_metric'};
			$temp_c4 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[3]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[3]->{'fcttext_metric'};
			$temp_c5 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[4]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[4]->{'fcttext_metric'};
			$temp_c6 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[5]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[5]->{'fcttext_metric'};

		break;
		case "tarantoggi":
	 $json_string = file_get_contents("http://api.wunderground.com/api/b3f95b06a21229ff/forecast/lang:IT/q/pws:IPUGLIAT9.json");
	 $parsed_json = json_decode($json_string);
	 $temp_c1 = $parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[0]->{'fcttext_metric'};
	 $temp_c2 = "\n".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[1]->{'title'}.", ".$parsed_json->{'forecast'}->{'txt_forecast'}->{'forecastday'}[1]->{'fcttext_metric'};

	break;

	}
	 return $temp_c1.$temp_c2.$temp_c3.$temp_c4.$temp_c5.$temp_c6;

	}

  //scraping dal sito web della PPC Lecce
	public function get_allertameteo($where)
	{
    $allerta="";

      $mysongs = simplexml_load_file('http://www.protezionecivile.puglia.it/feed?cat=14');
    $messaggio=$mysongs->channel->item[0]->{'title'};
    $linkarticolo=$mysongs->channel->item[0]->{'link'};

    $html = file_get_contents($linkarticolo);
    //$html = iconv('ASCII', 'UTF-8//IGNORE', $html);
    $html=utf8_decode($html);

    $html = sprintf('<html><head><title></title></head><body>%s</body></html>', $html);
    $html = sprintf('<html><head><title></title></head><body>%s</body></html>', $html);
    $html = sprintf('<html><head><title></title></head><body>%s</body></html>', $html);


    $doc = new DOMDocument;
    $doc->loadHTML($html);

    $xpa    = new DOMXPath($doc);

    $divs   = $xpa->query('//div[starts-with(@class, "media-body")]//a/@href');
    $divsm   = $xpa->query('//strong[starts-with(@class, "ptitle")]');

    $allertal="";
    foreach($divs as $div) {
        $allertal .= "\n".$div->nodeValue;

    }
    $allertam="";
    foreach($divsm as $div) {
        $allertam .= "\n".$div->nodeValue;

    }

    echo "Ultimo messaggio di allerta del:\n".$allertam;
    echo "Download:\n".$allertal;
    $allerta=$allertam."\nDownload: ".$allertal;

	 return $allerta;

	}
  //scraping dal sito web della PPC Lecce
	public function get_bollettini($where)
	{
    $allerta="";

      $mysongs = simplexml_load_file('http://www.protezionecivile.puglia.it/feed?cat=17');
    $messaggio=$mysongs->channel->item[0]->{'title'};
    $linkarticolo=$mysongs->channel->item[0]->{'link'};

    $html = file_get_contents($linkarticolo);
    //$html = iconv('ASCII', 'UTF-8//IGNORE', $html);
    $html=utf8_decode($html);

    $html = sprintf('<html><head><title></title></head><body>%s</body></html>', $html);
    $html = sprintf('<html><head><title></title></head><body>%s</body></html>', $html);
    $html = sprintf('<html><head><title></title></head><body>%s</body></html>', $html);


    $doc = new DOMDocument;
    $doc->loadHTML($html);

    $xpa    = new DOMXPath($doc);

    $divs   = $xpa->query('//div[starts-with(@class, "media-body")]//a/@href');
    $divsm   = $xpa->query('//strong[starts-with(@class, "ptitle")]');

    $allertal="";
    foreach($divs as $div) {
        $allertal .= "\n".$div->nodeValue;

    }
    $allertam="";
    foreach($divsm as $div) {
        $allertam .= "\n".$div->nodeValue;

    }

    echo "Ultimo bollettino del:\n".$allertam;
    echo "Download:\n".$allertal;
    $allerta=$allertam."\nDownload: ".$allertal;

	 return $allerta;

	}

  public function get_comunicatimeteo($where)
  {
    $allerta="";

      $mysongs = simplexml_load_file('http://www.protezionecivile.puglia.it/feed?cat=6');
    $messaggio=$mysongs->channel->item[0]->{'title'};
    $linkarticolo=$mysongs->channel->item[0]->{'link'};

    $html = file_get_contents($linkarticolo);
    //$html = iconv('ASCII', 'UTF-8//IGNORE', $html);
    $html=utf8_decode($html);

    $html = sprintf('<html><head><title></title></head><body>%s</body></html>', $html);
    $html = sprintf('<html><head><title></title></head><body>%s</body></html>', $html);
    $html = sprintf('<html><head><title></title></head><body>%s</body></html>', $html);
$html=str_replace('var hupso_services_c=new Array("twitter","facebook_like","facebook_send","print");var hupso_counters_lang = "it_IT";var hupso_image_folder_url = "";var hupso_url_c="";var hupso_title_c="','',$html);


    $doc = new DOMDocument;
    $doc->loadHTML($html);

    $xpa    = new DOMXPath($doc);

    $divs   = $xpa->query('//div[starts-with(@class, "blog-content")]');

    $allertal="";
    foreach($divs as $div) {
        $allertal = "\n".$div->nodeValue;

    }



   return $allertal;

  }
	public function get_traffico($where)
	{
	$homepage="";

		switch ($where) {

	case "lecce":
	// un google sheet fa il parsing del dataset presente su dati.comune.lecce.it
	// servizio sperimentale e Demo.
	$csv = array_map('str_getcsv', file("https://docs.google.com/spreadsheets/d/1IfmPLAFr7Ce0Iyd0fj_LQu1EPR0-vJMY5kaWS7IuRAA/pub?output=csv"));
	//$homepage  =$csv[0][0];
	$homepage .="\n";
	$count = 0;
	foreach($csv as $data=>$csv1){
	   $count = $count+1;
	}
	for ($i=1;$i<$count;$i++){

	$homepage .="\n";
	$homepage .="Tipologia: ".$csv[$i][0]."\n";
	$homepage .="Descrizione: ".$csv[$i][1]."\n";
	$homepage .="Data: ".$csv[$i][2]."\n";
	$homepage .="Luogo: ".$csv[$i][3]."\n";
	$homepage .="Puoi visualizzarlo su :\nhttp://www.openstreetmap.org/?mlat=".$csv[$i][4]."&mlon=".$csv[$i][5]."#map=19/".$csv[$i][4]."/".$csv[$i][5];

	//$homepage .="Mappa: http://www.openstreetmap.org/#map=19/".$csv[$i][4]."/".$csv[$i][5];
	$homepage .="\n";


	}

	break;

		}

	 return $homepage;

	}


//monitoraggio temperatura
public function get_temperature()
{


		 $json_string = file_get_contents("http://api.wunderground.com/api/b3f95b06a21229ff/conditions/q/pws:IPUGLIAT9.json");
		 $parsed_json = json_decode($json_string);
		 $location = $parsed_json->{'location'}->{'city'};
		 $temp_c = $parsed_json->{'current_observation'}->{'temp_c'};


	return $temp_c;
}

//definisci il path dell'immagine
public function get_image_path($image)
{
	return "data/". $image. ".jpg";
}

//preleva ultima allerta del feed protezione civile di Prato o in locale o in remoto e ritorna titolo e data.
public function load_prot($islocal)
{
	date_default_timezone_set('UTC');

	$logfile=(dirname(__FILE__).'/logs/storedata.log');

	if($islocal)
	{
		//carico dati salvati in locale per confrontarli con quelli remoti
		$prot_civ=dirname(__FILE__)."/data/prot.xml";
		echo "carico dati in locale";
		print_r($prot_civ);
	}
	else
	{
		//carico dati salvati in remoto
		$prot_civ=PROT_CIV;
		echo "carico dati da remoto";
		print_r($prot_civ);

	}

	$xml_file=simplexml_load_file($prot_civ);

	if ($xml_file==false)
		{
			print("Errore nella ricerca del file relativo alla protezione civile");
		}

		//ritorna il primo elemento del feed rss
		$data[0]=$xml_file->channel->item->title;
		//print_r($data[0]);
		$data[1]=$xml_file->channel->item->pubDate;
		//print_r($data[1]);
		return $data;
}

public function update_prot($data)
{
	$prot_civ=dirname(__FILE__)."/data/prot.xml";

	// load the document
	$info = simplexml_load_file($prot_civ);

	// update
	$info->channel->item->title = $data[0];
	$info->channel->item->pubDate = $data[1];

	// save the updated document
	$info->asXML($prot_civ);

}


}
//Fonti
//http://www.lamma.rete.toscana.it/…/comuni_web/dati/prato.xml
//http://data.biometeo.it/BIOMETEO.xml
//http://data.biometeo.it/PRATO/PRATO_ITA.xml
//http://www.sir.toscana.it/supports/xml/risks_395/".$today.".xml"
//http://www.wunderground.com/weather/api/
//https://github.com/alfcrisci/WU_weather_list/blob/master/WU_stations.csv
 ?>
