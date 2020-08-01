<?php


namespace System\Http\Validation;


use Ds\Vector;
use System\Exceptions\Http\BadRequestException;
use System\Exceptions\Http\UnsupportedMediaTypeException;
use System\Http\Dto\ValidationResult;
use System\Http\Requests\AppRequest;
use System\Http\Requests\BasicRequest;
use System\Http\Validation\Rules\AbstractRule;

class ValidationProvider
{
    public function validateCustomRequest(AppRequest $request)
    {
        $basicRequest = $request->getBasicRequest();
        $this->validateMediaType($request->isJson(), $basicRequest);
        $this->validateCustomRules($request);
    }

    private function validateMediaType(bool $isJson, BasicRequest $basicRequest)
    {
        if (!$isJson) {
            return false;
        }
        $headerKey = 'Content-Type';
        if ($basicRequest->hasHeader($headerKey)) {
            if ($basicRequest->getHeader($headerKey) !== 'application/json') {
                throw new UnsupportedMediaTypeException();
            }
        } else {
            throw new UnsupportedMediaTypeException();
        }
    }

    private function validateCustomRules(AppRequest $request)
    {
        $rules = $request->getRules();
        $validationResults = new Vector();
        foreach ($rules as $field => $rule) {
            /** @var \System\Http\Dto\Rule $rule */
            /** @var AbstractRule $ruleObject */
            $ruleObject = (new \ReflectionClass($rule->getClass()))->newInstanceArgs($rule->getParameters());
            if (!$rule->isDefaultMessage()) {
                $ruleObject->setErrorMessage($rule->getErrorMessage());
            }
            $validationResults->push($ruleObject->validate($field,$request->getBasicRequest()->getFieldValue($field)));
        }
        $this->processResults($validationResults);
    }

    private function processResults(Vector $results)
    {
        $errorResults = $results->filter(function (ValidationResult $result){
            return !$result->isSuccess();
        });
        if ($errorResults->count()!==0){
            throw (new BadRequestException())->setResults($errorResults);
        }
    }
}