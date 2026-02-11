@props(['name', 'title'])

<div x-data="{ show: false, name: 'create-idea' }" x-show="show" @open-modal.window="if($event.detail === name) show = true;"
    @keydown.escape.window="show = false" @close-modal="show = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs"
    x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 -transition-y-4 -translate-x-4"
    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 -transition-y-4 -translate-x-4" style="display: none" role="dialog"
    aria-modal="true" aria-labelledby="modal-{{ $name }}-title" :area-hidden="!show" tabindex="-1">

    <x-card @click.away="show = false" class="shadow-xl max-w-2xl w-full max-h-[800vh] overflow-auto">
        <div class="flex justify-between">
            <h2 id="modal-{{ $name }}-title" class="text-xl font-bold">{{ $title }}</h2>

            <button @click="show = false">
                <x-icons.close-button />
            </button>
        </div>

        <div class="mt-4">
            {{ $slot }}
        </div>
    </x-card>
</div>
