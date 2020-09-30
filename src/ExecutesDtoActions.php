<?php

namespace R4nkt\LaravelDtoAction;

trait ExecutesDtoActions
{
    protected ?string $dtoActionClass;

    public function execute()
    {
        return resolve($this->getDtoActionClass())->execute($this);
    }

    public function getDtoActionClass()
    {
        return $this->dtoActionClass;
    }

    public function setDtoActionClass(string $dtoActionClass)
    {
        $this->dtoActionClass = $dtoActionClass;

        return $this;
    }
}
