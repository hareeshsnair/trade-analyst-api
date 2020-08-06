<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

trait ExceptionTrait
{
    protected $code;

    public function apiException($request, $exception)
    {
        if ($this->isValidation($exception)) {

            $this->code = 422;
            return $this->response($exception->errors());
        } 
        elseif ($this->isAuthentication($exception)) {

            return $this->response('Please login to continue');
        } 
        elseif ($this->isAuthorization($exception)) {

            return $this->response('Unauthorized request');
        } 
        elseif ($this->isNotFoundHttp($exception)) {

            return $this->response('Request not found');
        }
        elseif ($this->isMethodNotAllowed($exception)) {

            return $this->response('Method not allowed');
        }
        else {

            $msg = (app()->environment() !== 'production')
                    ? $exception->getMessage()
                    : 'Something went wrong!';
            
            if($exception->getCode() && gettype($exception->getCode()) === 'integer')
                $this->code = $exception->getCode();
            
            return $this->response($msg);
        }
    }

    public function setErrorCode($code)
    {
        $this->code = $code;
    }

    public function getErrorCode()
    {
        return $this->code;
    }

    protected function isValidation($e) 
    {
        return $e instanceof ValidationException;
    }

    protected function isAuthorization($e) 
    {
        return $e instanceof AuthorizationException;
    }

    protected function isAuthentication($e) 
    {
        return $e instanceof AuthenticationException;
    }

    protected function isNotFoundHttp($e) 
    {
        return $e instanceof NotFoundHttpException;
    }

    protected function isMethodNotAllowed($e)
    {
        return $e instanceof MethodNotAllowedHttpException;
    }

    private function response($message)
    {
        if(gettype($message) === 'array')
            foreach($message as $key => $value){
                $message[$key] = $value[0];
            };
            
        return $message;
        // [
        //     'message' => $message,
        //     'status_code' => $this->code
        // ];
    }
}