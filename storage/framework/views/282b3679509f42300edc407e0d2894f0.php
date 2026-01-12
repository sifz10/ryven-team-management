<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'info', 'message' => '']));

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

foreach (array_filter((['type' => 'info', 'message' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $colors = [
        'success' => 'bg-green-600',
        'error' => 'bg-red-600',
        'info' => 'bg-black',
    ];
    $bg = $colors[$type] ?? $colors['info'];
?>

<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="<?php echo e($bg); ?> text-white rounded-full shadow px-4 py-2 flex items-center gap-3">
    <span class="inline-block w-2 h-2 rounded-full bg-white/70"></span>
    <span class="text-sm"><?php echo e($message); ?></span>
    <button type="button" class="ms-2 text-white/80 hover:text-white" @click="show = false">×</button>
    <span class="sr-only">Toast</span>
</div>


<?php /**PATH D:\Ryven Works\ryven-team-management\resources\views/components/toast.blade.php ENDPATH**/ ?>