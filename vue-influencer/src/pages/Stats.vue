<template>
  <div class="album py-5 bg-light">
    <div class="container">
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
          <tr>
            <th>Link</th>
            <th>Users</th>
            <th>Revenue</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="order in orders" :key="order.id">
            <td>{{ link(order.code) }}</td>
            <td>{{ order.count }}</td>
            <td>{{ order.revenue }}</td>
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
  name: "Stats",

  setup() {

    const orders = ref([])

    const link = (code) => {
      return `${process.env.VUE_APP_CHECKOUT}/${code}`
    }

    onMounted(async () => {
      const { data } = await axios.get('stats')

      orders.value = data
    })

    return {
      link,
      orders
    }
  }
}
</script>
