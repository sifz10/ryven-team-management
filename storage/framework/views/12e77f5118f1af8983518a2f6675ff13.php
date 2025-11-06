<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'button',
    'size' => 'md', // sm, md, lg
    'variant' => 'default', // default, black
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'type' => 'button',
    'size' => 'md', // sm, md, lg
    'variant' => 'default', // default, black
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $baseClasses = 'inline-flex items-center justify-center rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    $sizeClasses = [
        'sm' => 'p-1.5 w-8 h-8',
        'md' => 'p-2 w-10 h-10',
        'lg' => 'p-3 w-12 h-12',
    ];
    
    $variantClasses = [
        'default' => 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-gray-500',
        'black' => 'bg-black text-white hover:bg-gray-800 focus:ring-black dark:bg-white dark:text-black dark:hover:bg-gray-200 dark:focus:ring-white',
    ];
    
    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant];
?>

<button 
    type="<?php echo e($type); ?>"
    <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php echo e($slot); ?>

</button>
<?php /**PATH F:\Project\salary\resources\views/components/icon-button.blade.php ENDPATH**/ ?>