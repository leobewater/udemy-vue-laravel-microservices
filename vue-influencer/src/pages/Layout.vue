<template>
  <Nav />

  <main role="main">
    <router-view/>
  </main>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRouter } from "vue-router"
import axios from 'axios'
import Nav from '@/components/Nav'
import {useStore} from "vuex"
import { User } from "@/classes/user"

export default {
  name: "Layout.vue",
  components: {
    Nav
  },
  setup() {
    const router = useRouter()
    const user = ref({})
    const store = useStore()

    onMounted(async () => {
      try {
        const response = await axios.get('user')

        // assign the data to the user class
        user.value = new User(response.data.data)

        await store.dispatch('setUser', user.value)

      } catch (e) {
        await router.push('/login')
      }
    })

  }
}
</script>
