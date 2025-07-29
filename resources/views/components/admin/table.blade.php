@props(['headers' => []])

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Slider Bar Styles */
    .slider-bar-modern {
        height: 10px;
        background: rgba(224, 231, 239, 0.6);
        backdrop-filter: blur(8px);
        border-radius: 9999px;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06), 
                    0 1px 2px rgba(255, 255, 255, 0.2);
        transition: all 0.3s;
    }
    .slider-bar-modern:hover {
        background: rgba(224, 231, 239, 0.8);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.08), 
                    0 2px 4px rgba(255, 255, 255, 0.3);
    }
    .slider-progress-modern {
        background: linear-gradient(90deg, #10b981 0%, #06b6d4 100%);
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        transition: width 0.2s, background 0.3s;
        border-radius: 9999px;
        position: relative;
        overflow: hidden;
    }
    .slider-progress-modern::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 50%;
        height: 100%;
        background: linear-gradient(90deg, 
                    rgba(255, 255, 255, 0) 0%, 
                    rgba(255, 255, 255, 0.3) 50%, 
                    rgba(255, 255, 255, 0) 100%);
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 200%; }
    }
    
    .slider-arrow-modern {
        width: 44px; 
        height: 44px;
        background: rgba(16, 185, 129, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        display: flex; 
        align-items: center; 
        justify-content: center;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2),
                    0 2px 4px rgba(6, 182, 212, 0.1),
                    inset 0 -2px 0 rgba(0, 0, 0, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        font-size: 1.5rem;
        color: #fff;
        border: none;
        cursor: pointer;
        position: relative;
        z-index: 1;
        overflow: hidden;
    }
    
    .slider-arrow-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 60%);
        z-index: -1;
    }
    
    .slider-arrow-modern:hover {
        transform: translateY(-2px) scale(1.05);
        background: rgba(6, 182, 212, 0.9);
        box-shadow: 0 8px 20px rgba(6, 182, 212, 0.3),
                    0 4px 8px rgba(16, 185, 129, 0.2),
                    inset 0 -2px 0 rgba(0, 0, 0, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }
    
    .slider-arrow-modern:active {
        transform: translateY(1px) scale(0.98);
        box-shadow: 0 2px 8px rgba(6, 182, 212, 0.2),
                    inset 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .slider-wrapper-modern {
        padding-left: 24px;
        padding-right: 24px;
        overflow: visible;
        position: relative;
    }
    
    /* Button Pulse Effect */
    .btn-pulse {
        position: relative;
    }
    
    .btn-pulse::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: inherit;
        background: inherit;
        opacity: 0;
        z-index: -1;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.7; }
        70% { transform: scale(1.15); opacity: 0; }
        100% { transform: scale(1.15); opacity: 0; }
    }
    
    /* Modern Table Styles */
    .modern-table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    
    .modern-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
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
    
    .modern-table th:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 0;
        height: 3px;
        background: linear-gradient(90deg, #10b981 0%, #06b6d4 100%);
        transition: width 0.3s ease;
    }
    
    .modern-table th:hover:after {
        width: 100%;
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
    
    /* Action Buttons in Table */
    .btn-table-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08),
                    inset 0 -1px 0 rgba(0, 0, 0, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    
    .btn-table-action::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
                    rgba(255, 255, 255, 0) 0%, 
                    rgba(255, 255, 255, 0.2) 50%, 
                    rgba(255, 255, 255, 0) 100%);
        transition: all 0.6s;
    }
    
    .btn-table-action:hover::before {
        left: 100%;
    }
    
    .btn-table-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1),
                    inset 0 -1px 0 rgba(0, 0, 0, 0.1),
                    inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }
    
    .btn-table-action:active {
        transform: translateY(1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08),
                    inset 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
        color: white;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: white;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    
    .btn-info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
</style>

<div
    x-data="{
        scrollEl: null,
        progressBar: null,
        isDragging: false,
        dragStartX: 0,
        scrollStart: 0,
        updateBar() {
            if (this.scrollEl && this.progressBar) {
                const percent = (this.scrollEl.scrollLeft / (this.scrollEl.scrollWidth - this.scrollEl.clientWidth)) * 100;
                this.progressBar.style.width = (isNaN(percent) ? 0 : percent) + '%';
            }
        },
        drag(e) {
            if (!this.isDragging) return;
            const bar = this.$refs.progressBar;
            const barRect = bar.parentElement.getBoundingClientRect();
            const percent = Math.min(Math.max((e.clientX - barRect.left) / barRect.width, 0), 1);
            if (this.scrollEl) {
                this.scrollEl.scrollLeft = percent * (this.scrollEl.scrollWidth - this.scrollEl.clientWidth);
                this.updateBar();
            }
        },
        startDrag(e) {
            this.isDragging = true;
            document.body.style.userSelect = 'none';
        },
        stopDrag() {
            this.isDragging = false;
            document.body.style.userSelect = '';
        },
        clickBar(e) {
            // Geser tabel ke posisi klik
            const bar = this.$refs.progressBar;
            const barRect = bar.parentElement.getBoundingClientRect();
            const percent = Math.min(Math.max((e.clientX - barRect.left) / barRect.width, 0), 1);
            if (this.scrollEl) {
                this.scrollEl.scrollLeft = percent * (this.scrollEl.scrollWidth - this.scrollEl.clientWidth);
                this.updateBar();
            }
        }
    }"
    x-init="scrollEl = $refs.tableScroll; progressBar = $refs.progressBar; updateBar(); scrollEl.addEventListener('scroll', updateBar);"
    @mousemove.window="drag($event)"
    @mouseup.window="stopDrag()"
    class="relative"
>
    <!-- Slider Bar & Arrow Button di Atas Tabel -->
    <div class="slider-wrapper-modern flex items-center mb-4 select-none gap-2">
        <button type="button" @click="scrollEl.scrollBy({ left: -200, behavior: 'smooth' }); updateBar()" class="slider-arrow-modern btn-pulse mr-2" aria-label="Geser Kiri">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div class="flex-1 relative slider-bar-modern mx-2 cursor-pointer"
            @mousedown="startDrag($event)"
            @click="clickBar($event)"
        >
            <div x-ref="progressBar" class="absolute top-0 left-0 h-full slider-progress-modern rounded transition-all duration-300" style="width:0%"></div>
        </div>
        <button type="button" @click="scrollEl.scrollBy({ left: 200, behavior: 'smooth' }); updateBar()" class="slider-arrow-modern btn-pulse ml-2" aria-label="Geser Kanan">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>
    <div class="overflow-x-auto hide-scrollbar rounded-lg" x-ref="tableScroll" @scroll="updateBar()">
        <table class="modern-table">
            <thead>
                <tr>
                    @foreach($headers as $header)
                    <th scope="col">
                        {{ $header }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div> 