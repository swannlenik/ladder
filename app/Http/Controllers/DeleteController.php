<?php

declare(strict_types=1);


namespace App\Http\Controllers;


use Illuminate\Http\Request;

class DeleteController extends Controller {
    
    public function confirm(Request $request) {
        $previousParamName = $request->get('previousParamName') ?? '';
        $previousParamValue = $request->get('previousParamValue') ?? '';
        $deleteParamName = $request->get('deleteParamName') ?? '';
        $deleteParamValue = $request->get('deleteParamValue') ?? '';
        
        if (empty($previousParamName) || empty($previousParamValue)) {
            $previous = route($request->get('previousRoute'));
        } else {
            $previous = route($request->get('previousRoute'), [$request->get('previousParamName') => $request->get('previousParamValue')]);    
        }
        
        if (empty($deleteParamName) || empty($deleteParamValue)) {
            $route = route($request->get('deleteRoute'));
        } else {
            $route = route($request->get('deleteRoute'), [$request->get('deleteParamName') => $request->get('deleteParamValue')]);
        }
        
        return view('delete/confirm', [
            'route' => $route,
            'previous' => $previous,
        ]);
    }
}
