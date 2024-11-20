<template>
   <Layout>
      <div class="mb-3">
         <label for="create-username" class="form-label">Username</label>
         <input type="text" class="form-control" id="create-username" name="username" v-model="form.username">
         <span class="text-red-500">{{ form.errors.username }}</span>
      </div>

      <!-- Conditional rendering based on whether the user has a client -->
      <div v-if="form.client_id">
         <div class="mb-3">
            <label for="create-first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="create-first_name" name="first_name" v-model="form.first_name">
            <span class="text-red-500">{{ form.errors.first_name }}</span>
         </div>

         <div class="mb-3">
            <label for="create-middle_initial" class="form-label">Middle Initial</label>
            <input type="text" class="form-control" id="create-middle_initial" name="middle_initial" v-model="form.middle_initial">
            <span class="text-red-500">{{ form.errors.middle_initial }}</span>
         </div>

         <div class="mb-3">
            <label for="create-last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="create-last_name" name="last_name" v-model="form.last_name">
            <span class="text-red-500">{{ form.errors.last_name }}</span>
         </div>
      </div>
      <div v-else class="mb-3">
         <p class="text-gray-600 bg-gray-100 p-3 rounded">
            This user doesn't have a client account yet.
         </p>
      </div>

      <div class="mb-3">
         <button @click="update" class="btn bg-green-400 border-gray-400">
            <i class="bi bi-save me-2"></i>Save Changes
         </button>
      </div>
   </Layout>
</template>

   <script setup>

   import { useForm } from '@inertiajs/vue3';
   import Layout from '../Layouts/Layout.vue';

   const props = defineProps({
      user: Object
   })

   const form = useForm({
      id: props.user.id,
      username: props.user.username,
      client_id: props.user.client?.id || null, // Set to null if no client exists
      first_name: props.user.client?.first_name || '', // Handle nullable client
      middle_initial: props.user.client?.middle_initial || '', // Handle nullable client
      last_name: props.user.client?.last_name || '', // Handle nullable client
   });

   const update = () => {
      form.errors = {};

      form.put(route('editProfile', form.id), {
      onError: (errors) => {
         form.errors = errors;
      },
      onSuccess: () => {

      }
      });
   }

   </script>