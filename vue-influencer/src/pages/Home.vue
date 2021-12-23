<template>
  <Header/>

  <div class="album py-5 bg-light">
    <div class="container">

      <div class="row">

        <div class="col-md-12 mb-4" v-if="link">
          <div class="alert alert-info" role="alert">
            Link Generated: {{ link }}
          </div>
        </div>

        <div class="col-md-12 mb-4" v-if="error">
          <div class="alert alert-danger" role="alert">
            You should be logged in to generate a link
          </div>
        </div>

        <div class="col-md-12 mb-4 input-group">
          <input class="form-control" placeholder="Search" @keyup="search($event.target.value)"/>
          <div class="input-group-append" v-if="selected.length > 0">
            <button class="btn btn-info" @click="generate()">Generate Link</button>
          </div>
        </div>

        <div class="col-md-4" v-for="product in products" :key="product.id">
          <div class="card mb-4 shadow-sm" @click="select(product.id)" :class="{selected: isSelected(product.id)}">
            <img :src="product.image" height="200"/>
            <div class="card-body">
              <p class="card-text">{{ product.title }}</p>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">{{ product.price }}</small>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import Header from '@/components/Header'
import { ref, onMounted } from 'vue'
import axios from 'axios'

export default {
  name: "Home",
  components: {
    Header
  },
  setup() {
    const products = ref([])
    const selected = ref([])
    const link = ref('')
    const error = ref(false)

    const load = async (text = '') => {
      const { data } = await axios.get(`products?s=${text}`)

      products.value = data.data
    }

    const search = async (text) => {
      await load(text)
    }

    const isSelected = (id) => {
      return selected.value.some(s => s === id)
    }

    const select = (id) => {
      if( !isSelected(id)) {
        selected.value.push(id)
        return
      }

      selected.value = selected.value.filter(s => s !== id)
    }

    const generate = async() => {
      try {
        const { data } = await axios.post('links', {
          products: selected.value
        })

        link.value = `${process.env.VUE_APP_CHECKOUT}/${data.data.code}`
      } catch(e) {
        error.value = true
      }
    }

    onMounted(async () => {
      await load()
    })

    return {
      products,
      search,
      select,
      isSelected,
      selected,
      generate,
      link,
      error
    }
  }
}
</script>

<style scoped>
.card {
  cursor: pointer;
}

.card.selected {
  border: 4px solid darkcyan;
}
</style>
