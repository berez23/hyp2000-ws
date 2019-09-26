<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use App\Api\v1\Controllers\Hyp2000StationsController;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Api\Traits\UtilsTrait;
use App\Api\Traits\EarthwormTrait;
use App\Api\Traits\ArcFileTrait;

class Hyp2000Controller extends Controller
{
    use UtilsTrait, EarthwormTrait, ArcFileTrait;

	protected $default_output = [
		"prt"
    ];

	protected $default_model = [
		"Italy",
		"5.00  0.00",
		"6.00 10.00",
		"8.10 30.00"
    ];

	protected $default_hyp2000_conf = [
		"200 T 2000 0",
		"LET 5 2 3 2 2",
		"H71 1 1 3",
		"STA './all_stations.hinv'",
		"CRH 1 './italy.crh'",
		"MAG 1 T 3 1",
		"DUR -.81 2.22 0 .0011 0, 5*0, 9999 1",
		"FC1 'D' 2 'HHZ' 'EHZ'",
		"PRE 7, 3 0 4 9, 5 6 4 9, 1 1 0 9, 2 1 0 9, 4 4 4 9, 3 0 0 9, 4 0 0 9",
		"RMS 4 .40 2 4",
		"ERR .10",
		"POS 1.78",
		"REP T T",
		"JUN T",
		"MIN 4",
		"NET 4",
		"ZTR 5 T",
		"DIS 6 100 1. 7.",
		"DAM 7 30 0.5 0.9 0.005 0.02 0.6 100 500",
		"WET 1. .75 .5 .25",
		"ERF T",
		"TOP F",
		"LST 1 1 0",
		"KPR 2",
		"COP 5",
		"CAR 3",
		"PRT '../output/hypo.prt'",
		"SUM '../output/hypo.sum'",
		"ARC '../output/hypo.arc'",
		"APP F T F",
		"CON 25 0.04 0.001",
		"PHS './input.arc'",
		"LOC"
    ];

