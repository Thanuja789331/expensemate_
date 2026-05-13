<button {{ $attributes->merge(['type' => 'submit', 'class' => 'theme-button']) }}>
    {{ $slot }}
</button>
