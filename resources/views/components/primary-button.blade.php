<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary justify-center']) }}>
    {{ $slot }}
</button>
