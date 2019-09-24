<?php
namespace App\Api\Traits;

use Illuminate\Support\Facades\Validator;
 
trait EarthwormTrait {

	/**
     * @brief Validate a single phase fields from 'ewMessage'
     * 
     * Validate a single phase fields from 'ewMessage' generated from 'ew2openapi' output
	 * 
	 * @param type $phase
	 */
	public static function validateHyp2000ArcEwMessagePhase($phase) {
		\Log::debug("START - ".__CLASS__.' -> '.__FUNCTION__);
		
		// START - Validator
		$validator_default_check    = config('hyp2000-ws.validator_default_check');
		$validator_default_message  = config('hyp2000-ws.validator_default_messages');				
		$validator = Validator::make($phase, [
			'sta'                   => 'required|'.$validator_default_check['sta'],
			'net'                   => $validator_default_check['net'],
			'comp'                  => $validator_default_check['cha'],
			'loc'                   => $validator_default_check['loc'],
			'Plabel'                => 'string|nullable',
			'Slabel'                => 'string|nullable',
			'Ponset'                => 'string|nullable',
			'Sonset'                => 'string|nullable',
			'Pres'                  => 'numeric|nullable',
			'Sres'                  => 'numeric|nullable',
			'Pqual'                 => 'integer|nullable|in:0,1,2,3,4,9', // '9' is not a valid value but sometimes it is used from EW when 'Pqual' is 'null'; this will be filtered in the 'IngvNTEwController.hyp2000arc()' method.
			'Squal'                 => 'integer|nullable|in:0,1,2,3,4,9', // '9' is not a valid value but sometimes it is used from EW when 'Pqual' is 'null'; this will be filtered in the 'IngvNTEwController.hyp2000arc()' method.
			'codalen'               => 'integer|nullable',
			'codawt'                => 'integer|nullable',
			'Pfm'                   => 'string|size:1|nullable',
			'Sfm'                   => 'string|size:1|nullable',
			'datasrc'               => 'string|nullable',            
			'Md'                    => 'numeric|nullable',            
			'azm'                   => 'numeric|nullable',
			'takeoff'               => 'integer|nullable',
			'dist'                  => $validator_default_check['distance'],
			'Pwt'                   => 'numeric|nullable',
			'Swt'                   => 'numeric|nullable',
			'pamp'                  => 'integer|nullable',
			'codalenObs'            => 'integer|nullable',
            'amplitude'             => 'numeric|nullable',
            'ampUnitsCode'          => 'integer|nullable',
            'ampType'               => 'integer|nullable',
            'ampMag'                => 'numeric|nullable',
            'ampMagWeightCode'      => 'integer|nullable',
            'importanceP'           => 'numeric|nullable',
            'importanceS'           => 'numeric|nullable',
		], $validator_default_message);
        
        /* Check 'P' fileds only when 'Ponset' is set */
        $validator->sometimes('Pat', $validator_default_check['data_time_with_msec'], function($input) {
            return isset($input->Ponset);
        });
        
        /* Check 'S' fileds only when 'Sonset' is set */
        $validator->sometimes('Sat', $validator_default_check['data_time_with_msec'], function($input) {
            return isset($input->Sonset);
        });
        
        $validator->validate();
        
		// END - Validator
		\Log::debug("END - ".__CLASS__.' -> '.__FUNCTION__);
	} 
}