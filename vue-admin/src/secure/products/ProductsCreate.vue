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
import {ref} from 'vue'
import axios from 'axios'
import { useRouter } from "vue-router"
import ImageUpload from "@/secure/components/ImageUpload.vue";

export default {
  name: "ProductsCreate",
  components: {ImageUpload},
  setup() {
    const title = ref('')
    const image = ref('')
    const description = ref('')
    const price = ref(0)
    const router = useRouter()

    const submit = async() => {
      const response = await axios.post('products', {
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
