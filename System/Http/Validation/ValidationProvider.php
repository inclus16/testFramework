<?php


namespace System\Http\Validation;


use Ds\Map;
use Ds\Vector;
use System\Exceptions\Http\BadRequestException;
use System\Http\Dto\ValidationResult;
use System\Http\Requests\AppRequest;
use System\Http\Requests\BasicRequest;
use System\Http\Validation\Rules\RuleInterface;

class ValidationProvider
{
    public function validateRequest(AppRequest $request): ?ValidationResult
    {
        $basicRequest = $request->getBasicRequest();
        $result = $this->validateMediaType($request->isJson(), $basicRequest);
        if ($result !== null) {
            return $result;
        }
        $result = $this->validateFields($request);
        if ($result !== null) {
            return $result;
        }
        return null;
    }

    private function validateMediaType(bool $isJson, BasicRequest $basicRequest): ?ValidationResult
    {
        if (!$isJson) {
            return null;
        }
        $headerKey = 'Content-Type';
        if (!$basicRequest->hasHeader($headerKey) || $basicRequest->getHeader($headerKey) !== 'application/json') {
            new ValidationResult(415, new Map());
        }
        return null;
    }

    private function validateFields(AppRequest $request): ?ValidationResult
    {
        $rules = $request->getRules();
        $validationResults = new Map();
        /**
         * @var string $field
         * @var RuleInterface $rule
         */
        foreach ($rules as $field => $rule) {
            if ($rule->validate($field, $request->getBasicRequest()->getFieldValue($field))) {
                $validationResults->put($field, $rule->getMessage());
            }
        }
        if (!$validationResults->isEmpty()) {
            return new ValidationResult(400, $validationResults);
        }
        return null;
    }
}