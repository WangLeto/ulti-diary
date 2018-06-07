<?php

namespace App\Http\Controllers;

use App\Support\Response;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Contracts\Validation\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $response;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->response = new Response(response());
    }

    /**
     * Format the validation errors to be returned.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return array
     */
    protected function formatValidationErrors(Validator $validator)
    {
        $message = '';
        foreach ($validator->errors()->getMessages() as $arr) {
            foreach ($arr as $value) {
                $message .= $value.',';
            }
        }
        return [ 'message' => rtrim($message, ',') ];
    }
}
