<?php
include('settings_t.php');
$lat=40.6734;
$lon=16.6038;
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
//echo ucfirst($comune);
$data="http://www.confiscatibene.it/it/api/action/datastore/search.json?resource_id=e5b4d63a-e1e8-40a3-acec-1d351f03ee56&filters[Comune]=".$comune;
$json_stringdata = file_get_contents($data);
$parsed_jsondata = json_decode($json_stringdata,true);
$temp_data="";
$count=0;
foreach($parsed_jsondata['result']['records'] as $data=>$csv1){
	 $count = $count+1;
}
//echo $count;
for ($i=0;$i<$count;$i++){

$temp_data .="\nComune: ".$parsed_jsondata['result']['records'][$i]['Comune'];
$temp_data .="\nNome: ".$parsed_jsondata['result']['records'][$i]['Nome'];
$temp_data .="\nTipologia: ".$parsed_jsondata['result']['records'][$i]['Tipologia'];
$temp_data .="\nCategoria".$parsed_jsondata['result']['records'][$i]['Categoria'];
$temp_data .="\nSottocategoria".$parsed_jsondata['result']['records'][$i]['Sottocategoria'];
$temp_data .="\nStato".$parsed_jsondata['result']['records'][$i]['Stato'];
$temp_data .="\nEnte destinatario".$parsed_jsondata['result']['records'][$i]['Ente destinatario'];
$temp_data .="\nDettaglio del bene".$parsed_jsondata['result']['records'][$i]['Dettaglio del bene'];
$temp_data .="\nDecreto di destinazione".$parsed_jsondata['result']['records'][$i]['Decreto di destinazione'];
$temp_data .="\nAggiornamento".date('m/d/Y', $parsed_jsondata['result']['records'][$i]['timestamp']);

}
echo $temp_data;

?>
