@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'form-input border-slate-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm w-full']) }}>
