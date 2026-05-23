@props(['name', 'val', 'borderRight' => 'border-gray-700/50'])

<td class="p-0 border-r {{ $borderRight }}">
    <input type="number" step="0.01" name="{{ $name }}" value="{{ $val ? rtrim(rtrim((string)$val, '0'), '.') : '' }}" 
           class="cell-input w-full h-full min-w-[80px] px-3 py-2.5 bg-transparent border border-transparent focus:bg-gray-900 focus:border-indigo-500 focus:ring-0 text-right outline-none transition-all text-white placeholder-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">
</td>
