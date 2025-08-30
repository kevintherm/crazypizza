@props([
    'id',
    'maxFiles' => 1,
    'allowedTypes' => ['image/jpg'],
    'maxSize' => 9999999,
    'maxDimensions' => [
        'width' => 99999,
        'height' => 99999,
    ],
])

<div x-data="$store.fileUploader({
    fileInputElement: $refs.fileInput,
    ...@js(compact('maxFiles', 'allowedTypes', 'maxSize', 'maxDimensions'))
})" {{ $attributes }} @file-upload-clear.window="clearFiles">
    <input x-ref="fileInput" class="hidden" type="file" id="{{ $id }}" name="{{ $id }}"
        x-on:change="handleFiles($event.target.files)" multiple>
    <label
        :class="dragActive ? 'border-primary dark:border-primary-dark' :
            'border-outline dark:border-outline-dark'"
        for="{{ $id }}"
        class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed p-4"
        @drop.prevent="dropFile($event)" @dragover.prevent="dragOver" @dragleave.prevent="dragLeave">
        <div x-show="files.length < 1" class="flex flex-col items-center gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-24">
                <path stroke-linecap="round" stroke-linejoin="round" o
                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
            </svg>
            <p x-show="files.length < 1">Drop files here or click to upload</p>
        </div>
        <div class="flex flex-wrap items-start gap-2">
            <template x-for="(fileWrapper, index) in files" :key="index">
                <div class="relative">

                    <template x-if="fileWrapper.type === 'image'">
                        <img :src="URL.createObjectURL(fileWrapper.file)"
                            class="rounded-radius w-full max-w-2xl cursor-pointer object-cover"
                            x-on:click.stop.prevent="$store.mg.viewImage.open(URL.createObjectURL(fileWrapper.file))" />
                    </template>

                    <template x-if="fileWrapper.type === 'file'">
                        <div class="border-outline dark:border-outline-dark rounded-radius w-full max-w-36 border p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>
                    </template>

                    <div
                        class="rounded-b-radius bg-primary dark:bg-primary-dark absolute bottom-0 left-0 right-0 px-2 py-1">
                        <p class="text-on-primary dark:text-on-primary-dark line-clamp-1 text-xs"
                            x-text="fileWrapper.file.name.toString()"></p>
                    </div>
                    <button
                        class="absolute right-2 top-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-white"
                        @click.stop.prevent="removeFile(index)">
                        ×
                    </button>
                </div>
            </template>
        </div>
    </label>

    <div class="mt-4">
        <template x-for="(filename, index) in Object.keys(errors)" :key="filename">
            <div x-data="{
                alertIsVisible: true,
                kill() {
                    setTimeout(() => {
                        this.alertIsVisible = false;
                        setTimeout(() => (delete this.errors[filename]), 500);
                    }, 30000 + index * 10)
                }
            }" x-show="alertIsVisible" x-init="kill"
                class="rounded-radius border-danger bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark relative mb-2 w-full overflow-hidden border"
                role="alert" x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                <div class="bg-danger/10 flex w-full items-center gap-2 p-4">
                    <div class="bg-danger/15 text-danger rounded-full p-1" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6"
                            aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-2">
                        <h3 class="text-danger break-words pe-4 text-sm font-semibold" x-text="filename"></h3>
                        <p class="text-xs font-medium sm:text-sm" x-text="errors[filename]">
                        </p>
                    </div>
                    <button type="button" x-on:click="alertIsVisible = false" class="ml-auto"
                        aria-label="dismiss alert">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"
                            stroke="currentColor" fill="none" stroke-width="2.5" class="h-4 w-4 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>
