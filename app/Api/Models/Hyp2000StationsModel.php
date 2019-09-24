<?php

namespace App\Api\Models;

use Illuminate\Database\Eloquent\Model;

use Cache;
use App\Api\Traits\FortranFormatTrait;

class Hyp2000StationsModel extends Model
{
    use FortranFormatTrait;
	
	public static function DECtoDMS($latitude, $longitude)
	{
		$latitudeDirection = $latitude < 0 ? 'S': 'N';
		$longitudeDirection = $longitude < 0 ? 'W': 'E';

		$latitudeNotation = $latitude < 0 ? '-': '';
		$longitudeNotation = $longitude < 0 ? '-': '';

		$latitudeInDegrees = floor(abs($latitude));
		$longitudeInDegrees = floor(abs($longitude));

		$latitudeDecimal = abs($latitude)-$latitudeInDegrees;
		$longitudeDecimal = abs($longitude)-$longitudeInDegrees;

		$_precision = 4;
		$latitudeMinutes = round($latitudeDecimal*60,$_precision);
		$longitudeMinutes = round($longitudeDecimal*60,$_precision);
		
		return [
			'lat' => [
				'notation'	=> $latitudeNotation,
				'degrees'	=> $latitudeInDegrees,
				'minutes'	=> $latitudeMinutes,
				'direction'	=> $latitudeDirection,
				],
			'lon' => [
				'notation'	=> $longitudeNotation,
				'degrees'	=> $longitudeInDegrees,
				'minutes'	=> $longitudeMinutes,
				'direction'	=> $longitudeDirection,
				]
		];
		/*
		return sprintf('%s%s %s%s %s%s %s%s',
			$latitudeNotation,
			$latitudeInDegrees,
			$latitudeMinutes,
			$latitudeDirection,
			$longitudeNotation,
			$longitudeInDegrees,
			$longitudeMinutes,
			$longitudeDirection
		);
		*/
	}
	
