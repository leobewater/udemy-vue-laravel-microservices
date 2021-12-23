<template>
  <h2>Roles</h2>

  <router-link to="/roles/create" v-if="authenticatedUser.canEdit('roles')">Add</router-link>

  <div class="table-responsive">
    <table class="table table-striped table-sm">
      <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="role in roles" :key="role.id">
        <td>{{ role.id }}</td>
        <td>{{ role.name }}</td>
        <td>
          <div class="btn-group mr-2" v-if="authenticatedUser.canEdit('roles')">
          <router-link :to="`/roles/${role.id}/edit`" class="btn btn-sm btn-outline-secondary">Edit</router-link>
          <a href="#" class="btn btn-sm btn-outline-secondary" @click.prevent="del(role.id)">Delete</a>
          </div>
        </td>
      </tr>
      </tbody>
    </table>
  </div>

</template>

<script lang="ts">
import {ref, onMounted, computed} from 'vue'
import axios from 'axios'
import {Entity} from "@/interfaces/entity"
import {useStore} from "vuex";

export default {
  name: "Roles",
  setup() {
    const roles = ref([])
    const store = useStore()
    const authenticatedUser = computed(() => store.state.User.user )

    const load = async () => {
      const response = await axios.get(`roles`)

      roles.value = response.data.data
    }

    const del = async (id: number) => {
      if (confirm('Are you sure you want to delete this record?')) {
        await axios.delete(`roles/${id}`);

        roles.value = roles.value.filter((e: Entity) => e.id !== id)
      }
    }

    onMounted(load)

    return {
      roles,
      del,
      authenticatedUser
    }
  }
}
</script>
