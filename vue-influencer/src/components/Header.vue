<template>
  <section class="jumbotron text-center">
    <div class="container">
      <h1>{{ title() }}</h1>
      <p class="lead text-muted">{{ description() }}</p>
      <p v-if="!isAuthenticated">
        <router-link to="/login" class="btn btn-primary my-2">Login</router-link>
        <router-link to="/register" class="btn btn-secondary my-2">Register</router-link>
      </p>
      <p v-if="isAuthenticated">
        <router-link to="/stats" class="btn btn-primary my-2">Stats</router-link>
      </p>
    </div>
  </section>
</template>

<script>
import { computed } from 'vue'
import { useStore } from "vuex"

export default {
  name: "Header",
  setup() {
    const store = useStore()
    const user = computed(() => store.state.user)

    const isAuthenticated = () => {
      return user.value?.id
    }

    const title = () => {
      if (isAuthenticated()) {
        return `You earned: $${user.value.revenue}`
      } else {
        return 'Welcome'
      }
    }

    const description = () => {
      if (isAuthenticated()) {
        return 'You have earned in total!'
      }

      return 'Share links and earn 10% of the product price!'
    }

    return {
      user,
      title,
      description,
      isAuthenticated
    }
  }
}
</script>
