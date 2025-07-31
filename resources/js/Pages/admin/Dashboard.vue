<template>
    <div class="dashboard-container">
        <!-- SVG Defs for Gradients -->
        <svg style="width:0;height:0;position:absolute;" aria-hidden="true" focusable="false">
            <defs>
                <linearGradient id="progress-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#84fab0" />
                <stop offset="100%" stop-color="#8fd3f4" />
                </linearGradient>
            </defs>
        </svg>

        <!-- Simple Header -->
        <div class="dashboard-header">
            <h1 class="header-title">Dashboard</h1>
            <p class="header-subtitle">Selamat datang kembali, berikut adalah ringkasan sistem saat ini.</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card" @click="navigateTo('/admin/santri')">
                <div class="stat-icon-wrapper" style="--color1: #6a82fb; --color2: #fc5c7d;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Santri</div>
                    <div class="stat-number">{{ animatedStats.totalSantri }}</div>
                </div>
            </div>

            <div class="stat-card" @click="navigateTo('/admin/santri?filter=trashed')">
                 <div class="stat-icon-wrapper" style="--color1: #ff8177; --color2: #ff867a;">
                    <i class="fas fa-user-slash"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Santri Dihapus</div>
                    <div class="stat-number">{{ animatedStats.totalSantriTrashed }}</div>
                </div>
            </div>

            <div class="stat-card" @click="navigateTo('/admin/pembayaran')">
                <div class="stat-icon-wrapper" style="--color1: #84fab0; --color2: #8fd3f4;">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Pembayaran Valid</div>
                    <div class="stat-number">{{ animatedStats.totalPembayaran }}</div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-section">
            <div class="chart-container">
                 <div class="chart-header">
                    <h3><i class="fas fa-user-graduate"></i> Status Tes Santri</h3>
                </div>
                <div class="chart-wrapper">
                    <Doughnut v-if="chartDataTes" :data="chartDataTes" :options="chartOptionsTes" />
                </div>
            </div>
            <div class="chart-container">
                 <div class="chart-header">
                    <h3><i class="fas fa-money-check-alt"></i> Status Pembayaran</h3>
                </div>
                <div class="chart-wrapper">
                    <Bar v-if="chartDataPembayaran" :data="chartDataPembayaran" :options="chartOptionsPembayaran" />
                </div>
            </div>
            <div class="chart-container">
                <div class="chart-header">
                    <h3><i class="fas fa-history"></i> Aktivitas Terbaru</h3>
                </div>
                <div class="activities-list">
                    <div v-for="activity in recentActivities" :key="activity.id" class="activity-item">
                        <div class="activity-icon" :class="activity.type">
                            <i :class="activity.icon"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title" v-html="activity.title"></div>
                            <div class="activity-time">{{ activity.time }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chart-container info">
                <div class="chart-header">
                    <h3><i class="fas fa-bullhorn"></i> Info Gelombang</h3>
                </div>
                <div v-if="stats.gelombangAktif" class="gelombang-info">
                    <div class="gelombang-status" :class="gelombangColor(stats.gelombangAktif.status)">
                        {{ stats.gelombangAktif.status }}
                    </div>
                    <div class="gelombang-nama">{{ stats.gelombangAktif.nama }}</div>
                    <div class="gelombang-detail">
                        <span>Biaya Admin:</span>
                        <strong>Rp {{ formatNumber(stats.gelombangAktif.biaya_administrasi) }}</strong>
                    </div>
                     <div class="gelombang-detail">
                        <span>Daftar Ulang:</span>
                        <strong>Rp {{ formatNumber(stats.gelombangAktif.biaya_daftar_ulang) }}</strong>
                    </div>
                </div>
                <div v-else class="text-danger">Belum ada gelombang aktif.</div>
            </div>
        </div>
    </div>
</template>

<script>
import { Doughnut, Pie, Bar } from 'vue-chartjs'
import { Chart, ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from 'chart.js'
Chart.register(ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend)

export default {
    components: { Doughnut, Pie, Bar },
    data() {
        return {
            stats: {
                totalSantri: 0,
                totalSantriTrashed: 0,
                totalPembayaran: 0,
                totalPembayaranDiverifikasi: 0,
                totalPembayaranBelumVerif: 0,
                totalPembayaranBelumBayar: 0,
                totalUploadBukti: 0,
                totalBelumUploadBukti: 0,
                statusTes: { Lulus: 0, 'Tidak Lulus': 0, 'Menunggu Tes': 0 },
                gelombangAktif: null
            },
            animatedStats: {
                totalSantri: 0,
                totalSantriTrashed: 0,
                totalPembayaran: 0,
            },
            recentActivities: [
                { id: 1, type: 'success', icon: 'fas fa-user-plus', title: 'Santri baru, <strong>Ahmad Zilong</strong>, telah mendaftar.', time: '2 menit yang lalu' },
                { id: 2, type: 'info', icon: 'fas fa-file-invoice-dollar', title: '<strong>Siti Sun</strong> mengupload bukti administrasi.', time: '15 menit yang lalu' },
                { id: 3, type: 'warning', icon: 'fas fa-user-edit', title: 'Data <strong>Ucup Surucup</strong> telah diperbarui.', time: '1 jam yang lalu' },
                { id: 4, type: 'success', icon: 'fas fa-user-plus', title: 'Santri baru, <strong>Bambang Gentolet</strong>, telah mendaftar.', time: '3 jam yang lalu' },
                { id: 5, type: 'info', icon: 'fas fa-file-invoice-dollar', title: '<strong>Paijo</strong> mengupload bukti daftar ulang.', time: '5 jam yang lalu' },
            ],
            chartDataTes: null,
            chartOptionsTes: {},
            chartDataPembayaran: null,
            chartOptionsPembayaran: {},
        }
    },
    mounted() {
        this.fetchData()
        this.startAutoRefresh()
    },
    computed: {
        progressUpload() {
            if (!this.stats.totalPembayaran) return 0;
            return Math.round((this.stats.totalUploadBukti / this.stats.totalPembayaran) * 100);
        }
    },
    methods: {
        async fetchData() {
            try {
                const response = await axios.get('/api/admin/dashboard')
                this.stats = response.data
                this.animateCounters()
                this.prepareCharts()
            } catch (error) {
                console.error('Error fetching dashboard data:', error)
            }
        },
        animateCounters() {
            this.animateCounter('totalSantri', this.stats.totalSantri)
            this.animateCounter('totalSantriTrashed', this.stats.totalSantriTrashed)
            this.animateCounter('totalPembayaran', this.stats.totalUploadBukti)
        },
        animateCounter(property, target) {
            const duration = 1500
            const steps = 50
            const increment = target / steps
            let current = 0
            const timer = setInterval(() => {
                current += increment
                if (current >= target) {
                    current = target
                    clearInterval(timer)
                }
                this.animatedStats[property] = Math.floor(current)
            }, duration / steps)
        },
        startAutoRefresh() {
            setInterval(() => {
                this.fetchData()
            }, 300000) // Refresh every 5 minutes
        },
        navigateTo(path) {
            window.location.href = path
        },
        prepareCharts() {
            this.chartDataTes = {
                labels: ['Lulus', 'Tidak Lulus', 'Menunggu Tes'],
                datasets: [{
                    data: [this.stats.statusTes.Lulus, this.stats.statusTes['Tidak Lulus'], this.stats.statusTes['Menunggu Tes']],
                    backgroundColor: ['#84fab0', '#ff8177', '#fccb90'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            }
            this.chartOptionsTes = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, boxWidth: 8, font: { size: 13 } } } },
                animation: { animateRotate: true, animateScale: true },
                cutout: '70%'
            }

            this.chartDataPembayaran = {
                labels: ['Diverifikasi', 'Belum Verif', 'Belum Bayar'],
                datasets: [{
                    label: 'Pembayaran',
                    data: [this.stats.totalPembayaranDiverifikasi, this.stats.totalPembayaranBelumVerif, this.stats.totalPembayaranBelumBayar],
                    backgroundColor: ['#84fab0', '#fccb90', '#ff8177'],
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 25,
                }]
            }
            this.chartOptionsPembayaran = {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.03)' }, ticks: { font: { size: 13 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 13 } } }
                },
                animation: { duration: 1500 }
            }
        },
        gelombangColor(status) {
            switch (status) {
                case 'Aktif': return 'success';
                case 'Kadaluarsa': return 'danger';
                case 'Belum Dimulai': return 'warning';
                default: return 'secondary';
            }
        },
        formatNumber(num) {
            return num ? num.toLocaleString('id-ID') : '0';
        }
    }
}
</script>

