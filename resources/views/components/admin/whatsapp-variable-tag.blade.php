@props([
    'variable' => '',
    'onClick' => null
])

<span 
    {{ $attributes->merge(['class' => 'px-2 py-1 bg-emerald-100 text-emerald-800 rounded text-sm cursor-pointer hover:bg-emerald-200 inline-block mb-1 mr-1']) }}
    @if($onClick) onclick="{{ $onClick }}('{{ $variable }}')" @endif
>
    {{ $variable }}
</span> 