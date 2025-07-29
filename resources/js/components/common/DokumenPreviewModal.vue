<template>
    <div v-if="show" @click="close" class="fixed inset-0 z-[99] flex items-center justify-center bg-black bg-opacity-75 backdrop-blur-sm p-4 animate-fadeIn">
        <div @click.stop class="relative w-full h-full flex flex-col items-center justify-center">

            <!-- Header/Title -->
            <div class="absolute top-5 text-center text-white text-lg font-semibold bg-black bg-opacity-50 px-4 py-2 rounded-lg z-10">
                {{ currentItem.label }} ({{ currentIndex + 1 }} / {{ items.length }})
            </div>

            <!-- Content Wrapper for Centering Navigation -->
            <div class="relative w-full max-w-[95vw] h-full flex items-center justify-center">
                <!-- Content -->
                <div
                    class="relative animate-zoomIn w-auto h-auto max-w-full max-h-[85vh] flex items-center justify-center bg-gray-800 rounded-lg shadow-2xl overflow-hidden"
                    @wheel.prevent="handleWheel"
                >
                    <div
                        class="transition-transform duration-200"
                        :style="transformStyle"
                        @mousedown="startPan"
                        :class="{ 'cursor-grab': scale > 1, 'cursor-grabbing': isPanning }"
                    >
                        <img v-if="isImage(currentItem.url)" :src="currentItem.url" class="max-w-full max-h-[85vh] object-contain mx-auto select-none" draggable="false">
                        <vue-pdf-embed v-else-if="isPdf(currentItem.url)" :source="pdfSource" @rendered="pdfRendered" class="select-none" />
                    </div>
                    <div v-if="loading" class="absolute inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
                        <i class="fas fa-spinner fa-spin text-white text-5xl"></i>
                    </div>
                </div>

                <!-- Navigation Arrows -->
                <button v-if="items.length > 1" @click.stop="prev" class="absolute left-0 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-800 rounded-full w-12 h-12 flex items-center justify-center shadow-lg transition transform hover:scale-110 focus:outline-none">
                    <i class="fas fa-chevron-left text-2xl"></i>
                </button>
                <button v-if="items.length > 1" @click.stop="next" class="absolute right-0 top-1/2 -translate-y-1/2 bg-white/70 hover:bg-white text-gray-800 rounded-full w-12 h-12 flex items-center justify-center shadow-lg transition transform hover:scale-110 focus:outline-none">
                    <i class="fas fa-chevron-right text-2xl"></i>
                </button>
            </div>

            <!-- Zoom Controls -->
            <div class="absolute bottom-5 flex items-center space-x-2 bg-black bg-opacity-50 px-3 py-1.5 rounded-full z-10">
                <button @click.stop="zoomOut" class="control-btn"><i class="fas fa-search-minus"></i></button>
                <button @click.stop="resetZoom" class="control-btn text-sm font-bold">{{ Math.round(scale * 100) }}%</button>
                <button @click.stop="zoomIn" class="control-btn"><i class="fas fa-search-plus"></i></button>
            </div>

             <!-- Close Button -->
            <button @click="close" class="absolute top-3 right-3 bg-white text-gray-800 rounded-full w-8 h-8 flex items-center justify-center shadow-lg hover:bg-gray-200 transition transform hover:scale-110 z-20">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
    </div>
</template>

<script>
import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
import VuePdfEmbed from 'vue-pdf-embed';

export default {
    components: { VuePdfEmbed },
    props: {
        show: { type: Boolean, required: true },
        items: { type: Array, required: true, default: () => [] },
        startIndex: { type: Number, default: 0 }
    },
    emits: ['close'],
    setup(props, { emit }) {
        const currentIndex = ref(0);
        const loading = ref(false);
        const pdfSource = ref(null);

        // Zoom & Pan state
        const scale = ref(1);
        const position = ref({ x: 0, y: 0 });
        const isPanning = ref(false);
        const startPanPoint = ref({ x: 0, y: 0 });

        const currentItem = computed(() => props.items[currentIndex.value] || {});

        const transformStyle = computed(() => ({
            transform: `translate(${position.value.x}px, ${position.value.y}px) scale(${scale.value})`
        }));

        const getFileExtension = (url) => {
            if (!url) return '';
            return url.split('?')[0].split('.').pop().toLowerCase();
        };
        const isImage = (url) => ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(getFileExtension(url));
        const isPdf = (url) => getFileExtension(url) === 'pdf';

        const resetZoom = () => {
            scale.value = 1;
            position.value = { x: 0, y: 0 };
        };

        const loadPdf = async () => {
            if (!isPdf(currentItem.value.url)) return;
            loading.value = true;
            pdfSource.value = null;
            try {
                const response = await fetch(currentItem.value.url);
                if (!response.ok) throw new Error('Network response was not ok');
                pdfSource.value = await response.arrayBuffer();
            } catch (error) {
                console.error("Error loading PDF:", error);
            }
        };

        const pdfRendered = () => {
            loading.value = false;
        };

        watch(() => props.show, (newVal) => {
            if (newVal) {
                currentIndex.value = props.startIndex;
                resetZoom();
            }
        });

        watch(currentItem, (newItem) => {
            resetZoom();
            if (isPdf(newItem.url)) {
                loadPdf();
            } else {
                loading.value = false;
                pdfSource.value = null;
            }
        }, { immediate: true });

        const close = () => emit('close');
        const next = () => { currentIndex.value = (currentIndex.value + 1) % props.items.length; };
        const prev = () => { currentIndex.value = (currentIndex.value - 1 + props.items.length) % props.items.length; };

        // Zoom methods
        const zoomIn = () => scale.value += 0.1;
        const zoomOut = () => scale.value = Math.max(0.2, scale.value - 0.1);

        // Pan methods
        const startPan = (e) => {
            if (scale.value <= 1) return;
            e.preventDefault();
            isPanning.value = true;
            startPanPoint.value = { x: e.clientX - position.value.x, y: e.clientY - position.value.y };
            window.addEventListener('mousemove', pan);
            window.addEventListener('mouseup', endPan);
        };
        const pan = (e) => {
            if (!isPanning.value) return;
            position.value = {
                x: e.clientX - startPanPoint.value.x,
                y: e.clientY - startPanPoint.value.y
            };
        };
        const endPan = () => {
            isPanning.value = false;
            window.removeEventListener('mousemove', pan);
            window.removeEventListener('mouseup', endPan);
        };

        // Wheel to zoom
        const handleWheel = (e) => {
            if (e.deltaY < 0) zoomIn();
            else zoomOut();
        };

        onUnmounted(() => {
            window.removeEventListener('mousemove', pan);
            window.removeEventListener('mouseup', endPan);
        });

        return {
            currentIndex, loading, pdfSource, scale, position, isPanning,
            currentItem, transformStyle,
            isImage, isPdf, pdfRendered,
            close, next, prev,
            zoomIn, zoomOut, resetZoom,
            startPan, handleWheel
        };
    }
}
</script>

<style scoped lang="postcss">
.control-btn {
    @apply w-10 h-10 text-white rounded-full flex items-center justify-center hover:bg-white/20 transition-all;
}
.cursor-grab { cursor: grab; }
.cursor-grabbing { cursor: grabbing; }
.animate-fadeIn { animation: fadeIn 0.2s ease-out forwards; }
.animate-zoomIn { animation: zoomIn 0.2s ease-out forwards; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes zoomIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>
