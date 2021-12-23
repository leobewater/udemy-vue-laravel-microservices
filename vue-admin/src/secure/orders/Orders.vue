<template>
  <h2>Orders</h2>

  <div class="d-flex justify-content-bewteen flex-wrap flex-md-nowrap align-items-center pt-3 pb-3 pb-2 border-bottom">
    <div class="btn-toolbar mb-2 mb-md-0">
      <a href="#" class="btn btn-sm btn-outline-secondary" @click="exportFile">Export</a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-striped table-sm">
      <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Total</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="order in orders" :key="order.id">
        <td>{{ order.id }}</td>
        <td>{{ order.first_name }} {{ order.last_name }}</td>
        <td>{{ order.email }}</td>
        <td>{{ order.total }}</td>
        <td>
          <router-link :to="`/orders/${order.id}`" class="btn btn-sm btn-outline-secondary">View</router-link>
          <div class="btn-group mr-2" v-if="authenticatedUser.canEdit('orders')">
          </div>
        </td>
      </tr>
      </tbody>
    </table>
  </div>

  <Paginator :last-page="lastPage" @page-changed="load($event)"/>

</template>

<script lang="ts">
import {ref, onMounted, computed} from 'vue'
import axios from 'axios'
import {Entity} from "@/interfaces/entity"
import Paginator from "@/secure/components/Paginator.vue"
import {useStore} from "vuex";

export default {
  name: "Orders",
  components: {
    Paginator
  },
  setup() {
    const orders = ref([])
    const lastPage = ref(0)
    const store = useStore()
    const authenticatedUser = computed(() => store.state.User.user)

    const load = async (page = 1) => {
      const response = await axios.get(`orders?page=${page}`)

      orders.value = response.data.data
      lastPage.value = response.data.meta.last_page
    }

    onMounted(load)

    const exportFile = async () => {
      const response = await axios.get(`export`, {
        responseType: 'blob'
      })

      const blob = new Blob([response.data], {type:'text/csv'})
      const downloadUrl = window.URL.createObjectURL(response.data)
      const link = document.createElement('a')
      link.href = downloadUrl
      link.download = 'orders.csv'
      link.click()
    }

    return {
      orders,
      lastPage,
      load,
      exportFile,
      authenticatedUser
    }
  }
}
</script>
