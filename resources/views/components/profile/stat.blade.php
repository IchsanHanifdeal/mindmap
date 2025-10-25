@props(['icon', 'label', 'value', 'color' => 'primary'])

<div class="bg-white p-4 rounded-lg shadow flex items-center gap-4 w-full">
    <div class="p-3 rounded-full text-white
        @if($color === 'primary') bg-blue-600
        @elseif($color === 'secondary') bg-purple-600
        @elseif($color === 'success') bg-green-600
        @elseif($color === 'warning') bg-yellow-500
        @elseif($color === 'danger') bg-red-600
        @else bg-gray-600
        @endif
    ">
        <x-dynamic-component :component="'lucide-' . $icon" class="w-6 h-6" />
    </div>

    <div class="flex-1 min-w-0">
        <p class="text-sm text-gray-600">{{ $label }}</p>
        <h3 class="font-semibold capitalize text-gray-800 truncate break-words">
            {{ $value }}
        </h3>
    </div>
</div>
