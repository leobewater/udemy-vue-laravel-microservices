<template>
  <form @submit.prevent="submit">
    <div class="form-group">
      <label for="title" class="sr-only">Title</label>
      <input type="text" id="title" class="form-control" placeholder="Product Title" required v-model="title">
    </div>
    <div class="form-group">
      <label for="description" class="sr-only">Description</label>
      <textarea id="description" class="form-control" required v-model="description"></textarea>
    </div>
    <div class="form-group">
      <label for="image" class="sr-only">Image</label>
      <div class="input-group-append">
        <input type="text" id="image" class="form-control" required v-model="image">
        <image-upload @file-uploaded="image = $event"></image-upload>
      </div>
    </div>
    <div class="form-group">
      <label for="price" class="sr-only">Price</label>
      <input type="number" id="price" class="form-control" required v-model="price">
    </div>

    <button class="btn btn-outline-secondary">Save</button>
  </form>
</template>

<script lang="ts">
import {ref, onMounted} from 'vue'
import axios from 'axios'
import {useRoute, useRouter} from "vue-router"
import ImageUpload from "@/secure/components/ImageUpload.vue";
import {Product} from "@/classes/product";

export default {
  name: "ProductsEdit",
  components: {ImageUpload},
  setup() {
    const title = ref('')
    const image = ref('')
    const description = ref('')
    const price = ref(0)
    const router = useRouter()
    const {params} = useRoute()

    onMounted(async() => {
      const response = await axios.get(`products/${params.id}`)

      const product: Product = response.data.data
      title.value = product.title
      description.value = product.description
      image.value = product.image
      price.value = product.price
    })

    const submit = async() => {
      const response = await axios.put(`products/${params.id}`, {
        title: title.value,
        image: image.value,
        description: description.value,
        price: price.value,
      })

      //console.log(response)
      await router.push('/products')
    }

    return {
      title,
      image,
      description,
      price,
      submit
    }
  }
}
</script>

<style scoped>

</style>
