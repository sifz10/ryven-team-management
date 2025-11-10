<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'type' => 'button',
    'variant' => 'solid', // solid, outline
    'size' => 'md', // sm, md, lg
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'href' => null, // If provided, renders as <a> tag instead of <button>
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
    'variant' => 'solid', // solid, outline
    'size' => 'md', // sm, md, lg
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'href' => null, // If provided, renders as <a> tag instead of <button>
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2';

    $sizeClasses = [
        'sm' => 'px-4 py-2 text-sm rounded-full',
        'md' => 'px-6 py-2.5 text-base rounded-full',
        'lg' => 'px-8 py-3 text-lg rounded-full',
    ];

    $variantClasses = [
        'solid' => 'bg-black text-white hover:bg-gray-800 focus:ring-black dark:bg-black dark:text-white dark:hover:bg-gray-900 dark:focus:ring-black',
        'outline' => 'border-2 border-black text-black hover:bg-black hover:text-white focus:ring-black dark:border-white dark:text-white dark:hover:bg-white dark:hover:text-black dark:focus:ring-white',
    ];

    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant];
?>

<?php if($href): ?>
    <a
        href="<?php echo e($href); ?>"
        <?php echo e($attributes->merge(['class' => $classes])); ?>>

        <?php if($icon && $iconPosition === 'left'): ?>
            <?php echo $icon; ?>

        <?php endif; ?>

        <?php echo e($slot); ?>


        <?php if($icon && $iconPosition === 'right'): ?>
            <?php echo $icon; ?>

        <?php endif; ?>
    </a>
<?php else: ?>
    <button
        type="<?php echo e($type); ?>"
        <?php echo e($attributes->merge(['class' => $classes])); ?>>

        <?php if($icon && $iconPosition === 'left'): ?>
            <?php echo $icon; ?>

        <?php endif; ?>

        <?php echo e($slot); ?>


        <?php if($icon && $iconPosition === 'right'): ?>
            <?php echo $icon; ?>

        <?php endif; ?>
    </button>
<?php endif; ?>
<?php /**PATH F:\Project\salary\resources\views/components/black-button.blade.php ENDPATH**/ ?>