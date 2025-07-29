<template>
  <div class="bg-white rounded-lg shadow-md p-4 md:p-6 max-w-4xl mx-auto">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-2xl md:text-3xl font-bold text-center text-teal-700 mb-6">
        Informasi Ujian
      </h1>

      <!-- Status Badge Component -->
      <StatusBadgeUjian :santri="santri" />

      <!-- Progress Overview Component -->
      <ProgressUjianOverview :progress="progressData" />

      <!-- Daftar Tes -->
      <div class="space-y-4">
        <TesLisanCard
          :tes-lisan="tesLisanData"
          @join-jitsi="handleJoinJitsi"
        />
        <TesTulisCard
          :tes-tulis="tesTulisData"
          @start-tes="handleStartTes"
        />
        <TesPsikotesCard
          :tes-psikotes="tesPsikotesData"
          @start-psikotes="handleStartPsikotes"
        />
        <TesKesehatanCard
          :tes-kesehatan="tesKesehatanData"
          @view-schedule="handleViewSchedule"
        />
      </div>

      <!-- Footer -->
      <div class="mt-8 text-center text-gray-500 text-sm">
        <p>© 2024 Pondok Pesantren. Semua hak dilindungi.</p>
      </div>
    </div>

    <!-- Modal Join Jitsi pakai teleport -->
    <teleport to="body">
      <div v-if="showJitsiModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-80 z-[9999] pointer-events-auto">
        <div class="bg-white w-screen h-screen relative flex flex-col justify-center items-center p-0 m-0 shadow-none rounded-none overflow-hidden">
          <h3 class="text-2xl font-semibold mb-4 mt-8">Tes Lisan - Jitsi Meet</h3>
          <div id="jitsi-container" class="w-full flex-1 flex justify-center items-center" style="height:80vh;"></div>
        </div>
      </div>
    </teleport>
  </div>
</template>

<script>
import StatusBadgeUjian from './components/StatusBadgeUjian.vue'
import ProgressUjianOverview from './components/ProgressUjianOverview.vue'
import TesLisanCard from './components/TesLisanCard.vue'
import TesTulisCard from './components/TesTulisCard.vue'
import TesPsikotesCard from './components/TesPsikotesCard.vue'
import TesKesehatanCard from './components/TesKesehatanCard.vue'
import axios from 'axios'

