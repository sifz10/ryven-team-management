<div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3 border border-gray-200 dark:border-gray-700 activity-note" data-note-id="<?php echo e($note->id); ?>">
    <div class="flex items-start justify-between gap-2 mb-2">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center text-white text-xs font-bold">
                <?php echo e(strtoupper(substr($note->user->name ?? 'U', 0, 1))); ?>

            </div>
            <div class="text-xs">
                <span class="font-medium text-gray-900 dark:text-white"><?php echo e($note->user->name ?? 'Unknown'); ?></span>
                <span class="text-gray-500 dark:text-gray-400"> • <?php echo e($note->created_at->diffForHumans()); ?></span>
            </div>
        </div>
        <form method="POST" action="<?php echo e(route('employees.payments.notes.destroy', [$employee, $payment, $note])); ?>" class="inline delete-note-form">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition" onclick="return confirm('Delete this note?')">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </form>
    </div>
    <p class="text-sm text-gray-700 dark:text-gray-300"><?php echo e($note->note); ?></p>
</div>

<?php /**PATH F:\Project\salary\resources\views/employees/partials/activity-note.blade.php ENDPATH**/ ?>