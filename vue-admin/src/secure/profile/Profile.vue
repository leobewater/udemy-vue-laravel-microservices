<template>
  <form @submit.prevent="submitInfo">
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

    <button class="btn btn-outline-secondard">Save</button>
  </form>

  <h2 class="mt-4">Change Password</h2>
  <hr>
  <form @submit.prevent="submitPassword">
    <div class="form-group">
      <label for="password" class="sr-only">Password</label>
      <input type="password" id="password" class="form-control" required v-model="password">
    </div>

    <div class="form-group">
      <label for="password" class="sr-only">Password Confirm</label>
      <input type="password" id="password_confirm" class="form-control" required v-model="passwordConfirm">
    </div>

    <button class="btn btn-outline-secondard">Save</button>
  </form>
</template>

<script lang="ts">
import {ref, onMounted, computed} from 'vue'
import axios from 'axios'
import {User} from "@/classes/user"
import {useStore} from "vuex"

export default {
  name: "Profile",
  setup() {
    const firstName = ref('')
    const lastName = ref('')
    const email = ref('')
    const password = ref('')
    const passwordConfirm = ref('')
    const store = useStore()

    onMounted(async () => {
      // TODO - not working with TypeScript
      //const user: User = computed(() => store.state.User.user)
      const user = computed(() => store.state.User.user)
      if( user.value ) {
        firstName.value = user.value.first_name
        lastName.value = user.value.last_name
        email.value = user.value.email
      }
    })

    const submitInfo = async () => {
      const response = await axios.put(`users/info`, {
        first_name: firstName.value,
        last_name: lastName.value,
        email: email.value,
      })

      const u: User = response.data

      await store.dispatch('User/setUser', new User(
          u.id,
          u.first_name,
          u.last_name,
          u.email,
          u.role,
          u.permissions,
      ))
    }

    const submitPassword = async () => {
      const response = await axios.put(`users/info`, {
        password: password.value,
        password_confirm: passwordConfirm.value,
      })

      password.value = ''
      passwordConfirm.value = ''
    }

    return {
      firstName,
      lastName,
      email,
      password,
      passwordConfirm,
      submitInfo,
      submitPassword
    }
  }
}
</script>
