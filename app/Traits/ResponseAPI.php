<?php namespace App\Traits;

trait ResponseAPI {
    /**
     * Core of response
     * 
     * @param   string          $message
     * @param   array|object    $data
     * @param   integer         $statusCode  
     * @param   boolean         $isSuccess
     */
    public function coreResponse($message, $data = null, $statusCode, $isSuccess = true)
    {
        // Check the params
        if(!$message) return response()->json(['message' => 'Message is required'], 500);

        // Send the response
        return response()->json([
            'code' => $statusCode,
            'data' => $data,
            'message' => $message,
            'error' => !$isSuccess,
        ], $statusCode);
    }

    /**
     * Send any success response
     * 
     * @param   string          $message
     * @param   array|object    $data
     * @param   integer         $statusCode
     */
    public function success($message, $data, $statusCode = 200)
    {
        return $this->coreResponse($message, $data, $statusCode);
    }

    /**
     * Send any error response
     * 
     * @param   string          $message
     * @param   integer         $statusCode    
     */
    public function error($message, $data = [], $statusCode = 500)
    {
        return $this->coreResponse($message, $data, $statusCode, false);
    }

    public function errorValidation($validator, $data = [])
    {
        $errStr ='';
        foreach ($validator->errors()->getMessages() as $key => $errorField) {
            $errStr .= $key.' : ';
            $start = 0;
            foreach ($errorField as $errorMessage) {
                if($start++!=0)
                    $errStr .= " | ";    
                $errStr .= $errorMessage;
            }

            $errStr .= "\n";
            
        }
        return $this->coreResponse($errStr, $data, 422, false);
    }
}