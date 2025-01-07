@php
    use Filament\Forms\Components\TextInput\Actions\HidePasswordAction;
    use Filament\Forms\Components\TextInput\Actions\ShowPasswordAction;

    $datalistOptions = $getDatalistOptions();
    $extraAlpineAttributes = $getExtraAlpineAttributes();
    $hasInlineLabel = $hasInlineLabel();
    $id = $getId();
    $isConcealed = $isConcealed();
    $isDisabled = $isDisabled();
    $isPasswordRevealable = $isPasswordRevealable();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $mask = $getMask();
    $prefixActions = $getPrefixActions();
    $prefixIcon = $getPrefixIcon();
    $prefixLabel = $getPrefixLabel();
    $suffixActions = $getSuffixActions();
    $suffixIcon = $getSuffixIcon();
    $suffixLabel = $getSuffixLabel();
    $statePath = $getStatePath();
    $dataListNative = $getDatalistNative() ?? true;
    $maxItems = $getDatalistMaxItems();

    if ($isPasswordRevealable) {
        $xData = '{ isPasswordRevealed: false }';
    } elseif (count($extraAlpineAttributes) || filled($mask)) {
        $xData = '{}';
    } else {
        $xData = null;
    }

   if ($dataListNative === false && filled($datalistOptions)) {
        $xData = '{
            search: null,
            state: null,
            isDatalistOpen: false,
            items: [],
            highlightedIndex: 0,
            options: ' . json_encode($datalistOptions) . ',
            maxItems: ' . $maxItems . ',
            filterItems() {
                if (!this.search || this.search.length < 2) {
                    this.closeDropdown();
                    return;
                }

                searchTerm = this.search.toLowerCase();
                this.items = this.options.filter(item =>
                    item.toLowerCase().includes(searchTerm)
                ).slice(0, this.maxItems);
                this.isDatalistOpen = this.items.length > 0;
                this.highlightedIndex = 0;
            },
            selectItem(item) {
                this.search = item;
                this.$wire.set("' . $statePath . '", item);
                setTimeout(() => {
                    this.closeDropdown();
                }, 5);
            },
            closeDropdown() {
                this.isDatalistOpen = false;
                this.items = [];
            },
            onKeyDown(event) {
                if (!this.isDatalistOpen) {
                    return;
                }

                switch (event.key) {
                    case "ArrowUp":
                        event.preventDefault();
                        this.highlightedIndex = this.highlightedIndex > 0 ? this.highlightedIndex - 1 : this.items.length - 1;
                        break;
                    case "ArrowDown":
                        event.preventDefault();
                        this.highlightedIndex = this.highlightedIndex < this.items.length - 1 ? this.highlightedIndex + 1 : 0;
                        break;
                    case "Enter":
                        event.preventDefault();
                        const selectedItem = this.items[this.highlightedIndex];
                        if (selectedItem) {
                            this.selectItem(selectedItem);
                        }
                        break;
                    case "Escape":
                        event.preventDefault();
                        this.closeDropdown();
                        break;
                }
            }
        }';
    }

    if ($isPasswordRevealable) {
        $type = null;
    } elseif (filled($mask)) {
        $type = 'text';
    } else {
        $type = $getType();
    }
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
>
    <x-slot
        name="label"
        @class([
            'sm:pt-1.5' => $hasInlineLabel,
        ])
    >
        {{ $getLabel() }}
    </x-slot>

    <x-filament::input.wrapper
        :disabled="$isDisabled"
        :inline-prefix="$isPrefixInline"
        :inline-suffix="$isSuffixInline"
        :prefix="$prefixLabel"
        :prefix-actions="$prefixActions"
        :prefix-icon="$prefixIcon"
        :prefix-icon-color="$getPrefixIconColor()"
        :suffix="$suffixLabel"
        :suffix-actions="$suffixActions"
        :suffix-icon="$suffixIcon"
        :suffix-icon-color="$getSuffixIconColor()"
        :valid="! $errors->has($statePath)"
        :x-data="$xData"
        :attributes="
            \Filament\Support\prepare_inherited_attributes($getExtraAttributeBag())
                ->class(['fi-fo-text-input overflow-hidden'])
        "
    >
        <x-filament::input
            :attributes="
                \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                    ->merge($extraAlpineAttributes, escape: false)
                    ->merge([
                        'autocapitalize' => $getAutocapitalize(),
                        'autocomplete' => $getAutocomplete(),
                        'autofocus' => $isAutofocused(),
                        'disabled' => $isDisabled,
                        'id' => $id,
                        'inlinePrefix' => $isPrefixInline && (count($prefixActions) || $prefixIcon || filled($prefixLabel)),
                        'inlineSuffix' => $isSuffixInline && (count($suffixActions) || $suffixIcon || filled($suffixLabel)),
                        'inputmode' => $getInputMode(),
                        'list' => $datalistOptions ? $id . '-list' : null,
                        'max' => (! $isConcealed) ? $getMaxValue() : null,
                        'maxlength' => (! $isConcealed) ? $getMaxLength() : null,
                        'min' => (! $isConcealed) ? $getMinValue() : null,
                        'minlength' => (! $isConcealed) ? $getMinLength() : null,
                        'placeholder' => $getPlaceholder(),
                        'readonly' => $isReadOnly(),
                        'required' => $isRequired() && (! $isConcealed),
                        'step' => $getStep(),
                        'type' => $type,
                        $applyStateBindingModifiers('wire:model') => $statePath,
                        'x-bind:type' => $isPasswordRevealable ? 'isPasswordRevealed ? \'text\' : \'password\'' : null,
                        'x-mask' . ($mask instanceof \Filament\Support\RawJs ? ':dynamic' : '') => filled($mask) ? $mask : null,
                        '@keydown' => 'onKeyDown($event)',
                        '@input' => 'filterItems',
                        'x-model' => 'search',
                    ], escape: false)
                    ->class([
                        '[&::-ms-reveal]:hidden' => $isPasswordRevealable,
                    ])
            "
        />

        <div
            x-show="isDatalistOpen"
            x-transition
            class="absolute z-50 mt-1 bg-white dark:bg-gray-950 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600"
        >
            <ul class="py-1">
                <template x-for="(item, index) in items" :key="index">
                    <li
                        x-text="item"
                        @click="selectItem(item)"
                        @mousedown.prevent
                        :class="{
                            'bg-primary-50 dark:bg-primary-950 text-primary-600 dark:text-primary-400': index === highlightedIndex,
                            'hover:bg-gray-50 dark:hover:bg-gray-800': index !== highlightedIndex,
                            'px-4 py-2 cursor-pointer': true
                        }"
                    ></li>
                </template>
            </ul>
        </div>
    </x-filament::input.wrapper>

    @if ($dataListNative && $datalistOptions)
        <datalist id="{{ $id }}-list">
            @foreach ($datalistOptions as $option)
                <option value="{{ $option }}" />
            @endforeach
        </datalist>
    @endif
</x-dynamic-component>
