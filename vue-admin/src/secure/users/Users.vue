<template>
  <h2>Users</h2>

  <router-link to="/users/create" v-if="authenticatedUser.canEdit('users')">Add</router-link>

  <div class="table-responsive">
    <table class="table table-striped table-sm">
      <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="user in users" :key="user.id">
        <td>{{ user.id }}</td>
        <td>{{ user.first_name }} {{ user.last_name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user?.role?.name }}</td>
        <td>
          <div class="btn-group mr-2" v-if="authenticatedUser.canEdit('users')">
            <router-link :to="`/users/${user.id}/edit`" class="btn btn-sm btn-outline-secondary">Edit</router-link>
            <a href="#" class="btn btn-sm btn-outline-secondary" @click.prevent="del(user.id)">Delete</a>
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
  name: "Users",
  components: {
    Paginator
  },
  setup() {
    const users = ref([])
    const lastPage = ref(0)
    const store = useStore()
    const authenticatedUser = computed(() => store.state.User.user)

    const load = async (page = 1) => {
      const response = await axios.get(`users?page=${page}`)

      users.value = response.data.data
      lastPage.value = response.data.meta.last_page
    }

    onMounted(load)

    const del = async (id: number) => {
      if (confirm('Are you sure you want to delete this record?')) {
        await axios.delete(`users/${id}`);

        // remove users from the current users var
        users.value = users.value.filter((e: Entity) => e.id !== id)
      }
    }

    return {
      users,
      authenticatedUser,
      lastPage,
      del,
      load
    }
  }
}
</script>
