<?php

namespace App\Api\Middleware;

use Closure;

class UniformInputParametersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Log::debug("START - ".__CLASS__.' -> '.__FUNCTION__);
        $inputParams = array_change_key_case($request->all(), CASE_LOWER);

        foreach ($inputParams as $inputParamsKey => $inputParamsValue) {
            switch ($inputParamsKey) {
                case 'catalog':
                    \Log::debug(" uniform: ".$inputParamsKey. ' -> catalog');
                    $request->merge(['catalog' => $inputParamsValue]);
                    unset($request[$inputParamsKey]);
                    break;
                case 'eventid':
                    \Log::debug(" uniform: ".$inputParamsKey. ' -> eventid');
                    $request->merge(['eventid' => $inputParamsValue]);
                    unset($request[$inputParamsKey]);
                    break;
                case 'start':
                    \Log::debug(" uniform: ".$inputParamsKey. ' -> starttime');
                    $request->merge(['starttime' => $inputParamsValue]);
                    unset($request[$inputParamsKey]);
                    break;
                case 'end':
                    \Log::debug(" uniform: ".$inputParamsKey. ' -> endtime');
                    $request->merge(['endtime' => $inputParamsValue]);
                    unset($request[$inputParamsKey]);
                    break;
                case 'minmagnitude':
                    \Log::debug(" uniform: ".$inputParamsKey. ' -> minmag');
                    $request->merge(['minmag' => $inputParamsValue]);
                    unset($request[$inputParamsKey]);
                    break;
                case 'maxmagnitude':
                    \Log::debug(" uniform: ".$inputParamsKey. ' -> maxmag');
                    $request->merge(['maxmag' => $inputParamsValue]);
                    unset($request[$inputParamsKey]);
                    break;
                case 'maxlatitude':
                    \Log::debug(" uniform: ".$inputParamsKey. ' -> maxlat');
                    $request->merge(['maxlat' => $inputParamsValue]);
                    unset($request[$inputParamsKey]);
                    break;
                case 'minlatitude':
                    \Log::debug(" uniform: ".$inputParamsKey. ' -> minlat');
                    $request->merge(['minlat' => $inputParamsValue]);
                    unset($request[$inputParamsKey]);
                    break;
                case 'maxlongitude':
                    \Log::debug(" uniform: ".$inputParamsKey. ' -> maxlon');
                    $request->merge(['maxlon' => $inputParamsValue]);
                    unset($request[$inputParamsKey]);
                    break;
                case 'minlongitude':
                    \Log::debug(" uniform: ".$inputParamsKey. ' -> minlon');
                    $request->merge(['minlon' => $inputParamsValue]);
                    unset($request[$inputParamsKey]);
                    break;
                case 'longitude':
                    \Log::debug(" uniform: ".$inputParamsKey. ' -> lon');
                    $request->merge(['lon' => $inputParamsValue]);
                    unset($request[$inputParamsKey]);
                    break;
                case 'latitude':
                    \Log::debug(" uniform: ".$inputParamsKey. ' -> lat');
                    $request->merge(['lat' => $inputParamsValue]);
                    unset($request[$inputParamsKey]);
                    break;
                case 'formatted':
                case 'includegeometry':
                    $formatted = filter_var($inputParamsValue, FILTER_VALIDATE_BOOLEAN);
                    \Log::debug(" uniform: $inputParamsKey ($inputParamsValue) -> $inputParamsKey ($formatted)");
                    $request->merge([$inputParamsKey => filter_var($inputParamsValue, FILTER_VALIDATE_BOOLEAN)]);
                    unset($request[$inputParamsKey]);
                    break;
            }
        }

        \Log::debug("END - ".__CLASS__.' -> '.__FUNCTION__);
        return $next($request);
    }
}
