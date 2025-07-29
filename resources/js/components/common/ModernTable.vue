<template>
    <div>
        <div v-if="isDesktop && showTips" class="flex items-center justify-between mb-2 text-sm text-blue-700 bg-blue-50 rounded px-3 py-2">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                <span>Tips: <b>Tekan Ctrl + Scroll</b> untuk menggeser tabel ke kanan/kiri.</span>
            </div>
            <button @click="dismissTips" class="text-blue-500 hover:text-blue-700 ml-4 p-1 rounded-full hover:bg-blue-100 transition-colors duration-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="overflow-x-auto table-container" @wheel="handleTableScroll">
        <div v-if="loading" class="text-center py-10 text-gray-400">
            <i class="fas fa-spinner fa-spin text-2xl"></i>
            <div>Memuat data...</div>
        </div>
        <div v-else>
            <table class="w-full modern-table">
                <thead>
                    <tr>
                        <th v-if="selectable" class="w-12 text-center">
                            <input type="checkbox" @change="toggleSelectAll" :checked="isAllSelected" :indeterminate="isPartiallySelected" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        </th>
                        <th v-for="(column, index) in displayColumns" :key="index"
                            class="whitespace-nowrap cursor-pointer select-none"
                            :style="{ textAlign: column.textAlign || 'left' }"
                            @click="handleSort(column)" >
                            {{ column.label }}
                            <span v-if="isSortable(column)">
                                <i v-if="sortKey === column.key && sortAsc" class="fas fa-sort-up ml-1"></i>
                                <i v-else-if="sortKey === column.key && !sortAsc" class="fas fa-sort-down ml-1"></i>
                                <i v-else class="fas fa-sort ml-1 text-gray-300"></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in sortedItems" :key="item.id || index"
                        :class="[
                            rowClickable && !isRowClickDisabled(item, index) ? 'row-clickable' : '',
                            'hover:bg-gray-50',
                            rowClass ? rowClass(item, index) : ''
                        ]"
                        @click="onRowClick(item, index, $event)"
                        @dblclick="onRowDblClick(item, index, $event)"
                        >
                        <td v-if="selectable" class="w-12 text-center align-middle">
                            <input type="checkbox" :checked="isSelected(item)" @change.stop="toggleSelect(item)" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50">
                        </td>
                        <td v-for="(column, colIndex) in displayColumns" :key="colIndex"
                            class="whitespace-nowrap align-middle"
                            :style="{ textAlign: column.textAlign || 'left' }">
                            <slot :name="column.key" :item="item" :value="item[column.key]" :index="index">
                                {{ item[column.key] }}
                            </slot>
                        </td>
                    </tr>
                    <tr v-if="sortedItems.length === 0">
                        <td :colspan="displayColumns.length + (selectable ? 1 : 0)" class="px-6 py-4 text-center text-gray-500 font-medium">
                            <div class="flex flex-col items-center justify-center py-12">
                                <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                                <p class="text-lg">Tidak ada data</p>
                                <p class="text-sm text-gray-400">Belum ada data yang tersedia.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ModernTable',
    props: {
        columns: {
            type: Array,
            required: true,
        },
        selectable: {
            type: Boolean,
            default: false,
        },
        items: {
            type: Array,
            required: true
        },
        loading: {
            type: Boolean,
            default: false
        },
        visibleColumns: {
            type: Array,
            default: null // tampilkan semua kalau null
        },
        sortableColumns: {
            type: Array,
            default: () => [] // array of key
        },
        rowClickable: {
            type: Boolean,
            default: true
        },
        rowClickDisabled: {
            type: Function,
            default: null // function(item, index) => boolean
        },
        rowClass: {
            type: Function,
            default: null // function(item, index) => string
        },
        modelValue: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            sortKey: '',
            sortAsc: true,
            isDesktop: true,
            showTips: true
        }
    },
    computed: {
        selected: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        },
        isAllSelected() {
            if (!this.items || this.items.length === 0 || !this.selectable) return false;
            return this.selected.length === this.items.length;
        },
        isPartiallySelected() {
            if (!this.items || this.items.length === 0 || !this.selectable) return false;
            return this.selected.length > 0 && this.selected.length < this.items.length;
        },
        displayColumns() {
            if (!this.visibleColumns) return this.columns;
            return this.columns.filter(col => this.visibleColumns.includes(col.key));
        },
        sortedItems() {
            if (!this.sortKey) return this.items;
            return [...this.items].sort((a, b) => {
                let valA = a[this.sortKey] || '';
                let valB = b[this.sortKey] || '';
                if (typeof valA === 'string') valA = valA.toLowerCase();
                if (typeof valB === 'string') valB = valB.toLowerCase();
                if (valA < valB) return this.sortAsc ? -1 : 1;
                if (valA > valB) return this.sortAsc ? 1 : -1;
                return 0;
            });
        }
    },
    mounted() {
        this.checkDevice();
        window.addEventListener('resize', this.checkDevice);
        this.checkTipsVisibility();
    },
    beforeUnmount() {
        window.removeEventListener('resize', this.checkDevice);
    },
    methods: {
        checkTipsVisibility() {
            const dismissedAt = localStorage.getItem('modernTableTipsDismissedAt');
            if (dismissedAt) {
                const oneWeekInMillis = 7 * 24 * 60 * 60 * 1000;
                const dismissedTimestamp = parseInt(dismissedAt, 10);
                if (new Date().getTime() - dismissedTimestamp < oneWeekInMillis) {
                    this.showTips = false;
                } else {
                    localStorage.removeItem('modernTableTipsDismissedAt');
                }
            }
        },
        dismissTips() {
            this.showTips = false;
            localStorage.setItem('modernTableTipsDismissedAt', new Date().getTime().toString());
        },
        checkDevice() {
            // Cek desktop dengan matchMedia (lebar minimal 1024px) dan bukan touchscreen
            this.isDesktop = window.matchMedia('(min-width: 1024px)').matches && !('ontouchstart' in window);
        },
        toggleSelectAll() {
            if (this.isAllSelected) {
                this.selected = [];
            } else {
                this.selected = [...this.items];
            }
        },
        toggleSelect(item) {
            const index = this.selected.findIndex(selectedItem => selectedItem.id === item.id);
            if (index > -1) {
                this.selected.splice(index, 1);
            } else {
                this.selected.push(item);
            }
            this.$emit('update:modelValue', this.selected);
        },
        isSelected(item) {
            return this.selected.some(selectedItem => selectedItem.id === item.id);
        },
        handleTableScroll(event) {
            const container = event.currentTarget;
            if (event.ctrlKey) {
                // Scroll horizontal jika Ctrl ditekan
            const scrollAmount = event.deltaX || event.deltaY || event.detail || event.wheelDelta;
            container.scrollLeft += scrollAmount * 5;
                event.preventDefault();
            }
            // Jika tidak Ctrl, biarkan scroll halaman biasa
        },
        handleSort(column) {
            if (!this.isSortable(column)) return;
            if (this.sortKey === column.key) {
                this.sortAsc = !this.sortAsc;
            } else {
                this.sortKey = column.key;
                this.sortAsc = true;
            }
            this.$emit('sort-changed', { key: this.sortKey, asc: this.sortAsc });
        },
        isSortable(column) {
            return this.sortableColumns.includes(column.key);
        },
        isRowClickDisabled(item, index) {
            return this.rowClickDisabled ? this.rowClickDisabled(item, index) : false;
        },
        onRowClick(item, index, event) {
            if (!this.rowClickable || this.isRowClickDisabled(item, index)) return;
            this.$emit('row-click', { item, index, event });
        },
        onRowDblClick(item, index, event) {
            if (!this.rowClickable || this.isRowClickDisabled(item, index)) return;
            this.$emit('row-dblclick', { item, index, event });
        }
    }
}
</script>

<style scoped>
.modern-table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    min-width: 1200px;
}

.table-container {
    position: relative;
    overflow-x: auto;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    max-width: 100%;
    cursor: default;
}

.table-container:active {
    cursor: default;
}

.table-container::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}

.table-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.modern-table thead {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.modern-table th,
.modern-table td {
    padding: 0.5rem 1rem; /* 8px 16px */
    border-bottom: 1px solid #e5e7eb; /* Cool Gray 200 */
    font-size: 0.875rem; /* 14px */
}

.modern-table th {
    padding: 16px 24px;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e2e8f0;
    position: relative;
    transition: all 0.2s;
}

.modern-table tbody tr {
    transition: all 0.2s ease;
}

.modern-table tbody tr:hover {
    background-color: #f8fafc;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.04);
}

.modern-table td {
    padding: 16px 24px;
    color: #334155;
    border-bottom: 1px solid #e2e8f0;
    transition: all 0.2s;
}

.modern-table tbody tr:last-child td {
    border-bottom: none;
}

.row-clickable {
    cursor: pointer;
    transition: background 0.2s;
}
.row-clickable:hover, .row-clickable:active {
    background: #f1f5f9 !important;
}
</style>