    /**
     * @brief Get a JSON picks file, run hyp2000 and return location
     *
     * An example of JSON could be:
	 *
	{
	  "data" : {
		"ewMessage" : {
		  "Md" : 0,
		  "reg" : null,
		  "e1" : 2.5099999999999998,
		  "depth" : 39.439999999999998,
		  "gap" : 177,
		  "e0az" : 75,
		  "e2" : 0.65000000000000002,
		  "dmin" : 37,
		  "erz" : 2.2200000000000002,
		  "mdtype" : "D",
		  "erh" : 2.4399999999999999,
		  "mdmad" : 0,
		  "e1az" : 255,
		  "latitude" : 42.331333000000001,
		  "ingvQuality" : "DD",
		  "version" : "ew prelim",
		  "nph" : 8,
		  "originTime" : "2018-04-04 14:01:08.510000",
		  "Mpref" : 0,
		  "quakeId" : 47863,
		  "longitude" : 13.479333,
		  "e0dp" : 42,
		  "nphS" : 2,
		  "nphtot" : 8,
		  "labelpref" : null,
		  "wtpref" : 0,
		  "phases" : [
			{
			  "Md" : 0,
			  "Pat" : "2018-04-04 14:02:39.989998",
			  "Slabel" : null,
			  "caav" : [
				0,
				0,
				0,
				0,
				0,
				0
			  ],
			  "Pqual" : 4,
			  "datasrc" : "W",
			  "Ponset" : null,
			  "Pwt" : 0,
			  "Sfm" : null,
			  "Pres" : 82.659999999999997,
			  "codawt" : 4,
			  "sta" : "T1299",
			  "azm" : 335,
			  "Sonset" : "S",
			  "net" : "IV",
			  "takeoff" : 122,
			  "Pfm" : null,
			  "dist" : 37.299999999999997,
			  "ccntr" : [
				0,
				0,
				0,
				0,
				0,
				0
			  ],
			  "comp" : "EHZ",
			  "Sat" : "2018-04-04 14:01:24.410000",
			  "pamp" : 35,
			  "Plabel" : null,
			  "loc" : "--",
			  "Squal" : 3,
			  "codalen" : 0,
			  "Swt" : 0.56000000000000005,
			  "Sres" : 0.20000000000000001,
			  "codalenObs" : 0
			}
		  ],
		  "e0" : 3.2999999999999998,
		  "mdwt" : 0,
		  "e1dp" : 47,
		  "rms" : 0.14999999999999999,
		  "nPfm" : 2
		}
	  }
	}
     *
     * @param string json input picks
     * @return string json location
     */
    public function query(Request $request)
    {
        \Log::debug("START - ".__CLASS__.' -> '.__FUNCTION__); 
        
        $input_parameters = $request->only('data');

        /* Validator */
        $validator_default_check    = config('hyp2000-ws.validator_default_check');
        $validator_default_message  = config('hyp2000-ws.validator_default_messages');
        $validator = Validator::make($input_parameters, [
			'data'			=> 'required',
        ], $validator_default_message)->validate();
        
		/****** START - HYP2000ARC ******/
        /* Get 'ewMessage' from "TYPE_ARC" */
        $arcMessage = $input_parameters['data']['TYPE_HYP2000ARC'];

        /* START - Validate '$ewMessage' */
        $validator_default_check    = config('hyp2000-ws.validator_default_check');
        $validator_default_message  = config('hyp2000-ws.validator_default_messages');
        $validator = Validator::make($arcMessage, [
            'quakeId'       => 'integer|required',
            'version'       => 'required',
        ], $validator_default_message)->validate();

		// Convert Earthworm version (that is string) to ARC version (that is 'A1' format)
		if ( (isset($arcMessage['version'])) && !empty($arcMessage['version']) ) {
			$type_hypocenter__name = $arcMessage['version'];
			switch ($type_hypocenter__name) {
				case 'ew prelim':
					$version_from_ew_to_arc = 0;
					break;
				case 'ew rapid':
					$version_from_ew_to_arc = 1;
					break;
				case 'ew final':
					$version_from_ew_to_arc = 2;
					break;
				default:
					$version_from_ew_to_arc = 4;
			}
		} else {
			$version_from_ew_to_arc = 4;
		}
		$arcMessage['version']             = $version_from_ew_to_arc;

        /* START - Validate phases */
        if ( (isset($arcMessage['phases'])) && !empty($arcMessage['phases']) ) {
            foreach ($arcMessage['phases'] as $arcMessagePhase) {
                /* Validate 'phase' */
                $this->validateHyp2000ArcEwMessagePhase($arcMessagePhase);
            }
        }
        /* END - Validate phases */
		/****** END - HYP2000ARC ******/

		/* Get Array ARC */
		$data = $this->fromHypocenterPhasesAmplitudesToArcFormat($arcMessage, $arcMessage['phases'], []);

        /* Build array used to retrieve 'all_stations.hinv' files */
        $arrayHyp2000Stations = [];
        foreach ($data['scnls'] as $key => $value) {
            //\Log::debug(" scnl:".$key);
            $arrayHyp2000Stations[] = str_replace('.','|',$key).'|'.$value['pick__arrival_time__Y__col18__len4'].'-'.substr($value['pick__arrival_time__mdHi__col22__len8'], 0,2).'-'.substr($value['pick__arrival_time__mdHi__col22__len8'], 2,2);
        }

		/* Transform Array ARC to ARC file */
		$text = $this->fromArrayArcToText($data);

		/****** START - HYP2000_CONF ******/
		if ( (isset($input_parameters['data']['HYP2000_CONF'])) && !empty($input_parameters['data']['HYP2000_CONF']) ) {
			$hyp2000Conf = $input_parameters['data']['HYP2000_CONF'];
		} else {
			$hyp2000Conf = $this->default_hyp2000_conf;
		}
		/****** END - HYP2000_CONF ******/

		/****** START - MODEL ******/
		if ( (isset($input_parameters['data']['MODEL'])) && !empty($input_parameters['data']['MODEL']) ) {
			$model = $input_parameters['data']['MODEL'];
		} else {
			$model = $this->default_model;
		}
		/****** END - MODEL ******/

		/****** START - OUTPUT ******/
		if ( (isset($input_parameters['data']['OUTPUT'])) && !empty($input_parameters['data']['OUTPUT']) ) {
			$output_format = $input_parameters['data']['OUTPUT'][0];
			switch ($output_format) {
				case 'prt':
				case 'sum':
				case 'arc':
				case 'json':
					break;
				default:
					$output_format = $this->default_output[0];
			}
		} else {
			$output_format = $this->default_output[0];
		}
		/****** END - OUTPUT ******/

		/* Set argument for arc file */
		$now							= \DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
		$nowFormatted					= $now->format("Ymd_His");
		/* */
		$dir_working					= "/hyp2000/".$nowFormatted."__".gethostbyaddr(\request()->ip())."__".\Illuminate\Support\Str::random(5);
		$dir_data						= config('filesystems.disks.data.root');
		$dir_input						= "input";
		$dir_output						= "output";
		/* */
		$file_input_arc					= "input.arc";
		$file_input_conf				= "italy2000.hyp";
		$file_input_model				= "italy.crh";
		$file_input_stations			= "all_stations.hinv";
		$file_output_prt				= "hypo.prt";
		$file_output_sum				= "hypo.sum";
		$file_output_arc				= "hypo.arc";
		$file_output_json				= "hypo.json"; // generated from ew2openapi
		$file_output_log				= "output.log";
        $file_output_err				= "output.err";
		/* */
		$file_input_fullpath_arc		= $dir_working."/".$dir_input."/".$file_input_arc;
		$file_input_fullpath_conf		= $dir_working."/".$dir_input."/".$file_input_conf;
		$file_input_fullpath_model		= $dir_working."/".$dir_input."/".$file_input_model;
		$file_input_fullpath_stations	= $dir_working."/".$dir_input."/".$file_input_stations;
		$file_output_fullpath_log		= $dir_working."/".$dir_output."/".$file_output_log;
        $file_output_fullpath_err		= $dir_working."/".$dir_output."/".$file_output_err;

		/* Write ARC file on disk */
		Storage::disk('data')->put($file_input_fullpath_arc, $text);

		/* Write hyp2000 conf file on disk */
		$textHyp2000Conf = '';
		foreach ($hyp2000Conf as $line) {
			$skip = 0;
			if (strpos($line, 'STA') !== false) {
				$skip = 1;
			}
			if (strpos($line, 'CRH') !== false) {
				$skip = 1;
			}
			if (strpos($line, 'PRT') !== false) {
				$skip = 1;
			}
			if (strpos($line, 'SUM') !== false) {
				$skip = 1;
			}
			if (strpos($line, 'ARC') !== false) {
				$skip = 1;
			}
			if (strpos($line, 'PHS') !== false) {
				$skip = 1;
			}
			if (strpos($line, 'LOC') !== false) {
				$skip = 1;
			}

			if ($skip == 0) {
				$textHyp2000Conf .= $line." \n";
			}
		}
		$textHyp2000Conf .= "STA './".$file_input_stations."' \n";
		$textHyp2000Conf .= "CRH 1 './".$file_input_model."' \n";
		$textHyp2000Conf .= "PRT '../".$dir_output."/".$file_output_prt."' \n";
		$textHyp2000Conf .= "SUM '../".$dir_output."/".$file_output_sum."' \n";
		$textHyp2000Conf .= "ARC '../".$dir_output."/".$file_output_arc."' \n";
		$textHyp2000Conf .= "PHS './".$file_input_arc."' \n";
		$textHyp2000Conf .= "LOC \n";
		Storage::disk('data')->put($file_input_fullpath_conf, $textHyp2000Conf);

		/* Write hyp2000 model file on disk */
		$textModel = '';
		foreach ($model as $line) {
			$textModel .= $line."\n";
		}
		Storage::disk('data')->put($file_input_fullpath_model, $textModel);

		/* Build 'all_stations.hinv' file */
		$textHyp2000Stations = '';
		$Hyp2000StationsController = new Hyp2000StationsController;

        /* Number of stations */
        $n_hyp2000Sation = count($arrayHyp2000Stations);

        $count = 1;
		foreach ($arrayHyp2000Stations as $value) {
            $arrayValue = explode("|", $value);

            $net        = $arrayValue[0];
            $sta        = $arrayValue[1];
            $cha        = $arrayValue[2];
            $loc        = empty($arrayValue[3]) ? '--' : $arrayValue[3];
            $starttime  = $arrayValue[4].'T00:00:00';
            $endtime    = $arrayValue[4].'T23:59:59';

            \Log::debug($count."/".$n_hyp2000Sation." - Searching: ".$net.".".$sta.".".$loc.".".$cha);
			$arrayValue = explode("|", $value);
			$stationLine = $Hyp2000StationsController->query(new Request([
				'net'		=> $net,
				'sta'		=> $sta,
				'cha'		=> $cha,
                'loc'		=> $loc,
				'starttime' => $starttime,
				'endtime'	=> $endtime,
				'cache'		=> 'true'
				]));
			$textHyp2000Stations .= $stationLine->content();
            $count++;
		}

		/* Write 'all_stations.hinv' file on disk */
		Storage::disk('data')->put($file_input_fullpath_stations, $textHyp2000Stations);

		/* Copy stations file and create output dir */
		Storage::disk('data')->makeDirectory($dir_working."/".$dir_output."");

		/* Set command for run process */
		$command =
			array_merge(
				[
                    'hostname'
				]
			);

        /* Run process */
        \Log::debug(" Running command: ", $command);
        $command_timeout = 120;
        $command_process = new Process($command);
        $command_process->setTimeout($command_timeout);
        $command_process->run();
        \Log::debug(" getOutput:".$command_process->getOutput());
        \Log::debug(" getErrorOutput:".$command_process->getErrorOutput());
        if (!$command_process->isSuccessful()) {
            throw new ProcessFailedException($command_process);
        }
        \Log::debug(" Done.");
        
		/* Set command for run process */
		$command =
			array_merge(
				[
                    'docker',
                    'run',
                    '--rm',
                    '-v', config('filesystems.disks.data.root').$dir_working.":/opt/data",
                    config('hyp2000-ws.docker_hyp2000'),
                    $file_input_conf
				]
			);

        /* Run process */
        \Log::debug(" Running docker: ", $command);
        $command_timeout = 120;
        $command_process = new Process($command);
        $command_process->setTimeout($command_timeout);
        $command_process->run();
        \Log::debug(" getOutput:".$command_process->getOutput());
        \Log::debug(" getErrorOutput:".$command_process->getErrorOutput());
        if (!$command_process->isSuccessful()) {
            throw new ProcessFailedException($command_process);
        }
        \Log::debug(" Done.");

        /* Write warnings and errors into log file */
        \Log::debug(" Write warnings and errors into \"$file_output_fullpath_err\"");
        Storage::disk('data')->put($file_output_fullpath_err, $command_process->getErrorOutput());

        /* Write standard output messages into log file */
        \Log::debug(" Write standard output messages into \"$file_output_fullpath_log\"");
        Storage::disk('data')->put($file_output_fullpath_log, $command_process->getOutput());

        /* Get output to return */
        \Log::debug(" Get output to return");
        $contents = Storage::disk('data')->get($dir_working."/".$dir_output."/".${"file_output_".$output_format});

        \Log::debug("END - ".__CLASS__.' -> '.__FUNCTION__);
        if ( $output_format == 'json' ) {
            //$contents = date_format(date_create($arcMessage['originTime']), 'Y-m-d');
            //$contents .= "\n\n";
            return response()->json(json_decode($contents, true), 200, [], JSON_PRETTY_PRINT);
        } else {
            /* set headers */
            $headers['Content-type'] = 'text/plain';
            return response()->make($contents, 200, $headers);
        }
    }
}
