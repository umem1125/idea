@props(['idea' => new \App\Models\Idea()])

<x-modal name="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}" title="{{ $idea->exists ? 'Edit Idea' : 'New Idea'}}">

    <form 
        x-data="{ 
            status: @js(old('status',$idea->status->value)),
            newLink: '',
            links: @js(old('links', $idea->links ?? [])),
            newStep: '',
            steps: @js(old('steps', $idea->steps->map->only(['id', 'description', 'completed']))) 
        }" 
        action="{{ $idea->exists ? route('idea.update', $idea) : route('idea.store') }}" 
        method="POST"
        enctype="multipart/form-data"    
    >
        @csrf

        @if ($idea->exists)
            @method('PATCH')
        @endif

        <div class="space-y-6">
            <x-form.field 
                name="title" 
                id="title" 
                label="Title" 
                autofocus 
                required
                placeholder="What's your thought?"
                :value="$idea->title"
            />

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
                :value="$idea->description" 
            />
            
            <div class="space-y-2">
                <label for="image" class="label">Photo</label>

                @if ($idea->image_path)
                    <div class="space-y-2">
                        <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}"
                            class="w-full h-48 object-cover rounded-lg"
                        >

                        <button class="btn btn-outlined h-10 w-full" form="delete-photo-form">Remove Photo</button>
                    </div>
                @endif

                <input type="file" name="image" id="image" accept="image/*">
                <x-form.error name="image" />
            </div>

            <div>
                <fieldset class="space-y-3">
                    <legend class="label">Actionable Steps</legend>

                    <template x-for="(step, index) in steps" :key="step.id || index">
                        <div class="flex gap-x-2 items-center">
                            <input 
                                type="text" 
                                :name="`steps[${index}][description]`" 
                                x-model="step.description" 
                                class="input"
                                readonly
                            >

                            <input 
                                type="hidden"
                                :name="`steps[${index}][completed]`"
                                x-model="step.completed ? '1' : '0'"
                                class="input"
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
                            type="button" 
                            @click="
                                steps.push({description: newStep.trim(), completed: false});
                            "
                            class="form-muted-icon"
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
                <button type="submit" class="btn">{{ $idea->exists ? 'Update' : 'Create' }}</button>
            </div>

        </div>
    </form>

    @if ($idea->image_path)
        <form method="POST" action="{{ route('idea.destroy.image', $idea) }}" id="delete-photo-form">
            @csrf
            @method('DELETE')
        </form>
    @endif
    
</x-modal>