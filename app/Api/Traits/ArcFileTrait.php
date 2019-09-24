<?php
namespace App\Api\Traits;

use App\Api\Traits\FortranFormatTrait;
 
trait ArcFileTrait {
    
    use FortranFormatTrait;
    
	/**
	 * @brief Returns an array template for P phase of ARC file
	 *
	 *
	 * 
	 * @param string $str_pad_string String used to pad the input string
     * @return array Template of P phase of ARC file
	 *
	 */	
	public static function getArcPhasePTemplate($str_pad_string = ' ') {
		return [
            'remark__col14__len2'                   => self::fromFortranFormatToString('A2',   '', $str_pad_string),
            'pick__firstmotion__col16__len1'        => self::fromFortranFormatToString('A1',   '', $str_pad_string),
            'pick__weight__col17__len1'             => self::fromFortranFormatToString('I1',   '', $str_pad_string),
            'seconds_of_P_arrival__col30__len5'     => self::fromFortranFormatToString('F5.2', null, $str_pad_string),
            'phase__residual__col35__len4'          => self::fromFortranFormatToString('F4.2', null, $str_pad_string),
            'phase__weight_out__col39__len3'        => self::fromFortranFormatToString('F3.2', null, $str_pad_string),
        ];
	}
	
	/**
	 * @brief Returns an array template for S phase of ARC file
	 *
	 *
	 * 
	 * @param string $str_pad_string String used to pad the input string
     * @return array Template of S phase of ARC file
	 *
	 */	
	public static function getArcPhaseSTemplate($str_pad_string = ' ') {
		return [
            'seconds_of_S_arrival__col42__len5'     => self::fromFortranFormatToString('F5.2', null, $str_pad_string),
            'remark__col47__len2'                   => self::fromFortranFormatToString('A2',   '', $str_pad_string),
            'blank__col49__len1'                    => self::fromFortranFormatToString('1X',   null, $str_pad_string),
            'pick__weight__col50__len1'             => self::fromFortranFormatToString('I1',   '', $str_pad_string),
            'phase__residual__col51__len4'          => self::fromFortranFormatToString('F4.2', null, $str_pad_string),
        ];
	}
	
	/**
	 * @brief Returns an array template for Amplitude of ARC file
	 *
	 *
	 * 
	 * @param string $str_pad_string String used to pad the input string
     * @return array Template of Amplitude of ARC file
	 *
	 */	
	public static function getArcAmplitudeTemplate($str_pad_string = ' ') {
		return [
            'amplitude__col55__len7'                => self::fromFortranFormatToString('F7.2', null, $str_pad_string),
            'ampUnitsCode__col62__len2'             => self::fromFortranFormatToString('I2',   0, $str_pad_string),
            'fill_gap__col64__len18'                => self::fromFortranFormatToString('18X',  null, $str_pad_string),
            'ampMagWeightCode__col82__len1'         => self::fromFortranFormatToString('I1',   0, $str_pad_string),
            'fill_gap__col83__len15'                => self::fromFortranFormatToString('15X',  null, $str_pad_string),
            'ampMag__col98__len3'                   => self::fromFortranFormatToString('F3.2', null, $str_pad_string),
            'importanceP__col101__len4'             => self::fromFortranFormatToString('F4.3', null, $str_pad_string),
            'importanceS__col105__len4'             => self::fromFortranFormatToString('F4.3', null, $str_pad_string),
            'fill_gap__col109__len5'                => self::fromFortranFormatToString('5X',   null, $str_pad_string),
            'ampType__col114__len2'                 => self::fromFortranFormatToString('A2',  '0', $str_pad_string),
        ];
	}

