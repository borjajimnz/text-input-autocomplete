<?php

namespace Borjajimnz\TextInputAutocomplete\Forms\Components;

use Closure;
use Filament\Forms\Components\TextInput;

class AutoComplete extends TextInput
{
    protected \Closure|string|null $nativeId = null;

    protected \Closure|bool|null $datalistNative = false;

    protected \Closure|bool|null $datalistOpenOnClick = true;

    protected \Closure|int|null $datalistMinCharsToSearch = 0;

    protected \Closure|bool|null $datalistDisableScroll = false;

    protected \Closure|int|null|false $datalistMaxItems = false;

    protected string $view = 'text-input-autocomplete::forms.components.auto-complete';

    protected function setUp(): void
    {
        $this->autocomplete = false;
    }

    public function datalistNativeId(bool|Closure|null $condition = true): static
    {
        $this->nativeId = $condition;

        return $this;
    }

    public function getDatalistNativeId(): mixed
    {
        return $this->evaluate($this->nativeId);
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

    public function datalistOpenOnClick(bool|Closure|null $condition = true): static
    {
        $this->datalistOpenOnClick = $condition;

        return $this;
    }

    public function getDatalistOpenOnClick(): bool
    {
        return $this->evaluate($this->datalistOpenOnClick) ?? false;
    }

    public function datalistDisableScroll(bool|Closure|null $condition = true): static
    {
        $this->datalistDisableScroll = $condition;

        return $this;
    }

    public function getDatalistDisableScroll(): bool
    {
        return $this->evaluate($this->datalistDisableScroll) ?? false;
    }

    public function datalistMaxItems(int|Closure|false|null $condition = false): static
    {
        $this->datalistMaxItems = $condition;

        return $this;
    }

    public function getDatalistMaxItems(): int
    {
        return $this->evaluate($this->datalistMaxItems) ?? false;
    }

    public function datalistMinCharsToSearch(int|Closure|null $condition = 0): static
    {
        $this->datalistMinCharsToSearch = $condition;

        return $this;
    }

    public function getDatalistMinCharsToSearch(): int
    {
        return $this->evaluate($this->datalistMinCharsToSearch) ?? false;
    }
}
