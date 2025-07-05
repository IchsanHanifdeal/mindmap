@props(['icon', 'label', 'value', 'color' => 'primary'])

<div class="bg-white p-4 rounded-lg shadow flex items-center gap-4">
    <div class="p-3 bg-{{ $color }} text-white rounded-full">
        <x-lucide-{{ $icon }} class="w-6 h-6" />
    </div>
    <div>
        <p class="text-sm text-gray-600">{{ $label }}</p>
        <h3 class="font-semibold capitalize">{{ $value }}</h3>
    </div>
</div>