	/**
	 * @brief Transform a standard hypocenter data, phases data and amplitudes to an array in ARC format
	 *
	 * Questo metodo prende in input un array '$hypocenter' cosi composto:
	   [
        "hypocenter__id"           => 53539041,
        "originTime"           => "2018-01-29 19:02:39.110",
        "latitude"          => 42.9348,
        "longitude"          => 13.1157,
        "depth"        => 10.1,
        "gap"     => 51.0,
        "dmin" => 6.0,
        "rms"          => 0.17,
        "nph"          => 38,
        "e0az"        => 71.0,
        "e0dp"       => 7.0,
        "e0"           => 0.22,
        "e1"           => 0.18,
        "e2"           => 0.17,
        "hypocenter__fk_event"     => 18144331, 
	   ];
	 * 
	 * 
	 * 
	 * e un array '$phases' cosi composto:
	  [
		0 => [
		  "pick__arrival_time" => "2018-01-29 19:02:48.270",
		  "pick__firstmotion" => "U",
		  "pick__emersio" => null,
		  "phase__isc_code" => "P",
		  "phase__ep_distance" => 0.0,
		  "phase__arr_time_is_used" => null,
		  "phase__residual" => -0.15,
		  "pick__weight" => 4,
		  "phase__weight_out" => 0.0,
		  "pick__id" => 350462601,
		  "phase__id" => 938262441,
		  "scnl__net" => "IV",
		  "scnl__sta" => "FOSV",
		  "scnl__cha" => "EHZ",
		  "scnl__loc" => "--",
		],
		1 => [
		  "pick__arrival_time" => "2018-01-29 19:02:52.370",
		  "pick__firstmotion" => null,
		  "pick__emersio" => null,
		  "phase__isc_code" => "P",
		  "phase__ep_distance" => 0.0,
		  "phase__arr_time_is_used" => null,
		  "phase__residual" => -0.48,
		  "pick__weight" => 4,
		  "phase__weight_out" => 0.0,
		  "pick__id" => 350462771,
		  "phase__id" => 938262611,
		  "scnl__net" => "IV",
		  "scnl__sta" => "ATVO",
		  "scnl__cha" => "HHZ",
		  "scnl__loc" => "--",
		]
	  ];
	 * 
	 * 
	 * e un array '$amplitudes' cosi composto:
	  [
		0 => [
		  "amplitude__time1" => "2018-01-29 19:02:43.296",
		  "amplitude__time2" => "2018-01-29 19:02:43.424",
		  "type_amplitude__type" => "WoodAnderson",
		  "amplitude__amp1" => -268.4015,
		  "amplitude__amp2" => 260.8436,
		  "scnl__net" => "IV",
		  "scnl__sta" => "FOSV",
		  "scnl__cha" => "EHZ",
		  "scnl__loc" => "--",
		],
		1 => [
		  "amplitude__time1" => "2018-01-29 19:02:43.494",
		  "amplitude__time2" => "2018-01-29 19:02:43.436",
		  "type_amplitude__type" => "WoodAnderson",
		  "amplitude__amp1" => -100.398,
		  "amplitude__amp2" => 92.20447,
		  "scnl__net" => "IV",
		  "scnl__sta" => "ATVO",
		  "scnl__cha" => "HNN",
		  "scnl__loc" => "--",
		]
	  ];
	 * 
	 * 
	 * e ritorna un array nel formato ARC cosi composto, con numero di colonna e lunghezza:
		[
		  "hypocenter" => [
			"log__hypocenter__id" => 53539041
			"log__hypocenter__fk_event" => 18144331
			"ot_Y__col1_len4" => "2018"
			"ot_mdHi__col5_len8" => "01291902"
			"ot_s__col13_len4" => "3911"
			"lat_deg__col17_len2" => "42"
			"lat_NS__col19_len1" => " "
			"lat_min__col20_len4" => "5609"
			"lon_deg__col24_len3" => " 13"
			"lon_EW__col27_len1" => "E"
			"lon_min__col28_len4" => " 694"
			"depth__col32_len5" => " 1010"
			"magnitude__mag__col37_len3" => "   "
			"nph__col40_len3" => " 38"
			"gap__col43_len3" => " 51"
			"dmin__col46_len3" => "  6"
			"rms__col49_len4" => "  17"
			"e0az__col53_len3" => " 71"
			"e0dp__col56_len2" => " 7"
			"e0__col58_len4" => "  22"
			"e1__col67_len4" => "  18"
			"e2__col77_len4" => "  17"
		  ],
		  "last_line" => [
			"id__col68_len20" => "                                                                   hid=53539041 eid=18144331"
		  ],
		  "scnls" => [
			"IV.ATVO.HHZ.--" => [
			  "remark__col14__len2" => "P "
			  "pick__firstmotion__col16__len1" => " "
			  "pick__weight__col17__len1" => "4"
			  "seconds_of_P_arrival__col30__len5" => " 5237"
			  "phase__residual__col35__len4" => "    "
			  "phase__weight_out__col39__len3" => "   "
			  "seconds_of_S_arrival__col42__len5" => "     "
			  "remark__col47__len2" => "  "
			  "blank__col49__len1" => " "
			  "pick__weight__col50__len1" => " "
			  "phase__residual__col51__len4" => "    "
			  "amplitude__col55__len7" => "       "
			  "ampUnitsCode__col62__len2" => " 0"
			  "ampType__col114__len2" => "                                                   0"
			  "scnl__sta__col1__len5" => "ATVO "
			  "scnl__net__col6__len2" => "IV"
			  "blank__col8__len1" => " "
			  "sta_comp_code__col9__len1" => " "
			  "scnl__cha__col10__len3" => "HHZ"
			  "blank__col13__len1" => " "
			  "pick__arrival_time__Y__col18__len4" => "2018"
			  "pick__arrival_time__mdHi__col22__len8" => "01291902"
			  "log__phase_P__id" => 938262611
			  "log__pick_P__id" => 350462771
			],
			"IV.ATVO.HNN.--" => [ ],
			"IV.FOSV.EHZ.--" => [ ],
		  ]
		]
	 * @param string $str_pad_string String used to pad the input string
     * @return array Template of Amplitude of ARC file
	 *
	 */		
	public static function fromHypocenterPhasesAmplitudesToArcFormat($hypocenter = [], $phases = []) {
        \Log::debug("START - ".__CLASS__.' -> '.__FUNCTION__);

        //
        $str_pad_string = ' ';
        
        /* Check and set hypocenter default params */
        $hypocenterAvailableFields = [
            'originTime', 
            'latitude', 
            'longitude', 
            'depth', 
            'nph', 
            'nphS', 
            'nphtot', 
            'gap', 
            'rms', 
            'e0az', 
            'e0dp', 
            'e0', 
            'e1az', 
            'e1dp', 
            'e1', 
            'e2', 
            'erh', 
            'erz', 
            'dmin', 
            'version', 
            'quakeId'
        ];
        foreach ($hypocenterAvailableFields as $value) {
            $hypocenter[$value] = $hypocenter[$value] ?? null;
        }

        // Set array
        $arrayOutput = [];

        //
        $arrayOutput['hypocenter']['log__hypocenter__id']                   = $hypocenter['hypocenter__id'] ?? '';
        $arrayOutput['hypocenter']['log__hypocenter__fk_event']             = $hypocenter['hypocenter__fk_event'] ?? '';
		$arrayOutput['hypocenter']['log__type_hypocenter__name']			= $hypocenter['type_hypocenter__name'] ?? '';
		$arrayOutput['hypocenter']['log__version']                          = $hypocenter['version'];
        $arrayOutput['hypocenter']['ot_Y__col1_len4']                       = self::fromFortranFormatToString('I4', isset($hypocenter['originTime']) ? \Carbon\Carbon::parse($hypocenter['originTime'])->format('Y') : null, $str_pad_string);
        $arrayOutput['hypocenter']['ot_mdHi__col5_len8']                    = self::fromFortranFormatToString('I8', isset($hypocenter['originTime']) ? \Carbon\Carbon::parse($hypocenter['originTime'])->format('mdHi') : null, $str_pad_string);
        $arrayOutput['hypocenter']['ot_s__col13_len4']                      = self::fromFortranFormatToString('F4.2', isset($hypocenter['originTime']) ? \Carbon\Carbon::parse($hypocenter['originTime'])->format('s.u') : null, $str_pad_string);
        $arrayOutput['hypocenter']['lat_deg__col17_len2']                   = self::fromFortranFormatToString('F2.0', abs(intval($hypocenter['latitude'])), $str_pad_string);
        $arrayOutput['hypocenter']['lat_NS__col19_len1']                    = self::fromFortranFormatToString('A1', ($hypocenter['latitude']>0 ? '' : 'S'), $str_pad_string);
        $arrayOutput['hypocenter']['lat_min__col20_len4']                   = self::fromFortranFormatToString('F4.2', round(($hypocenter['latitude'] - intval($hypocenter['latitude']))*60, 2), $str_pad_string);
        $arrayOutput['hypocenter']['lon_deg__col24_len3']                   = self::fromFortranFormatToString('F3.0', abs(intval($hypocenter['longitude'])), $str_pad_string);
        $arrayOutput['hypocenter']['lon_EW__col27_len1']                    = self::fromFortranFormatToString('A1', ($hypocenter['longitude']>0 ? 'E' : ''), $str_pad_string);
        $arrayOutput['hypocenter']['lon_min__col28_len4']                   = self::fromFortranFormatToString('F4.2', round(($hypocenter['longitude'] - intval($hypocenter['longitude']))*60, 2), $str_pad_string);
        $arrayOutput['hypocenter']['depth__col32_len5']                     = self::fromFortranFormatToString('F5.2', $hypocenter['depth'], $str_pad_string);
        $arrayOutput['hypocenter']['magnitude__mag__col37_len3']            = self::fromFortranFormatToString('F3.2', null, $str_pad_string);
        $arrayOutput['hypocenter']['nph__col40_len3']                       = self::fromFortranFormatToString('I3', $hypocenter['nph'], $str_pad_string);        
        $arrayOutput['hypocenter']['gap__col43_len3']                       = self::fromFortranFormatToString('I3', $hypocenter['gap'], $str_pad_string);        
        $arrayOutput['hypocenter']['dmin__col46_len3']                      = self::fromFortranFormatToString('F3.0', $hypocenter['dmin'], $str_pad_string);
        $arrayOutput['hypocenter']['rms__col49_len4']                       = self::fromFortranFormatToString('F4.2', $hypocenter['rms'], $str_pad_string);
        $arrayOutput['hypocenter']['e0az__col53_len3']                      = self::fromFortranFormatToString('F3.0', $hypocenter['e0az'], $str_pad_string);
        $arrayOutput['hypocenter']['e0dp__col56_len2']                      = self::fromFortranFormatToString('F2.0', $hypocenter['e0dp'], $str_pad_string);
        $arrayOutput['hypocenter']['e0__col58_len4']                        = self::fromFortranFormatToString('F4.2', $hypocenter['e0'], $str_pad_string);
		$arrayOutput['hypocenter']['e1az__col62_len3']                      = self::fromFortranFormatToString('F3.0', $hypocenter['e1az'], $str_pad_string);
		$arrayOutput['hypocenter']['e1dp__col65_len2']                      = self::fromFortranFormatToString('F2.0', $hypocenter['e1dp'], $str_pad_string);
        $arrayOutput['hypocenter']['e1__col67_len4']                        = self::fromFortranFormatToString('F4.2', $hypocenter['e1'], $str_pad_string);
		$arrayOutput['hypocenter']['blank__col71_len6']						= self::fromFortranFormatToString('6X', null, $str_pad_string);
        $arrayOutput['hypocenter']['e2__col77_len4']                        = self::fromFortranFormatToString('F4.2', $hypocenter['e2'], $str_pad_string);
		$arrayOutput['hypocenter']['blank__col81_len5']						= self::fromFortranFormatToString('5X', null, $str_pad_string);
		$arrayOutput['hypocenter']['erh__col86_len4']                       = self::fromFortranFormatToString('F4.2', $hypocenter['erh'], $str_pad_string);
		$arrayOutput['hypocenter']['erz__col90_len4']                       = self::fromFortranFormatToString('F4.2', $hypocenter['erz'], $str_pad_string);
		$arrayOutput['hypocenter']['blank__col94_len25']					= self::fromFortranFormatToString('25X', null, $str_pad_string);
		$arrayOutput['hypocenter']['nphtot__col119_len3']                   = self::fromFortranFormatToString('I3', $hypocenter['nphtot'], $str_pad_string);
		$arrayOutput['hypocenter']['blank__col122_len15']					= self::fromFortranFormatToString('15X', null, $str_pad_string);
		$arrayOutput['hypocenter']['quakeId__col137_len10']                 = self::fromFortranFormatToString('I10', $hypocenter['quakeId'], $str_pad_string);
		$arrayOutput['hypocenter']['blank__col147_len16']					= self::fromFortranFormatToString('16X', null, $str_pad_string);
		$arrayOutput['hypocenter']['version__col164_len1']                  = self::fromFortranFormatToString('A1', $hypocenter['version'], $str_pad_string);

        /* Set last line comment data */
        $arrayOutput['last_line']['id__col68_len20']                        = self::fromFortranFormatToString('A67', '', $str_pad_string).'hid='.$arrayOutput['hypocenter']['log__hypocenter__id'].' eid='.$arrayOutput['hypocenter']['log__hypocenter__fk_event'].' type_hyp='.$arrayOutput['hypocenter']['log__version'].'|"'.$arrayOutput['hypocenter']['log__type_hypocenter__name'].'"';

        /* Set '$arrayOutput['scnls']' as array */
        $arrayOutput['scnls'] = [];
                
        /* Set templates */
        $template_P_phase   = self::getArcPhasePTemplate($str_pad_string);
        $template_S_phase   = self::getArcPhaseSTemplate($str_pad_string);
        $template_amplitude = self::getArcAmplitudeTemplate($str_pad_string);

        //
        foreach ($phases as $phase) {
            $scnl = $phase['net'].'.'.$phase['sta'].'.'.$phase['comp'].'.'.$phase['loc'] ?? '--';

            /* Set default array from templates */
            if (
                    ( isset($phase['Ponset']) && $phase['Ponset'] == "P" )
                    ||
                    ( isset($phase['Sonset']) && $phase['Sonset'] == "S" )
                    ||
                    ( isset($phase['amplitude']) && !empty($phase['amplitude']) )
               ) {
                
                /* Create array element with default value if not already set */
                if (!array_key_exists($scnl, $arrayOutput['scnls'])) {
                    $arrayOutput['scnls'][$scnl]    = $template_P_phase + $template_S_phase + $template_amplitude;
                }

                //
                if ( isset($phase['Pat']) && !empty($phase['Pat']) ) {
                    $arrayOutput['scnls'][$scnl]['pick__arrival_time__Y__col18__len4']      =   self::fromFortranFormatToString('I4', \Carbon\Carbon::parse($phase['Pat'])->format('Y'), $str_pad_string);
                    $arrayOutput['scnls'][$scnl]['pick__arrival_time__mdHi__col22__len8']   =   self::fromFortranFormatToString('I8', \Carbon\Carbon::parse($phase['Pat'])->format('mdHi'), $str_pad_string);
                    $pick__arrival_time__timestamp_without_seconds = strtotime(\Carbon\Carbon::parse($phase['Pat'])->format('Y-m-d H:i').':00');
                } else if ( isset($phase['Sat']) && !empty($phase['Sat']) ) {
                    $arrayOutput['scnls'][$scnl]['pick__arrival_time__Y__col18__len4']      =   self::fromFortranFormatToString('I4', \Carbon\Carbon::parse($phase['Sat'])->format('Y'), $str_pad_string);
                    $arrayOutput['scnls'][$scnl]['pick__arrival_time__mdHi__col22__len8']   =   self::fromFortranFormatToString('I8', \Carbon\Carbon::parse($phase['Sat'])->format('mdHi'), $str_pad_string);
                    $pick__arrival_time__timestamp_without_seconds = strtotime(\Carbon\Carbon::parse($phase['Sat'])->format('Y-m-d H:i').':00');
                } else if ( isset($phase['amplitude']) && !empty($phase['amplitude']) ) {
                    \Log::debug(" search \"amplitude_time\" for \"$scnl\".");

                    // START - search time to use for amplitude
                    $amplitude_time = null;

                    // First; check if is presente an 'amp_time_from_db' key. It should exists when the amp is read from DB.
                    if (is_null($amplitude_time)) {
                        if (array_key_exists('amp_time_from_db', $phase)) {
                            $amplitude_time = $phase['amp_time_from_db'];
                            \Log::debug("  first -> amp_time_from_db: ".$amplitude_time);
                        }
                    }

                    // Second; if no pick time from 'First', search if exists a pick with same StationChannelNetworkLocation; if yes, get the pick time
                    if (isset($phase['Pat'])) {
                        $amplitude_time = $phase['Pat'];
                        \Log::debug("  second -> Pat: ".$amplitude_time);
                    } else if (isset($phase['Sat'])) {
                        $amplitude_time = $phase['Sat'];
                        \Log::debug("  second -> Sat: ".$amplitude_time);
                    }

                    // Third; If no pick time from 'First' and 'Second' steps, search pick time with the same StationNetworkLocation but channel=Z;
                    if (is_null($amplitude_time)) {
                        $sZnl = $phase['net'].'.'.$phase['sta'].'.'.substr($phase['comp'],0,2).'Z.'.$phase['loc'] ?? '--';
                        if (array_key_exists($sZnl, $phases)) {
                            if (isset($phases[$sZnl]['Pat'])) {
                                $amplitude_time = $phases[$sZnl]['Pat'];
                                \Log::debug("  third -> $sZnl -> Pat: ".$amplitude_time);
                            } else if (isset($phases[$sZnl]['Sat'])) {
                                $amplitude_time = $phases[$sZnl]['Sat'];
                                \Log::debug("  third -> $sZnl -> Sat: ".$amplitude_time);
                            }
                        }
                    }

                    // Fourth; If no pick time from 'First', 'Second' and 'Third' steps, get first pick time available.
                    if (is_null($amplitude_time)) {
                        $foundAmpTime=0;
                        foreach ($phases as $phaseToSearchAmpTime) {
                            if ($foundAmpTime == 0) {
                                if ( isset($phaseToSearchAmpTime['Pat']) ) {
                                    $amplitude_time = $phaseToSearchAmpTime['Pat'];
                                    $foundAmpTime=1;
                                    \Log::debug("  fourth -> ".$phaseToSearchAmpTime['net'].'.'.$phaseToSearchAmpTime['sta'].'.'.$phaseToSearchAmpTime['comp'].'.'.$phaseToSearchAmpTime['loc']." -> Pat: ".$amplitude_time);
                                } else if ( isset($phaseToSearchAmpTime['Sat']) ) {
                                    $amplitude_time = $phaseToSearchAmpTime['Sat'];
                                    $foundAmpTime=1;
                                    \Log::debug("  fourth -> ".$phaseToSearchAmpTime['net'].'.'.$phaseToSearchAmpTime['sta'].'.'.$phaseToSearchAmpTime['comp'].'.'.$phaseToSearchAmpTime['loc']." -> Sat: ".$amplitude_time);
                                }
                            }
                        }
                    }

                    // Fifth; If no pick time with same SCNL and no pick time with same StationNetworkLocation and channel=Z, set amplitude time to 'now()'
                    if (is_null($amplitude_time)) {
                        $amplitude_time = date("Y-m-d H:m:s.u");
                        \Log::debug("  fifth -> now(): ".$amplitude_time);
                    }
                    // END - search time to use for amplitude

                    $arrayOutput['scnls'][$scnl]['pick__arrival_time__Y__col18__len4']      =   self::fromFortranFormatToString('I4', \Carbon\Carbon::parse($amplitude_time)->format('Y'), $str_pad_string);
                    $arrayOutput['scnls'][$scnl]['pick__arrival_time__mdHi__col22__len8']   =   self::fromFortranFormatToString('I8', \Carbon\Carbon::parse($amplitude_time)->format('mdHi'), $str_pad_string);
                }

                // 'P', 'S' and 'amplitudes' commons params
                $arrayOutput['scnls'][$scnl]['scnl__sta__col1__len5']                   =   self::fromFortranFormatToString('A5',     $phase['sta'], $str_pad_string, STR_PAD_RIGHT);
                $arrayOutput['scnls'][$scnl]['scnl__net__col6__len2']                   =   self::fromFortranFormatToString('A2',     $phase['net'], $str_pad_string);
                $arrayOutput['scnls'][$scnl]['blank__col8__len1']                       =   self::fromFortranFormatToString('1X',     null, $str_pad_string);
                $arrayOutput['scnls'][$scnl]['sta_comp_code__col9__len1']               =   self::fromFortranFormatToString('A1',     '', $str_pad_string);
                $arrayOutput['scnls'][$scnl]['scnl__cha__col10__len3']                  =   self::fromFortranFormatToString('A3',     $phase['comp'], $str_pad_string);
                $arrayOutput['scnls'][$scnl]['blank__col13__len1']                      =   self::fromFortranFormatToString('1X',     null, $str_pad_string);   
            }
            
            // 'P' phase params
            if ( isset($phase['Ponset']) && $phase['Ponset'] == "P" ) {
                $P_at_YmdHis    = substr($phase['Pat'], 0, 19);
                $P_at_u         = substr($phase['Pat'], 20, 3);
                $P_at_timestamp = strtotime($P_at_YmdHis).'.'.$P_at_u;

                // Second of 'P' arrival from hypocenter OT
                $phase__weight_out                                                      =   self::fromFortranFormatToString('F3.2',   null, $str_pad_string); 

                $arrayOutput['scnls'][$scnl]['remark__col14__len2']                     =   self::fromFortranFormatToString('A2',     substr($phase['Ponset'].$phase['Plabel'], 0, 2), $str_pad_string, STR_PAD_RIGHT); // P=phase, A=amplitude
                $arrayOutput['scnls'][$scnl]['pick__firstmotion__col16__len1']          =   self::fromFortranFormatToString('A1',     $phase['Pfm'], $str_pad_string);
                $arrayOutput['scnls'][$scnl]['pick__weight__col17__len1']               =   self::fromFortranFormatToString('I1',     $phase['Pqual'], $str_pad_string);
                $arrayOutput['scnls'][$scnl]['seconds_of_P_arrival__col30__len5']       =   self::fromFortranFormatToString('F5.2',   ($P_at_timestamp - $pick__arrival_time__timestamp_without_seconds), $str_pad_string);
                $arrayOutput['scnls'][$scnl]['phase__residual__col35__len4']            =   self::fromFortranFormatToString('F4.2',   null, $str_pad_string);

                if (strlen($phase__weight_out) == 3) {
                    $arrayOutput['scnls'][$scnl]['phase__weight_out__col39__len3']      =   $phase__weight_out;
                } else {
                    $arrayOutput['scnls'][$scnl]['log__phase__weight_out']              =   $phase['Pwt'];
                    $arrayOutput['scnls'][$scnl]['phase__weight_out__col39__len3']      =   self::fromFortranFormatToString('I3', '', $str_pad_string);                        
                }

                $arrayOutput['scnls'][$scnl]['log__phase_P__id']                        =   $phase['pick__id'] ?? '';
                $arrayOutput['scnls'][$scnl]['log__pick_P__id']                         =   $phase['phase__id'] ?? '';
            }
            
            // 'S' phase params
            if ( isset($phase['Sonset']) && $phase['Sonset'] == "S" ) {
                $S_at_YmdHis    = substr($phase['Sat'], 0, 19);
                $S_at_u         = substr($phase['Sat'], 20, 3);
                $S_at_timestamp = strtotime($S_at_YmdHis).'.'.$S_at_u;

                // Second of 'S' arrival from hypocenter OT
                $arrayOutput['scnls'][$scnl]['seconds_of_S_arrival__col42__len5']       =   self::fromFortranFormatToString('F5.2',   ($S_at_timestamp - $pick__arrival_time__timestamp_without_seconds), $str_pad_string);
                $arrayOutput['scnls'][$scnl]['remark__col47__len2']                     =   self::fromFortranFormatToString('A2',     $phase['pick__emersio'] ?? ''.'S', $str_pad_string, STR_PAD_RIGHT); // P=phase, A=amplitude
                $arrayOutput['scnls'][$scnl]['blank__col49__len1']                      =   self::fromFortranFormatToString('1X',     null, $str_pad_string);
                $arrayOutput['scnls'][$scnl]['pick__weight__col50__len1']               =   self::fromFortranFormatToString('I1',     $phase['Squal'], $str_pad_string);
                $arrayOutput['scnls'][$scnl]['phase__residual__col51__len4']            =   self::fromFortranFormatToString('F4.2',   null, $str_pad_string);

                $arrayOutput['scnls'][$scnl]['log__phase_S__id']                        =   $phase['phase__id'] ?? '';
                $arrayOutput['scnls'][$scnl]['log__pick_S__id']                         =   $phase['pick__id'] ?? '';
            }
            
            // Amplitude
            if ( isset($phase['amplitude']) && !empty($phase['amplitude']) ) {
                $arrayOutput['scnls'][$scnl]['amplitude__col55__len7']                  =   self::fromFortranFormatToString('F7.2', $phase['amplitude'], $str_pad_string);
                $arrayOutput['scnls'][$scnl]['ampUnitsCode__col62__len2']               =   self::fromFortranFormatToString('I2',   $phase['ampUnitsCode'] ?? 0, $str_pad_string);
                $arrayOutput['scnls'][$scnl]['ampMagWeightCode__col82__len1']           =   self::fromFortranFormatToString('I1',   $phase['ampMagWeightCode'] ?? 0, $str_pad_string);
                $arrayOutput['scnls'][$scnl]['ampMag__col98__len3']                     =   self::fromFortranFormatToString('F3.2', $phase['ampMag'] ?? null, $str_pad_string);
                $arrayOutput['scnls'][$scnl]['importanceP__col101__len4']               =   self::fromFortranFormatToString('F4.3', $phase['importanceP'] ?? null, $str_pad_string);
                $arrayOutput['scnls'][$scnl]['importanceS__col105__len4']               =   self::fromFortranFormatToString('F4.3', $phase['importanceS'] ?? null, $str_pad_string);
                $arrayOutput['scnls'][$scnl]['ampType__col114__len2']                   =   self::fromFortranFormatToString('A2',   $phase['ampType'] ?? 0, $str_pad_string);
            }
        }

        // Order array, by NET.STA.CHA.LOC
        ksort($arrayOutput['scnls']);
        // END - Get SCNLs data

        \Log::debug("END - ".__CLASS__.' -> '.__FUNCTION__);
        
        return $arrayOutput;
	}
    
