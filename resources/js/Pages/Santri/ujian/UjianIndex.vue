<template>
  <div>
    <UjianIndex :initial-data="initialData" />
  </div>
</template>

<script>
import UjianIndex from '@/components/santri/ujian/UjianIndex.vue'

export default {
  name: 'UjianIndexPage',
  components: {
    UjianIndex
  },
  props: {
    santri: {
      type: Object,
      default: () => ({})
    },
    progress: {
      type: Object,
      default: () => ({})
    },
    tesLisanData: {
      type: Object,
      default: () => ({})
    },
    tesTulisData: {
      type: Object,
      default: () => ({})
    },
    tesPsikotesData: {
      type: Object,
      default: () => ({})
    },
    tesKesehatanData: {
      type: Object,
      default: () => ({})
    }
  },
  computed: {
    initialData() {
      return {
        santri: this.santri,
        progress: this.progress,
        tesLisanData: this.tesLisanData,
        tesTulisData: this.tesTulisData,
        tesPsikotesData: this.tesPsikotesData,
        tesKesehatanData: this.tesKesehatanData
      }
    }
  }
}
</script>
