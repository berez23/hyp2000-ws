<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use App\Api\Rules\StartOrEndDateRule;
use App\Api\Traits\UtilsTrait;
use App\Api\Models\Hyp2000StationsModel;

class Hyp2000StationsController extends Controller
{
    use UtilsTrait;
    
	public function query(Request $request) {
        \Log::debug("START - ".__CLASS__.' -> '.__FUNCTION__);

        /* Log all input params */
        $this->logAllRequestParameters($request);
		
        /* Get specific parameters for this controller */        
        $specific_parameters = [
            'net',
			'sta',
			'cha',
			'loc',
			'starttime',
			'endtime',
			'cache'
        ];

        /* From GET, process only '$parameters_permitted' */
		$requestOnly = $request->only(
            $specific_parameters
        );
        
        /* Validator */
        $validator_default_check    = config('hyp2000-ws.validator_default_check');
        $validator_default_message  = config('hyp2000-ws.validator_default_messages');
        $validator = Validator::make($requestOnly, [
			'net'			=> $validator_default_check['net'].'|required',
			'sta'			=> $validator_default_check['sta'].'|required',
			'cha'			=> $validator_default_check['cha'].'|required',
			'loc'			=> $validator_default_check['loc'],
			'starttime'		=> [new StartOrEndDateRule],
			'endtime'		=> [new StartOrEndDateRule],
			'cache'			=> 'in:true,false',
        ], $validator_default_message)->validate();

        /* Log only selected params */
        $this->logSelectedParameters($requestOnly);
		
		/* Get data */
		$data = Hyp2000StationsModel::getData($requestOnly, 2880);

        /* set headers */
        $headers['Content-type'] = 'text/plain';
        
        \Log::debug("END - ".__CLASS__.' -> '.__FUNCTION__);
		return response()->make($data, 200, $headers);
	}
}