	public static function fromArrayArcToText($data) {
        \Log::debug("START - ".__CLASS__.' -> '.__FUNCTION__);

        /* format output to text */
        $text = $data['hypocenter']['ot_Y__col1_len4'].
                $data['hypocenter']['ot_mdHi__col5_len8'].
                $data['hypocenter']['ot_s__col13_len4'].
                $data['hypocenter']['lat_deg__col17_len2'].
                $data['hypocenter']['lat_NS__col19_len1'].
                $data['hypocenter']['lat_min__col20_len4'].
                $data['hypocenter']['lon_deg__col24_len3'].
                $data['hypocenter']['lon_EW__col27_len1'].
                $data['hypocenter']['lon_min__col28_len4'].
                $data['hypocenter']['depth__col32_len5'].
                $data['hypocenter']['magnitude__mag__col37_len3'].
                $data['hypocenter']['nph__col40_len3'].
                $data['hypocenter']['gap__col43_len3'].
                $data['hypocenter']['dmin__col46_len3'].
                $data['hypocenter']['rms__col49_len4'].
				$data['hypocenter']['e0az__col53_len3'].
				$data['hypocenter']['e0dp__col56_len2'].
				$data['hypocenter']['e0__col58_len4'].
				$data['hypocenter']['e1az__col62_len3'].
				$data['hypocenter']['e1dp__col65_len2'].
				$data['hypocenter']['e1__col67_len4'].
				$data['hypocenter']['blank__col71_len6'].
				$data['hypocenter']['e2__col77_len4'].
				$data['hypocenter']['blank__col81_len5'].
				$data['hypocenter']['erh__col86_len4'].
				$data['hypocenter']['erz__col90_len4'].
				$data['hypocenter']['blank__col94_len25'].
				$data['hypocenter']['nphtot__col119_len3'].
				$data['hypocenter']['blank__col122_len15'].
				$data['hypocenter']['quakeId__col137_len10'].
				$data['hypocenter']['blank__col147_len16'].
				$data['hypocenter']['version__col164_len1'];
        
        $text .= "\n";
        $text .= '$';
        $text .= "\n";
       
        $countPhases=1;
        foreach ($data['scnls'] as $scnl => $value) {
            //\Log::debug(" scnl:".$value['scnl__sta__col1__len5'].'.'.$value['scnl__cha__col10__len3']);
            $text .= $value['scnl__sta__col1__len5'].
                     $value['scnl__net__col6__len2'].
                     $value['blank__col8__len1'].
                     $value['sta_comp_code__col9__len1'].
                     $value['scnl__cha__col10__len3'].
                     $value['blank__col13__len1'].
                     $value['remark__col14__len2'].
                     $value['pick__firstmotion__col16__len1'].
                     $value['pick__weight__col17__len1'].
                     $value['pick__arrival_time__Y__col18__len4'].
                     $value['pick__arrival_time__mdHi__col22__len8'].
                     $value['seconds_of_P_arrival__col30__len5']. 
                     $value['phase__residual__col35__len4'].
                     $value['phase__weight_out__col39__len3'].
                     $value['seconds_of_S_arrival__col42__len5']. 
                     $value['remark__col47__len2'].
                     $value['blank__col49__len1'].
                     $value['pick__weight__col50__len1'].
                     $value['phase__residual__col51__len4'].
                     $value['amplitude__col55__len7'].
                     $value['ampUnitsCode__col62__len2'].
                     $value['fill_gap__col64__len18'].
                     $value['ampMagWeightCode__col82__len1'].
                     $value['fill_gap__col83__len15'].
                     $value['ampMag__col98__len3'].
                     $value['importanceP__col101__len4'].
                     $value['importanceS__col105__len4'].
                     $value['fill_gap__col109__len5'].
                     $value['ampType__col114__len2'] //Colonna 114, Format I2 - Amplitude type 0=unspecified 1=Wood-Anderson 2=velocity 3=acceleration 4=no magnitude.
                    ;            
            
            if ( $countPhases < count($data['scnls']) ) {
                $text .= "\n";
                $text .= '$';
                $text .= "\n";
            }
            $countPhases++;
        }
        
        /* Set last line with commen */
        $text .= "\n";
        $text .= '$';
        $text .= "\n";
        $text .= $data['last_line']['id__col68_len20'];
		$text .= "\n";
        
        \Log::debug("END - ".__CLASS__.' -> '.__FUNCTION__);
        
        return $text;
    }
}