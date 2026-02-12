<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>
            <p class="text-muted-foreground text-sm mt-2">Capture your thoughts. Make a plan.</p>

            <x-card 
                x-data @click="$dispatch('open-modal', 'create-idea')" 
                data-test="create-idea-button" is="button"
                type="button" 
                class="mt-10 cursor-pointer h-32 w-full text-left">
                <p>What's the idea?</p>
            </x-card>
        </header>

        {{-- filtering the status --}}
        <div>
            <a href="/ideas" class="btn {{ request()->has('status') ? 'btn-outlined' : '' }}">All</a>

            @foreach (App\IdeaStatus::cases() as $status)
                <a href="/ideas?status={{ $status->value }}"
                    class="btn {{ request('status') === $status->value ? '' : 'btn-outlined' }}">
                    {{ $status->label() }} <span class="text-xs pl-3">{{ $statusCounts->get($status->value) }}</span>
                </a>
            @endforeach
        </div>

        <div class="mt-10 text-muted-foreground">
            <div class="grid md:grid-cols-2 gap-6">
                @forelse ($ideas as $idea)
                    <x-card href="{{ route('idea.show', $idea->id) }}">
                        <h3 class="text-foreground text-lg">{{ $idea->title }}</h3>
                        <div class="mt-1">
                            <x-status-label status="{{ $idea->status }}">
                                {{ $idea->status->label() }}
                            </x-status-label>
                        </div>

                        <div class="mt-5 line-clamp-3">{{ $idea->description }}</div>
                        <div class="mt-4">{{ $idea->created_at->diffForHumans() }}</div>
                    </x-card>
                @empty
                    <x-card>
                        <p>No ideas at this time.</p>
                    </x-card>
                @endforelse
            </div>
        </div>
        {{-- modal --}}
        <x-modal name="create-idea" title="New Idea">
            <form 
                x-data="{ 
                    status: 'pending',
                    newLink: '',
                    links: [],
                    newStep: '',
                    steps: [] 
                }" 
                action="{{ route('idea.store') }}" 
                method="POST">
                @csrf

                <div class="space-y-6">
                    <x-form.field name="title" id="title" label="Title" autofocus required
                        placeholder="Enter an idea for your title" />

                    <div>
                        <label for="status" class="label">Status</label>
                        <div class="flex gap-x-3">
                            @foreach (App\IdeaStatus::cases() as $status)
                                <button type="button" @click="status = @js($status->value)"
                                    class="btn flex-1 h-10" data-test="button-status-{{ $status->value }}"
                                    :class="{ 'btn-outlined': status !== @js($status->value) }">
                                    {{ $status->label() }}
                                </button>
                            @endforeach

                            <input type="hidden" name="status" :value="status" class="input">
                        </div>

                        <x-form.error name="status" />
                    </div>

                    <x-form.field 
                        type="textarea" 
                        name="description" 
                        label="Description"
                        placeholder="Write your thoughts here..." 
                    />

                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Actionable Steps</legend>

                            <template x-for="(step, index) in steps">
                                <div class="flex gap-x-2 items-center">
                                    <input 
                                        type="text" 
                                        name="steps[]" 
                                        x-model="step" 
                                        class="input"
                                        readonly
                                    >

                                    <button
                                        class="form-muted-icon" 
                                        type="button" 
                                        aria-label="Remove a step"
                                        @click="steps.splice(index, 1)"
                                    >
                                    <x-icons.trash-button />
                                </div>
                            </template>

                            <div class="flex gap-x-2 items-center">
                                <input
                                    x-model="newStep" 
                                    type="text"
                                    id="new-step"
                                    class="input flex-2"
                                    spellcheck="false"
                                    data-test="new-step"
                                    placeholder="What needs to be done?"
                                >

                                <button 
                                    class="form-muted-icon"
                                    type="button" 
                                    @click="steps.push(newStep); newStep = '';"
                                    :disabled="newStep.trim().length === 0"
                                    aria-label="Add a step"
                                    data-test="submit-new-step-button"
                                >
                                    <x-icons.plus-button />
                                </button>
                            </div>
                        </fieldset>
                    </div>

                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Links</legend>

                            <template x-for="(link, index) in links">
                                <div class="flex gap-x-2 items-center">
                                    <input 
                                        type="text" 
                                        name="links[]" 
                                        x-model="link" 
                                        class="input"
                                        readonly
                                    >

                                    <button
                                        class="form-muted-icon" 
                                        type="button" 
                                        aria-label="Remove a link"
                                        @click="links.splice(index, 1)"
                                    >
                                    <x-icons.trash-button />
                                </div>
                            </template>

                            <div class="flex gap-x-2 items-center">
                                <input
                                    x-model="newLink" 
                                    type="url"
                                    id="new-link"
                                    placeholder="http://example.com"    
                                    autocomplete="url"
                                    class="input flex-2"
                                    spellcheck="false"
                                    data-test="new-link"
                                >

                                <button 
                                    class="form-muted-icon"
                                    type="button" 
                                    @click="links.push(newLink); newLink = '';"
                                    :disabled="newLink.trim().length === 0"
                                    aria-label="Add a link"
                                    data-test="submit-new-link-button"
                                >
                                    <x-icons.plus-button />
                                </button>
                            </div>
                        </fieldset>
                    </div>

                    <div class="flex justify-end gap-x-5">
                        <button type="button" @click="$dispatch('close-modal')">Cancel</button>
                        <button type="submit" class="btn">Create</button>
                    </div>

                </div>
            </form>

        </x-modal>
    </div>
</x-layout>
