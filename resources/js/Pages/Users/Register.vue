<template>
 <Layout>

   <!-- Back Button -->
   <div class="flex items-center p-3">
      <button @click="back" class="inline-flex items-center text-black-600 hover:text-gray-800">
         <i class="bi bi-arrow-left text-xl mr-2"></i>
      </button>
   </div>

   <div class="px-4 py-[1rem]">

      <div class="container mx-auto lg:w-[30rem] rounded shadow-md bg-white p-5 py-8">
         <div v-if="success" class="text-center bg-green-500/80 text-white p-4 rounded mb-4">
            {{ success }}
        </div>
         <h1 class="text-2xl text-center font-bold mb-4">Register </h1>
         <form @submit.prevent="submit">
            <!-- Username -->
            <div class="mb-4">
            <label for="create-username" class="block text-sm font-medium">Username</label>
            <input
               type="text"
               id="create-username"
               v-model="form.username"
               class="form-control"
            />
            <span class="text-red-500" v-if="form.errors.username">{{ form.errors.username }}</span>
            </div>

            <!-- Email -->
            <div class="mb-4">
            <label for="create-email" class="block text-sm font-medium">Email</label>
            <input
               type="email"
               id="create-email"
               v-model="form.email"
               class="form-control"
            />
            <span class="text-red-500" v-if="form.errors.email">{{ form.errors.email }}</span>
            </div>

            <!-- Password -->
            <div class="mb-4">
            <label for="create-password" class="block text-sm font-medium">Password</label>
            <input
               type="password"
               id="create-password"
               v-model="form.password"
               class="form-control"
            />
            <span class="text-red-500" v-if="form.errors.password">{{ form.errors.password }}</span>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
            <label for="create-password_confirmation" class="block text-sm font-medium">Confirm Password</label>
            <input
               type="password"
               id="create-password_confirmation"
               v-model="form.password_confirmation"
               class="form-control"
            />
            <span
               class="text-red-500"
               v-if="form.errors.password_confirmation"
            >{{ form.errors.password_confirmation }}</span>
            </div>

            <!-- Roles -->
            <div class="mb-4">
               <label for="create-role" class="form-label">Roles</label>
               <div v-for="role in roles" :key="role.id" class="form-check">
                 <input class="form-check-input" type="radio" :id="'create-role-' + role.id" :value="role.name" v-model="form.role">
                 <label class="form-check-label capitalize " :for="'create-role-' + role.id">
                   {{ role.name }}
                 </label>
               </div>
               <span v-if="form.errors.role" class="text-red-500">{{ form.errors.role }}</span>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Register</button>
         </form>
      </div>      
   </div>
  

 </Layout>
</template>

<script setup>
import Layout from '../../Layouts/Layout.vue';
import { reactive } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
   roles: Array,
   success: String
 });

const form = useForm({
  username: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: '',
});

const submit = () => {
  form.post(route('register.store'), {
    onSuccess: () => form.reset(),
  });
};

const back = () =>
{
    window.history.back();
}
</script>