export default {
  name: 'UjianIndex',
  components: {
    StatusBadgeUjian,
    ProgressUjianOverview,
    TesLisanCard,
    TesTulisCard,
    TesPsikotesCard,
    TesKesehatanCard
  },
  props: {
    initialData: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      showJitsiModal: false,
      jitsiApi: null,
      santri: {
        status_tes: 'belum_dinilai',
        nama: 'Ahmad Santri',
        nis: '2024001'
      },
      progressData: {
        tes_lisan: 60,
        tes_tulis: 40,
        tes_psikotes: 20,
        tes_kesehatan: 0
      },
      tesLisanData: {
        bacaan_alquran: {
          nama: 'Tes Bacaan Al-Qur\'an',
          deskripsi: 'Live talaqqi via Jitsi Meet',
          status: 'pending'
        },
        hafalan: {
          nama: 'Tes Hafalan Al-Qur\'an (Juz 30)',
          deskripsi: 'Live talaqqi via Jitsi Meet',
          status: 'pending'
        },
        doa: {
          nama: 'Tes Hafalan Doa Harian',
          deskripsi: 'Live talaqqi dengan checklist',
          status: 'pending'
        }
      },
      tesTulisData: {
        status: 'belum_mulai',
        durasi: '120 menit',
        jumlah_soal: 50
      },
      tesPsikotesData: {
        status: 'belum_mulai',
        durasi: '90 menit',
        jenis: 'MBTI & DISC'
      },
      tesKesehatanData: {
        status: 'belum_dijadwalkan',
        tanggal: null,
        lokasi: 'Klinik Pesantren'
      }
    }
  },
  watch: {
    showJitsiModal(val) {
      if (val) {
        this.initJitsi()
      } else {
        this.disposeJitsi()
      }
    }
  },
  mounted() {
    // Load data dari props jika ada
    if (this.initialData.santri) {
      this.santri = { ...this.santri, ...this.initialData.santri }
    }
    if (this.initialData.progress) {
      this.progressData = { ...this.progressData, ...this.initialData.progress }
    }
    if (this.initialData.tesLisanData) {
      this.tesLisanData = { ...this.tesLisanData, ...this.initialData.tesLisanData }
    }
    if (this.initialData.tesTulisData) {
      this.tesTulisData = { ...this.tesTulisData, ...this.initialData.tesTulisData }
    }
    if (this.initialData.tesPsikotesData) {
      this.tesPsikotesData = { ...this.tesPsikotesData, ...this.initialData.tesPsikotesData }
    }
    if (this.initialData.tesKesehatanData) {
      this.tesKesehatanData = { ...this.tesKesehatanData, ...this.initialData.tesKesehatanData }
    }

    console.log('🎯 UjianIndex component loaded! Santri:', this.santri.nama)
    console.log('📊 Progress Data:', this.progressData)
  },
  methods: {
    handleJoinJitsi() {
      this.showJitsiModal = true
    },
    initJitsi() {
      if (window.JitsiMeetExternalAPI) {
        this.jitsiApi = new window.JitsiMeetExternalAPI('8x8.vc', {
          roomName: 'tes-lisan-pesantren',
          parentNode: document.getElementById('jitsi-container'),
          width: '100%',
          height: '100%',
          userInfo: {
            displayName: this.santri.nama,
            email: this.santri.email || ''
          }
        })
        // Contoh event: close modal saat user keluar meeting
        this.jitsiApi.addListener('readyToClose', () => {
          this.showJitsiModal = false
        })
      } else {
        // Load script Jitsi jika belum ada
        const script = document.createElement('script')
        script.src = 'https://8x8.vc/external_api.js'
        script.onload = () => this.initJitsi()
        document.body.appendChild(script)
      }
    },
    disposeJitsi() {
      if (this.jitsiApi) {
        this.jitsiApi.dispose()
        this.jitsiApi = null
      }
    },
    async handleStartTes(jenis) {
      console.log(`📝 Mulai tes ${jenis}`)

      try {
        // Call API untuk mulai tes
        const response = await fetch('/santri/ujian/start-tes', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            jenis_tes: jenis
          })
        })

        const data = await response.json()

        if (data.success) {
          console.log('✅ Tes berhasil dimulai:', data.message)
          // Update local state
          if (jenis === 'tulis') {
            this.tesTulisData.status = 'sedang_berlangsung'
          } else if (jenis === 'psikotes') {
            this.tesPsikotesData.status = 'sedang_berlangsung'
          }
        } else {
          console.error('❌ Gagal mulai tes:', data.message)
        }
      } catch (error) {
        console.error('❌ Error saat mulai tes:', error)
      }

      // Logic buat mulai tes
      this.$emit('start-tes', jenis)
    },

    async handleStartPsikotes() {
      console.log('🧠 Mulai tes psikotes')
      await this.handleStartTes('psikotes')
      this.$emit('start-psikotes')
    },

    async handleViewSchedule() {
      console.log('🏥 Lihat jadwal tes kesehatan')
      this.$emit('view-schedule')
    },

    // Method untuk update progress via API
    async updateProgress(jenisTes, progress) {
      try {
        const response = await axios.post('/santri/ujian/update-progress', {
          jenis_tes: jenisTes,
          progress: progress
        })

        if (response.data.success) {
          // Update local data
          this.progressData[jenisTes] = progress
          this.$toast.success('Progress berhasil diupdate! 🎉')
        }
      } catch (error) {
        console.error('Error updating progress:', error)
        this.$toast.error('Gagal update progress! 😅')
      }
    },

    // Method untuk join Jitsi Meet
    async joinJitsi(jenisTes) {
      try {
        const roomName = `ujian-${jenisTes}-${this.santri.id}`
        const response = await axios.post('/santri/ujian/join-jitsi', {
          jenis_tes: jenisTes,
          room_name: roomName
        })

        if (response.data.success) {
          // Buka Jitsi Meet di tab baru
          window.open(response.data.jitsi_url, '_blank')
          this.$toast.success('Jitsi Meet dibuka! 🎥')
        }
      } catch (error) {
        console.error('Error joining Jitsi:', error)
        this.$toast.error('Gagal join Jitsi Meet! 😅')
      }
    },

    // Method untuk mulai tes tulis
    startTesTulis(jenisTes) {
      this.tesTulisData[jenisTes].status = 'sedang_berlangsung'
      this.tesTulisData[jenisTes].waktu_mulai = new Date()
      this.$toast.success(`Tes ${this.tesTulisData[jenisTes].nama} dimulai! ⏰`)
    },

    // Method untuk selesai tes tulis
    finishTesTulis(jenisTes) {
      this.tesTulisData[jenisTes].status = 'completed'
      this.tesTulisData[jenisTes].waktu_selesai = new Date()
      this.progressData.tes_tulis += 33 // Increment progress
      this.$toast.success(`Tes ${this.tesTulisData[jenisTes].nama} selesai! 🎉`)
    }
  },
  beforeUnmount() {
    this.disposeJitsi()
  }
}
</script>

<style scoped>
/* Custom animations */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}

/* Hover effects */
.hover-lift {
  transition: transform 0.2s ease-in-out;
}

.hover-lift:hover {
  transform: translateY(-2px);
}
</style>
