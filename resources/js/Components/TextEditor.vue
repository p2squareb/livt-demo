<template>
    <div v-if="editor" id="text-editor" class="w-full border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
        <div class="px-3 py-2 border-b dark:border-gray-600">
            <div class="flex flex-wrap items-center">
                <div class="flex items-center space-x-1 rtl:space-x-reverse flex-wrap">

                    <button
                        type="button"
                        class="p-1.5 rounded cursor-pointer text-gray-700 dark:text-gray-300 "
                        :class="{ 'text-gray-900 dark:text-white bg-gray-300 dark:bg-gray-900': editor.isActive('bold') }"
                        @click="editor.chain().focus().toggleBold().run()"
                    >
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5h4.5a3.5 3.5 0 1 1 0 7H8m0-7v7m0-7H6m2 7h6.5a3.5 3.5 0 1 1 0 7H8m0-7v7m0 0H6"/>
                        </svg>
                        <span class="sr-only">Bold</span>
                    </button>

                    <button
                        type="button"
                        class="p-1.5 rounded cursor-pointer text-gray-700 dark:text-gray-300 "
                        :class="{ 'text-gray-900 dark:text-white bg-gray-300 dark:bg-gray-900': editor.isActive('strike') }"
                        @click="editor.chain().focus().toggleStrike().run()"
                    >
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 6.2V5h12v1.2M7 19h6m.2-14-1.677 6.523M9.6 19l1.029-4M5 5l6.523 6.523M19 19l-7.477-7.477"></path>
                        </svg>
                        <span class="sr-only">Strike</span>
                    </button>

                    <button
                        type="button"
                        class="p-1.5 rounded cursor-pointer text-gray-700 dark:text-gray-300 "
                        :class="{ 'text-gray-900 dark:text-white bg-gray-300 dark:bg-gray-900': editor.isActive('link') }"
                        @click="setLink"
                    >
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.213 9.787a3.391 3.391 0 0 0-4.795 0l-3.425 3.426a3.39 3.39 0 0 0 4.795 4.794l.321-.304m-.321-4.49a3.39 3.39 0 0 0 4.795 0l3.424-3.426a3.39 3.39 0 0 0-4.794-4.795l-1.028.961"></path>
                        </svg>
                        <span class="sr-only">Link</span>
                    </button>

                    <button
                        type="button"
                        class="p-1.5 rounded cursor-pointer text-gray-700 dark:text-gray-300 "
                        @click="addImage"
                    >
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M13 10a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2H14a1 1 0 0 1-1-1Z" clip-rule="evenodd"></path>
                        <path fill-rule="evenodd" d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12c0 .556-.227 1.06-.593 1.422A.999.999 0 0 1 20.5 20H4a2.002 2.002 0 0 1-2-2V6Zm6.892 12 3.833-5.356-3.99-4.322a1 1 0 0 0-1.549.097L4 12.879V6h16v9.95l-3.257-3.619a1 1 0 0 0-1.557.088L11.2 18H8.892Z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Add image</span>
                    </button>

                    <button
                        type="button"
                        class="p-1.5 rounded cursor-pointer text-gray-700 dark:text-gray-300 "
                        @click="addVideo"
                    >
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M21.7 8.037a4.26 4.26 0 0 0-.789-1.964 2.84 2.84 0 0 0-1.984-.839c-2.767-.2-6.926-.2-6.926-.2s-4.157 0-6.928.2a2.836 2.836 0 0 0-1.983.839 4.225 4.225 0 0 0-.79 1.965 30.146 30.146 0 0 0-.2 3.206v1.5a30.12 30.12 0 0 0 .2 3.206c.094.712.364 1.39.784 1.972.604.536 1.38.837 2.187.848 1.583.151 6.731.2 6.731.2s4.161 0 6.928-.2a2.844 2.844 0 0 0 1.985-.84 4.27 4.27 0 0 0 .787-1.965 30.12 30.12 0 0 0 .2-3.206v-1.516a30.672 30.672 0 0 0-.202-3.206Zm-11.692 6.554v-5.62l5.4 2.819-5.4 2.801Z" clip-rule="evenodd"/>
                        </svg>
                        <span class="sr-only">Add Youtube Video</span>
                    </button>
                    
                </div>
            </div>
        </div>
        <div class="px-4 py-2 bg-white rounded-b-lg dark:bg-gray-800">
            <div class="w-full flex flex-col px-0 text-md text-gray-800 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400">
                <editor-content :editor="editor"/>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useEditor, EditorContent } from '@tiptap/vue-3'
import { StarterKit } from '@tiptap/starter-kit'
import { Image } from '@tiptap/extension-image'
import { Link } from '@tiptap/extension-link'
import { Youtube } from '@tiptap/extension-youtube'
import { watch } from "vue";

interface TextareaProps {
    modelValue?: string
    textareaMinHeight?: string
}

const props = withDefaults(defineProps<TextareaProps>(), {
    modelValue: '',
    textareaMinHeight: '300',
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
    content: props.modelValue,
    extensions: [
        StarterKit, Image, 
        Link.configure({
            openOnClick: false,
            defaultProtocol: 'https',
            HTMLAttributes: {
                class: 'text-blue-500',
            },
        }),
        Youtube.configure({
            controls: false,
            nocookie: false,
        }),
    ],

    editorProps: {
        attributes: {
            class: `text-md prose prose-sm sm:prose lg:prose-lg xl:prose-2xl mx-auto focus:outline-none min-h-[300px]`,
            spellcheck: 'false',
        },
    },

    onUpdate({ editor }) {
        emit('update:modelValue', editor.getHTML());
    },
})

const addImage = () => {
    const url = window.prompt('URL')
    if (url) {
        editor.value?.chain().focus().setImage({ src: url }).run()
    }
}

const setLink = () => {
    const previousUrl = editor.value?.getAttributes('link').href
    const url = window.prompt('URL', previousUrl)

    // cancelled
    if (url === null) {
        return
    }

    // empty
    if (url === '') {
        editor.value?.chain().focus().extendMarkRange('link').unsetLink().run()
        return
    }

    // update link
    editor.value?.chain().focus().extendMarkRange('link').setLink({ href: url }).run()
}

const addVideo = () => {
    const url = prompt('YouTube URL을 입력하세요')
    if (url) {
        editor.value?.commands.setYoutubeVideo({
            src: url,
            width: 100,
            height: 400,
        })
    }
}

watch(() => props.modelValue, (newValue) => {
    if (editor && editor.value?.getHTML() !== newValue) {
        editor.value?.commands.setContent(newValue);
    }
});
</script>