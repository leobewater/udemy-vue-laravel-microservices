<template>
  <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="#">Company name</a>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">

    <nav class="my-2 my-md-0 mr-md-3">
      <router-link to="/profile" class="p-2 text-white">{{ user?.name }}</router-link>
      <a class="p-2 text-white" href="#" @click.prevent="logout">Sign out</a>
    </nav>

  </nav>
</template>

<script>
import { computed } from 'vue'
import { useRouter } from "vue-router"
import { useStore } from "vuex"
import axios from 'axios'

export default {
  name: "Nav",

  setup() {
    const router = useRouter()
    const store = useStore()
    const user = computed(() => store.state.User.user)

    const logout = async() => {
      await axios.post('logout', {})

      //localStorage.clear();
      router.push('/login')
    }

    return {
      user,
      logout
    }
  }
}
</script>
