<?php
$plug_cmds = array('rule34', 'r34','р34', '34', 'рул34');
$plug_smalldescription = 'Выводит картинку правила 34 по тегу';
$plug_mainfunc = function ($kb_msg, $kb_cmds){
		$text = $kb_msg[5];
						$answer = explode(' ',$text);
						unset($answer[0]);
						unset($answer[1]);
						$answer = implode(' ',$answer);
						$answer = str_replace(' ','',$answer);
						//$answer = implode(' ',$answer);
						$data = file_get_contents('http://oj2wyzjtgqxhq6dy.cmle.ru/index.php?page=dapi&s=post&q=index&limit=100&tags=-fur+-furry+-dragon+-animal_penis+-animal+-wolf+-fox+-webm+-my_little_pony+-monster*+-3d+-animal*+-ant+-insects+-mammal+-horse+-blotch+-deer+-real*+-shit+'.$answer);
						$p = xml_parser_create();
						xml_parse_into_struct($p, $data, $vals, $index);
						xml_parser_free($p);
						//print_r($vals);
						
						$zcount=0;
						for(;;){
							$thisrand = rand(1,count($vals));
							$pic = $vals[$thisrand]['attributes']['FILE_URL'];
							$picture = $vals[$thisrand]['attributes']['FILE_URL'];
							//print_r($vals[$thisrand]['attributes']);
							$tags = $vals[$thisrand]['attributes']['TAGS'];
							$test = $vals[$thisrand]['attributes'];
							if (!empty($pic)){
								//apisay(print_r($vals[$thisrand]['attributes'],true),$kb_msg[3],$kb_msg[1]);
								//print_r($vals[$thisrand]['attributes']);
								break;	
							}
							if ($zcount >= 20){
								$empty = 'двачую';
								//$mess='Ничего не найдено :(';
								break;
							}
						$zcount++;
						}
						if ($empty=='двачую'){
							$mess='Ничего не найдено :(';
							}else{
								$mess = 'Дрочевня по тегу '.$answer.'<br>('.$thisrand.'/'.count($vals).')<br>'.'----------<br>Остальные теги: '.$tags;
								}
						$pic = str_replace('https://img.rule34.xxx/','http://oj2wyzjtgqxhq6dy.cmle.ru/',$pic);
						$pic = str_replace('https://rule34.xxx/','http://oj2wyzjtgqxhq6dy.cmle.ru/',$pic);
						$tmpf = explode(".", $pic);
    					$filecheck = array_pop(explode(".", $pic));
						file_put_contents('./plugins/rule34/pics/pic.'.$filecheck,file_get_contents($pic));
						$req = array(
							'v' => '5.68',
							'access_token' => KB_TOKEN,
						);
						$get_params = http_build_query($req);
						$url = json_decode(file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?'. $get_params));
						$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						$pics = scandir('./plugins/rule34/pics');
						unset($pics[0]);
						unset($pics[1]);
						$parameters = array(
							'file1' => new CURLFile('./plugins/rule34/pics/'.$pics[2])
						);
						curl_setopt($ch, CURLOPT_URL, $url->response->upload_url);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$curl_result = curl_exec($ch);
						curl_close($ch);
						$res = json_decode($curl_result);


						$req = array(
							'v' => '5.68',
							'album_id' => '231057848',
							'server' => $res->server,
							'photo' => $res->photo,
							'hash' => $res->hash,
							'access_token' => KB_TOKEN
						);
						$postdata = http_build_query($req);
						$opts = array('http' =>
							array(
								'method'  => 'POST',
								'header'  => 'Content-type: application/x-www-form-urlencoded',
								'content' => $postdata
							)
						);

						$context  = stream_context_create($opts);
						$res = json_decode(file_get_contents('https://api.vk.com/method/photos.saveMessagesPhoto?'. $get_params, false, $context));
						$req = array(
							'v' => '5.68',
							'peer_id' => $kb_msg[3],
							'attachment' => 'photo'.$res->response[0]->owner_id.'_'.$res->response[0]->id,
							'message' => $mess,
							'forward_messages' => $kb_msg[1],
							'access_token' => KB_TOKEN
						);
						$get_params = http_build_query($req);
						$res = json_decode(file_get_contents('https://api.vk.com/method/messages.send?'. $get_params));
						$pics = scandir('./plugins/rule34/pics');
						unset($pics[0]);
						unset($pics[1]);
						unlink('./plugins/rule34/pics/'.$pics[2]);
						unset($text,$answer,$data,$p,$zcount,$mess,$pic,$val,$index,$p,$req,$get_params,$url,$ch,$pics,$parametres,$curl_result,$res,$postdata,$opts,$context);
};

register_command(array(
		'cmds' => $plug_cmds,
		'cmdfunc' => $plug_mainfunc,
		'smalldescription' => $plug_smalldescription,
		'adminonly' => '1'
));