	public static function getData($input_parameters, $timeoutMinutes = 2880) {
		\Log::debug("START - ".__CLASS__.' -> '.__FUNCTION__);
		
		/* Get all FDSNWS nodes */
		$fdsnws_nodes    = config('hyp2000-ws.fdsnws_nodes');
		
		/* Set Url params */
		$urlParams = 'level=channel&net='.$input_parameters['net'].'&sta='.$input_parameters['sta'].'&cha='.$input_parameters['cha'];
		if ( isset($input_parameters['loc']) ) {
			$urlParams .= '&loc='.$input_parameters['loc'];
		}
		if ( isset($input_parameters['starttime']) ) {
			$urlParams .= '&starttime='.$input_parameters['starttime'];
		}
		if ( isset($input_parameters['endtime']) ) {
			$urlParams .= '&endtime='.$input_parameters['endtime'];
		}
		if ( isset($input_parameters['cache']) ) {
			$cache = $input_parameters['cache'];
		} else {
			$cache = "true";
		}

        /* Count '$fdsnws_nodes' elements */
        $n_fdsnws_nodes = count($fdsnws_nodes);
				
		/* Retrieve XML from FDSNWS node and return HYP2000 station line */
        $n_cicle = 0;
		foreach ($fdsnws_nodes as $fdsnws_node) {
            $n_cicle++;
            
			$url = 'http://'.$fdsnws_node.'/fdsnws/station/1/query?'.$urlParams;
			
			// Closure for executing a request url
			$func_execute_request_url = function() use ($url) {
				/* */
				$str_pad_string = ' ';				
				
				/* Retrieve StationXML */
				$urlOutput = self::getXml($url);
				
				$urlOutputData				= $urlOutput['data'];
				$urlOutputHttpStatusCode	= $urlOutput['httpStatusCode'];
				\Log::debug(" urlOutputHttpStatusCode=".$urlOutputHttpStatusCode);

				if ( $urlOutputHttpStatusCode == 200 ) {
					$xml = simplexml_load_string($urlOutputData);
					$text = '';
					foreach ($xml->Network as $network) {
						$net					= (string) $network->attributes()->code;
						foreach ($network->Station as $station) {
							$sta				= (string) $station->attributes()->code;
							foreach ($station->Channel as $channel) {
								$cha            = (string) $channel->attributes()->code;
								$loc            = (string) $channel->attributes()->locationCode;
								$lat			= (double) $channel->Latitude;
								$lon			= (double) $channel->Longitude;
								$elev			= (double) $channel->Elevation;

								/* Convert 'lat' and 'lon' */
								$arrayDmsLatLon = self::DECtoDMS($lat, $lon);
								
								/* Format data */
								$staFormatted		= self::fromFortranFormatToString('A5',   $sta, $str_pad_string, STR_PAD_RIGHT);
								$netFormatted		= self::fromFortranFormatToString('A2',   $net, $str_pad_string);
								$chaCompFormatted	= self::fromFortranFormatToString('A1',   substr($cha, 2, 1), $str_pad_string);
								$chaFormatted		= self::fromFortranFormatToString('A3',   $cha, $str_pad_string);
								$blank				= self::fromFortranFormatToString('1X',   null, $str_pad_string);
								$latDegFormatted	= self::fromFortranFormatToString('I2',   $arrayDmsLatLon['lat']['degrees'], $str_pad_string);
								$latMinFormatted	= self::fromFortranFormatToString('F7.4', $arrayDmsLatLon['lat']['minutes'], $str_pad_string);
								$latDirFormatted	= self::fromFortranFormatToString('A1',	$arrayDmsLatLon['lat']['direction'], $str_pad_string);
								$lonDegFormatted	= self::fromFortranFormatToString('I3',   $arrayDmsLatLon['lon']['degrees'], $str_pad_string);
								$lonMinFormatted	= self::fromFortranFormatToString('F7.4', $arrayDmsLatLon['lon']['minutes'], $str_pad_string);
								$lonDirFormatted	= self::fromFortranFormatToString('A1',   $arrayDmsLatLon['lon']['direction'], $str_pad_string);
								$elevFormatted		= self::fromFortranFormatToString('I4',   explode(".", $elev)[0], $str_pad_string);

								$arrayKey = $net.'_'.$sta.'_'.$cha.'_'.$loc;
								$text = 
										$staFormatted.
										$blank.
										$netFormatted.
										$blank.
										$chaCompFormatted.
										$chaFormatted.
										$blank.
										$blank.
										$latDegFormatted.
										$blank.
										$latMinFormatted.
										$latDirFormatted.
										$lonDegFormatted.
										$blank.
										$lonMinFormatted.
										$lonDirFormatted.
										$elevFormatted.
										$blank.
										$blank.
										$blank.
										$blank.
										$blank.
										"1".
										$blank.
										$blank.
										"0.00".
										$blank.
										$blank.
										"0.00".
										$blank.
										$blank.
										"0.00".
										$blank.
										$blank.
										"0.00".
										$blank.
										"0".
										$blank.
										$blank.
										"1.00--".
										"\n";
							}
						}
					}
					$return = $text;
				} else {
					$return = '--';
				}
				\Log::debug("  CACHING: url=".$url." -> value=".$return);
				return $return;
			};
			
			if ( config('hyp2000-ws.enableCache') ) {
				\Log::debug(' Query cache enabled (timeout='.$timeoutMinutes.')');
				if ($cache == "false") {
					\Log::debug('  forget cache');
					Cache::forget(md5($url));
				}
                $hyp2000StationLine = Cache::remember(md5($url), $timeoutMinutes, $func_execute_request_url);
			} else {
				\Log::debug(' Query cache NOT enabled');
				$hyp2000StationLine = $func_execute_request_url();
			}
			
			\Log::debug(" Output:");
            \Log::debug("  url=".$url);
            \Log::debug("  hyp2000StationLine=".$hyp2000StationLine);
			\Log::debug("END - ".__CLASS__.' -> '.__FUNCTION__);
			
            if ($hyp2000StationLine != '--' || $n_cicle == $n_fdsnws_nodes) {
                if ($hyp2000StationLine == '--') {
                    return null;
                } else {
                    return $hyp2000StationLine;
                }
            }
		}
		
	}
	
    public static function getXml($url) {
        \Log::debug("START - ".__CLASS__.' -> '.__FUNCTION__);
        //
        $client = new \GuzzleHttp\Client([
            'timeout'  => 120.0,
        ]);
        $baseGuzzleUri = $url;

        //
        $outputData      = "";
        $outputHttpStatusCode = 404;
                
        try {
            \Log::debug("   step_1a: ".$baseGuzzleUri);
            $res = $client->request('GET', $baseGuzzleUri);
            \Log::debug("   step_2");
            if ($res->getStatusCode() == 200) {
                \Log::debug("   step_3a - httpStatusCode=".$res->getStatusCode());
                $outputData      = $res->getBody()->getContents();
            } else {
                \Log::debug("   step_3b - httpStatusCode=".$res->getStatusCode());
            }
            $outputHttpStatusCode = $res->getStatusCode();
        } catch ( \GuzzleHttp\Exception\ServerException $e) {
            \Log::debug("   step_1b");
            \Log::debug("    getCode:".$e->getCode());
            \Log::debug("    getMessage:".$e->getMessage());
            $outputHttpStatusCode = $e->getCode();
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            \Log::debug("   step_2b");
            \Log::debug("    getCode:".$e->getCode());
            \Log::debug("    getMessage:".$e->getMessage());
            $outputHttpStatusCode = $e->getCode();
        } catch (Exception $e) {
            \Log::debug("   step_3b");
        }
        
        \Log::debug("END - ".__CLASS__.' -> '.__FUNCTION__);
        return $outputData = [
            'data'              =>  $outputData,
            'httpStatusCode'    =>  $outputHttpStatusCode
        ];
    }
}
