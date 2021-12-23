<template>
  <nav>
    <ul class="pagination">
      <li class="page-item">
        <a class="page-link" href="#" @click.prevent="prev">Previous</a>
      </li>
      <li class="page-item">
        <a class="page-link" href="#" @click.prevent="next">Next</a>
      </li>
    </ul>
  </nav>
</template>

<script>
import { ref } from "vue"

export default {
  name: "Paginator",
  emits: [
      'page-changed'
  ],
  props: {
    lastPage: Number
  },
  setup(props, context) {
    const page = ref(1)

    const next = async () => {
      if (page.value === props.lastPage) return;

      page.value++
      context.emit('page-changed', page.value)
    }

    const prev = async () => {
      if (page.value === 1) return;

      page.value--
      context.emit('page-changed', page.value)
    }

    return {
      next,
      prev,
    }
  }
}
</script>

<style scoped>

</style>
