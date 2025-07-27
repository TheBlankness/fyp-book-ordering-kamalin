@props([
    'label',
    'value'
])

<div class="mb-4">
    <label class="block font-medium text-sm text-gray-700">{{ $label }}</label>
    <p class="mt-1 block w-full text-sm text-gray-900 bg-gray-100 border border-gray-300 rounded-md px-3 py-2">
        {{ $value ?? '-' }}
    </p>
</div>
