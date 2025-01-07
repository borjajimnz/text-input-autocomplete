<?php

namespace Borjajimnz\TextInputAutocomplete\Forms\Components;

use Closure;
use Filament\Forms\Components\TextInput;

class AutoComplete extends TextInput
{
    protected string $view = 'text-input-autocomplete::forms.components.auto-complete';

    protected \Closure|bool|null $datalistNative = true;

    protected \Closure|int|null $datalistMaxItems = 10;

    public function datalistMaxItems(int|Closure|null $condition = 10): static
    {
        $this->datalistMaxItems = $condition;

        return $this;
    }

    public function getDatalistMaxItems(): int
    {
        return $this->evaluate($this->datalistMaxItems) ?? false;
    }

    public function datalistNative(bool|Closure|null $condition = true): static
    {
        $this->datalistNative = $condition;

        return $this;
    }

    public function getDatalistNative(): bool
    {
        return $this->evaluate($this->datalistNative) ?? false;
    }
}
