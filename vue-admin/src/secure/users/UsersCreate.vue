<template>
  <form @submit.prevent="submit">
    <div class="form-group">
      <label for="first_name" class="sr-only">First Name</label>
      <input type="text" id="first_name" class="form-control" placeholder="First Name" required v-model="firstName">
    </div>
    <div class="form-group">
      <label for="last_name" class="sr-only">Last Name</label>
      <input type="text" id="last_name" class="form-control" placeholder="Last Name" required v-model="lastName">
    </div>
    <div class="form-group">
      <label for="email" class="sr-only">Email address</label>
      <input type="email" id="email" class="form-control" placeholder="Email address" required v-model="email">
    </div>
    <div class="form-group">
      <label for="password" class="sr-only">Password</label>
      <input type="password" id="password" class="form-control" placeholder="Password" required v-model="password">
    </div>

    <div class="form-group">
      <label for="role_id" class="sr-only">Role</label>
      <select name="role_id" class="form-control" v-model="roleId">
        <option value="0">Select Role</option>
        <option v-for="role in roles" :key="role.id" :value="role.id">{{role.name}}</option>
      </select>
    </div>

    <button class="btn btn-outline-secondary">Save</button>
  </form>
</template>

<script>
import {ref, onMounted} from 'vue'
import axios from 'axios'
import { useRouter } from "vue-router"

export default {
  name: "UsersCreate",

  setup() {
    const firstName = ref()
    const lastName = ref()
    const email = ref()
    const password = ref()
    const roleId= ref(0)
    const roles = ref([])
    const router = useRouter()

    onMounted(async() => {
      const response = await axios.get('roles')

      roles.value = response.data.data
    });

    const submit = async() => {
      const response = await axios.post('users', {
        first_name: firstName.value,
        last_name: lastName.value,
        email: email.value,
        password: password.value,
        role_id: roleId.value
      })

      //console.log(response)
      await router.push('/users')
    }

    return {
      firstName,
      lastName,
      email,
      password,
      roleId,
      roles,
      submit
    }
  }
}
</script>

<style scoped>

</style>
