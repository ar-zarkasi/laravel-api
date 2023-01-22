<?php namespace App\Traits;

trait ReturnServices {
    
    /* const STATUS_SUCCESS = 200;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_SERVER_FAIL = 500;
    const STATUS_CREATED = 201;
    const STATUS_ERROR = 400;
    const STATUS_FAIL_VALIDATION = 422; */


    /**
     * Send any success response
     * 
     * @param   string          $message
     * @param   array|object    $data
     * @param   integer         $statusCode
     */
    public function response($data = [], $message = null, $error = false, $code = 200)
    {
        return [
            'error' => $error,
            'message' => $message,
            'data' => $data,
            'code' => $code
        ];
    }

    public function errorValidation($validator)
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
        return $errStr;
    }

}