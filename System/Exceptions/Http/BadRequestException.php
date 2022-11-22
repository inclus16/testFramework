<?php


namespace System\Exceptions\Http;


use Ds\Sequence;
use Ds\Vector;
use System\Exceptions\AbstractSystemException;
use System\Exceptions\Handler;
use System\Http\Dto\ValidationResult;

class BadRequestException extends AbstractSystemException
{
    private Vector $validationResults;

    public function setResults(Vector $validationResults): self
    {
        $this->validationResults = $validationResults;
        return $this;
    }

    public function getType(): int
    {
        return Handler::HTTP;
    }

    public function getMessages(): Sequence
    {
        return $this->validationResults->map(function (ValidationResult $result) {
            return $result->getError();
        });
    }
}