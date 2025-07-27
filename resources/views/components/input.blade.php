@props([
    'label',
    'name',
    'type' => 'text',
    'required' => false,
    'accept' => '',
    'class' => ''
])

<div class="{{ $class }}">
    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        @if($required) required @endif
        @if($accept) accept="{{ $accept }}" @endif
        class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200"
    />
</div>
