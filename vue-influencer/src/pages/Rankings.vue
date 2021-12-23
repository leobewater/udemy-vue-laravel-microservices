<template>
  <div class="album py-5 bg-light">
    <div class="container">
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Revenue</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(ranking, i) in rankings" :key="ranking.id">
            <td>{{ i + 1 }}</td>
            <td>{{ ranking }}</td>
            <td>{{ ranking.revenue }}</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import axios from 'axios'

export default {
  name: "Rankings.vue",

  setup() {

    const rankings = ref([])

    onMounted(async () => {
      const { data } = await axios.get('rankings')

      rankings.value = data
    })

    return {
      rankings
    }
  }
}
</script>
