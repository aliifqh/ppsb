<template>
    <div class="flex flex-col">
        <label class="block text-sm font-medium text-gray-700 mb-2">{{ label }}</label>
        <div class="flex items-center space-x-4 p-3 border-2 border-dashed rounded-lg" :class="[isDragover ? 'border-emerald-400 bg-emerald-50' : 'border-gray-300']" @dragover.prevent="isDragover = true" @dragleave.prevent="isDragover = false" @drop.prevent="handleDrop">

            <!-- Preview/Icon -->
            <div class="flex-shrink-0 h-16 w-16 rounded-md flex items-center justify-center" :class="[previewUrl ? 'bg-gray-100' : 'bg-gray-200']">
                <img v-if="previewUrl" :src="previewUrl" alt="Preview" class="h-full w-full object-cover rounded-md">
                <i v-else :class="['text-3xl text-gray-500', getFileIcon(fileUrl)]"></i>
            </div>

            <!-- Info & Actions -->
            <div class="flex-grow min-w-0">
                <p v-if="fileName" class="text-sm font-medium text-gray-800 truncate" :title="fileName">{{ fileName }}</p>
                <p v-else-if="fileUrl" class="text-sm font-medium text-gray-600">File sudah diupload</p>
                <p v-else class="text-sm text-gray-500">Pilih atau seret file ke sini</p>

                <div class="flex items-center space-x-3 mt-1 text-xs">
                    <button type="button" @click="triggerFileInput" class="font-semibold text-emerald-600 hover:text-emerald-800 transition-colors">
                        {{ fileUrl || fileName ? 'Ganti File' : 'Pilih File' }}
                    </button>
                    <button v-if="fileUrl && !fileName" @click.prevent="$emit('preview')" class="font-semibold text-blue-600 hover:text-blue-800 transition-colors">Lihat</button>
                    <button v-if="fileName" @click="removeFile" class="font-semibold text-red-600 hover:text-red-800 transition-colors">Batal</button>
                </div>
            </div>
            <input type="file" ref="fileInput" class="hidden" @change="handleFileSelect" accept="image/*,.pdf">
        </div>
    </div>
</template>

<script>
import { ref, computed } from 'vue';

export default {
    name: 'SingleDocUploader',
    props: {
        label: String,
        fileUrl: String, // URL of existing file
    },
    emits: ['file-change', 'preview'],
    setup(props, { emit }) {
        const fileInput = ref(null);
        const selectedFile = ref(null);
        const isDragover = ref(false);

        const isFileImage = computed(() => {
            if (selectedFile.value) {
                return selectedFile.value.type.startsWith('image/');
            }
            if (props.fileUrl) {
                const ext = props.fileUrl.split('?')[0].split('.').pop().toLowerCase();
                return ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext);
            }
            return false;
        });

        const previewUrl = computed(() => {
            if (selectedFile.value && isFileImage.value) {
                return URL.createObjectURL(selectedFile.value);
            }
            if (props.fileUrl && isFileImage.value) {
                return props.fileUrl;
            }
            return null;
        });

        const fileName = computed(() => selectedFile.value?.name);

        const triggerFileInput = () => {
            fileInput.value.click();
        };

        const handleFileSelect = (event) => {
            const file = event.target.files[0];
            if (file) {
                selectedFile.value = file;
                emit('file-change', file);
            }
        };

        const handleDrop = (event) => {
            isDragover.value = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                selectedFile.value = file;
                emit('file-change', file);
            }
        };

        const removeFile = () => {
            selectedFile.value = null;
            fileInput.value.value = ''; // Reset file input
            emit('file-change', null);
        };

        const getFileIcon = (url) => {
            if (!url) return 'fas fa-file-alt';
            const ext = url.split('?')[0].split('.').pop().toLowerCase();
            if (ext === 'pdf') return 'fas fa-file-pdf text-red-500';
            if (['doc', 'docx'].includes(ext)) return 'fas fa-file-word text-blue-500';
            if (['xls', 'xlsx'].includes(ext)) return 'fas fa-file-excel text-green-500';
            return 'fas fa-file-alt';
        };

        return {
            fileInput,
            isDragover,
            previewUrl,
            fileName,
            triggerFileInput,
            handleFileSelect,
            handleDrop,
            removeFile,
            getFileIcon,
        }
    }
}
</script>
