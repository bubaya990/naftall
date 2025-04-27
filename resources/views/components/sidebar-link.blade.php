@props(['href', 'label'])

<a href="{{ $href }}"
   class="block px-4 py-2 rounded-lg transition duration-200 hover:bg-[#142C4B] hover:translate-x-1 hover:shadow-md">
   {{ $label }}
</a>
