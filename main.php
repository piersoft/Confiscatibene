<?php
/**
* Telegram Bot Beni Immobili Confiscati alla Mafia Lic. CC-BY
* @author Francesco Piero Paolicelli @piersoft
*/
//include("settings_t.php");
include("Telegram.php");

class mainloop{
const MAX_LENGTH = 4096;
function start($telegram,$update)
{

	date_default_timezone_set('Europe/Rome');
	$today = date("Y-m-d H:i:s");
	//$data=new getdata();
	// Instances the class

	/* If you need to manually take some parameters
	*  $result = $telegram->getData();
	*  $text = $result["message"] ["text"];
	*  $chat_id = $result["message"] ["chat"]["id"];
	*/


	$text = $update["message"] ["text"];
	$chat_id = $update["message"] ["chat"]["id"];
	$user_id=$update["message"]["from"]["id"];
	$location=$update["message"]["location"];
	$reply_to_msg=$update["message"]["reply_to_message"];

	$this->shell($telegram,$text,$chat_id,$user_id,$location,$reply_to_msg);
	$db = NULL;

}

//gestisce l'interfaccia utente
 function shell($telegram,$text,$chat_id,$user_id,$location,$reply_to_msg)
{
	date_default_timezone_set('Europe/Rome');
	$today = date("Y-m-d H:i:s");

	if ($text == "/start") {
		$reply = "Benvenuto. Per ricercare un bene immobile confiscato alla Mafia, clicca sulla graffetta (📎) e poi 'posizione' oppure digita il nome del Comune. Verrà interrogato il Dataset 'Lista completa dei beni immobili - ANBSC' realizzato da ConfiscatBene.it su base dati ANBSC ,utilizzabile con licenza CC-BY 4.0 e verranno elencati i beni immobili confiscati alla Mafia. In qualsiasi momento scrivendo /start ti ripeterò questo messaggio di benvenuto.\nQuesto bot è stato realizzato da @piersoft con rilascio del codice sorgente per libero riuso. La propria posizione viene ricercata grazie al geocoder di openStreetMap con Lic. odbl.";
		$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
		$log=$today. ";new chat started;" .$chat_id. "\n";

		}

		//gestione segnalazioni georiferite
		elseif($location!=null)
		{

			$this->location_manager($telegram,$user_id,$chat_id,$location);
			exit;

		}
//elseif($text !=null)

		else{
			$location="Sto cercando i beni confiscati alla Mafia del Comune di: ".$text;
			$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
			$telegram->sendMessage($content);
			sleep (1);
			$text=str_replace(" ","%20",$text);
			$data="http://www.confiscatibene.it/it/api/action/datastore/search.json?resource_id=e5b4d63a-e1e8-40a3-acec-1d351f03ee56&filters[Comune]=".$text;
			$json_stringdata = file_get_contents($data);
			$parsed_jsondata = json_decode($json_stringdata,true);
			$temp_data="";
			$count=0;
			foreach($parsed_jsondata['result']['records'] as $data=>$csv1){
				 $count = $count+1;
			}
			//echo $count;
			if ($count ==0){
				$location="Nel Comune di ".$text." non risultano beni confiscati.";
				$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
				$telegram->sendMessage($content);
				exit;
			}
			for ($i=0;$i<$count;$i++){

				$temp_data .="\nComune: ".$parsed_jsondata['result']['records'][$i]['Comune'];
				$temp_data .="\nNome: ".$parsed_jsondata['result']['records'][$i]['Nome'];
				$temp_data .="\nTipologia: ".$parsed_jsondata['result']['records'][$i]['Tipologia'];
				$temp_data .="\nCategoria: ".$parsed_jsondata['result']['records'][$i]['Categoria'];
				$temp_data .="\nSottocategoria: ".$parsed_jsondata['result']['records'][$i]['Sottocategoria'];
				$temp_data .="\nStato: ".$parsed_jsondata['result']['records'][$i]['Stato'];
	if($parsed_jsondata['result']['records'][$i]['Ente destinatario'] != NULL) $temp_data .="\nEnte destinatario: ".$parsed_jsondata['result']['records'][$i]['Ente destinatario'];
	if($parsed_jsondata['result']['records'][$i]['Dettaglio del bene'] != NULL)	$temp_data .="\nDettaglio del bene: ".$parsed_jsondata['result']['records'][$i]['Dettaglio del bene'];
	if($parsed_jsondata['result']['records'][$i]['Decreto di destinazione'] != NULL) $temp_data .="\nDecreto di destinazione: ".$parsed_jsondata['result']['records'][$i]['Decreto di destinazione'];
				$temp_data .="\nAggiornamento: ".date('m/d/Y', $parsed_jsondata['result']['records'][$i]['timestamp']);
				$temp_data .="\n_________\n";
			}

				$chunks = str_split($temp_data, self::MAX_LENGTH);
				foreach($chunks as $chunk) {
				$forcehide=$telegram->buildForceReply(true);
				$content = array('chat_id' => $chat_id, 'text' => $chunk,'disable_web_page_preview'=>true);
				$telegram->sendMessage($content);

				}


				$content = array('chat_id' => $chat_id, 'text' => "Digita un Comune oppure invia la tua posizione tramite la graffetta (📎). Digitando /start hai le info sul progetto");
					$telegram->sendMessage($content);

		}

}


// Crea la tastiera
function create_keyboard($telegram, $chat_id)
 {
	 $forcehide=$telegram->buildKeyBoardHide(true);
	 $content = array('chat_id' => $chat_id, 'text' => "Invia la tua posizione cliccando sulla graffetta (📎) in basso e, se vuoi, puoi cliccare due volte sulla mappa e spostare il Pin Rosso in un luogo specifico", 'reply_markup' =>$forcehide);
	 $telegram->sendMessage($content);

 }




function location_manager($telegram,$user_id,$chat_id,$location)
	{

			$lon=$location["longitude"];
			$lat=$location["latitude"];
			$response=$telegram->getData();
			$reply="http://nominatim.openstreetmap.org/reverse?email=piersoft2@gmail.com&format=json&lat=".$lat."&lon=".$lon."&zoom=18&addressdetails=1";
			$json_string = file_get_contents($reply);
			$parsed_json = json_decode($json_string);
			//var_dump($parsed_json);
			$comune="";
			$temp_c1 =$parsed_json->{'display_name'};

			if ($parsed_json->{'address'}->{'town'}) {
				$temp_c1 .="\nCittà: ".$parsed_json->{'address'}->{'town'};
				$comune .=$parsed_json->{'address'}->{'town'};
			}else 	$comune .=$parsed_json->{'address'}->{'city'};

			$alert="";
			$location="Sto cercando i beni confiscati alla Mafia del Comune di: ".$comune;
			$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
			$telegram->sendMessage($content);
			sleep (1);
			$comune=str_replace(" ","%20",$comune);
			$data="http://www.confiscatibene.it/it/api/action/datastore/search.json?resource_id=e5b4d63a-e1e8-40a3-acec-1d351f03ee56&filters[Comune]=".$comune;
			$json_stringdata = file_get_contents($data);
			$parsed_jsondata = json_decode($json_stringdata,true);
			$temp_data="";
			$count=0;
			foreach($parsed_jsondata['result']['records'] as $data=>$csv1){
				 $count = $count+1;
			}
			//echo $count;
			if ($count ==0){
				$location="Nel Comune di".$text." non risultano beni confiscati.";
				$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
				$telegram->sendMessage($content);
				exit;
			}
			for ($i=0;$i<$count;$i++){

				$temp_data .="\nComune: ".$parsed_jsondata['result']['records'][$i]['Comune'];
				$temp_data .="\nNome: ".$parsed_jsondata['result']['records'][$i]['Nome'];
				$temp_data .="\nTipologia: ".$parsed_jsondata['result']['records'][$i]['Tipologia'];
				$temp_data .="\nCategoria: ".$parsed_jsondata['result']['records'][$i]['Categoria'];
				$temp_data .="\nSottocategoria: ".$parsed_jsondata['result']['records'][$i]['Sottocategoria'];
				$temp_data .="\nStato: ".$parsed_jsondata['result']['records'][$i]['Stato'];
	if($parsed_jsondata['result']['records'][$i]['Ente destinatario'] != NULL)$temp_data .="\nEnte destinatario: ".$parsed_jsondata['result']['records'][$i]['Ente destinatario'];
	if($parsed_jsondata['result']['records'][$i]['Dettaglio del bene'] != NULL)			$temp_data .="\nDettaglio del bene: ".$parsed_jsondata['result']['records'][$i]['Dettaglio del bene'];
	if($parsed_jsondata['result']['records'][$i]['Decreto di destinazione'] != NULL)			$temp_data .="\nDecreto di destinazione: ".$parsed_jsondata['result']['records'][$i]['Decreto di destinazione'];
				$temp_data .="\nAggiornamento: ".date('m/d/Y', $parsed_jsondata['result']['records'][$i]['timestamp']);
				$temp_data .="\n_________\n";
			}

				$chunks = str_split($temp_data, self::MAX_LENGTH);
				foreach($chunks as $chunk) {
				$forcehide=$telegram->buildForceReply(true);
				$content = array('chat_id' => $chat_id, 'text' => $chunk,'disable_web_page_preview'=>true);
				$telegram->sendMessage($content);

				}


				$content = array('chat_id' => $chat_id, 'text' => "Digita un Comune oppure invia la tua posizione tramite la graffetta (📎). Digitando /start hai le info sul progetto");
					$telegram->sendMessage($content);

		}


}

?>