<style scoped>
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
    background-color: #f7f9fc;
    font-family: 'Inter', sans-serif;
}

/* Header */
.dashboard-header {
    margin-bottom: 2.5rem;
}

.header-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.header-subtitle {
    font-size: 1rem;
    color: #7f8c8d;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

.stat-card {
    background: #ffffff;
    border-radius: 1rem;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
    cursor: pointer;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
}

.stat-icon-wrapper {
    width: 50px;
    height: 50px;
    flex-shrink: 0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
    background: linear-gradient(135deg, var(--color1, #6a82fb), var(--color2, #fc5c7d));
}

.stat-content {
    text-align: left;
}

.stat-label {
    font-size: 0.9rem;
    font-weight: 500;
    color: #7f8c8d;
    margin-bottom: 0.25rem;
}

.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2c3e50;
}

/* Charts Section */
.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.chart-container {
    background: #ffffff;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    min-height: 350px;
    display: flex;
    flex-direction: column;
}

.chart-header {
    margin-bottom: 1rem;
}

.chart-header h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.chart-wrapper {
    position: relative;
    flex-grow: 1;
    width: 100%;
}

.text-danger {
    color: #e74c3c;
    text-align: center;
    margin-top: 2rem;
}

/* Activities List */
.activities-list {
    display: flex;
    flex-direction: column;
    gap: 1.1rem;
    overflow-y: auto;
    flex-grow: 1;
    margin-right: -10px;
    padding-right: 10px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.activity-icon {
    width: 38px;
    height: 38px;
    flex-shrink: 0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.9rem;
}

.activity-icon.success { background: #2ecc71; }
.activity-icon.info { background: #3498db; }
.activity-icon.warning { background: #f39c12; }

.activity-content {
    flex-grow: 1;
}

.activity-title {
    font-size: 0.9rem;
    font-weight: 400;
    color: #34495e;
    line-height: 1.4;
    margin-bottom: 0.15rem;
}

.activity-title strong {
    font-weight: 600;
}

.activity-time {
    font-size: 0.8rem;
    color: #95a5a6;
}

.activities-list::-webkit-scrollbar {
  width: 4px;
}
.activities-list::-webkit-scrollbar-track {
  background: transparent;
}
.activities-list::-webkit-scrollbar-thumb {
  background: #e0e0e0;
  border-radius: 10px;
}
.activities-list::-webkit-scrollbar-thumb:hover {
  background: #bdc3c7;
}

/* Gelombang Info Card */
.chart-container.info {
    justify-content: center;
}

.gelombang-info {
    text-align: center;
}

.gelombang-status {
    display: inline-block;
    padding: 0.3rem 1rem;
    border-radius: 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #fff;
}

.gelombang-status.success { background-color: #2ecc71; }
.gelombang-status.danger { background-color: #e74c3c; }
.gelombang-status.warning { background-color: #f39c12; }
.gelombang-status.secondary { background-color: #95a5a6; }

.gelombang-nama {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.gelombang-detail {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #ecf0f1;
    font-size: 0.9rem;
}

.gelombang-detail:last-child {
    border-bottom: none;
}

.gelombang-detail span {
    color: #7f8c8d;
}

.gelombang-detail strong {
    color: #2c3e50;
    font-weight: 600;
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 1.5rem 1rem;
    }
    .stats-grid, .charts-section {
        grid-template-columns: 1fr;
    }
}
</style>
