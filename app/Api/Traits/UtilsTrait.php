<?php
namespace App\Api\Traits;
 
trait UtilsTrait {

    public function logAllRequestParameters($request) {
        \Log::debug("START - ".__CLASS__.' -> '.__FUNCTION__);

        foreach ($request->all() as $request_key => $request_val) {
            if (is_bool($request_val)) {
                $request_val = $request_val ? 'true' : 'false';
            }
            \Log::debug(' '.$request_key.'='.$request_val);            
        }

        \Log::debug("END - ".__CLASS__.' -> '.__FUNCTION__);
    }
    
    public function logSelectedParameters($input_parameters) {
        \Log::debug("START - ".__CLASS__.' -> '.__FUNCTION__);

        foreach ($input_parameters as $input_parameter_key => $input_parameter_val) {          
            if (is_bool($input_parameter_val)) {
                $input_parameter_val = $input_parameter_val ? 'true' : 'false';
            }            
            \Log::debug(' '.$input_parameter_key.'='.$input_parameter_val);            
        }

        \Log::debug("END - ".__CLASS__.' -> '.__FUNCTION__);
    } 
}