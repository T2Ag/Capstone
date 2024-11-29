<template>
   <Layout>
      <div class="p-5">
         <button @click="back" class="inline-flex items-center hover:text-gray-700">
            <i class="bi bi-arrow-left text-lg mr-2"></i>
         </button>

         <div class="mb-4">
            <p class="text-[30px] text-gray-600 font-semibold">USER PROFILE</p>
            <p class="text-gray-500 text-sm mt-1">
               Manage your account details and update your profile information below.
            </p>
         </div>
         <div class="px-15 py-3">
 
       <div class="flex flex-col lg:flex-row bg-white p-5 rounded items-center justify-center">
          <div class="flex flex-col items-center px-4 w-full lg:w-auto text-center">

             <!-- Only show when successMessage exists -->
             <div v-if="successMessage" class="px-10 py-2 ">
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative">
                   {{ successMessage }}
                </div>
             </div>

            <!-- New Password -->
            <div class="mb-3 w-[20rem] ">
              <label for="new-password" class="form-label">New Password</label>
              <input 
                type="password" 
                class="form-control " 
                id="new-password" 
                v-model="form.new_password"
              />
              <span class="text-red-500">{{ form.errors.new_password }}</span>
            </div>

            <!-- Verify Password -->
            <div class="mb-3 w-full">
              <label for="verify-password" class="form-label">Verify Password</label>
              <input 
                type="password" 
                class="form-control" 
                id="verify-password" 
                v-model="form.verify_password"
              />
              <span class="text-red-500">{{ form.errors.verify_password }}</span>
            </div>

             <div class="mb-4">
                <button 
                   @click="update" 
                   class="bg-green-400 text-white font-bold border p-2 border-gray-400 rounded hover:bg-green-500 hover:transition duration-200 ease-in-out"
                >
                   <i class="bi bi-save me-2"></i>Save Changes
                </button>
             </div>
          </div>
         
       </div>  
      </div>
   </div>
   </Layout>
 </template>
 
 <script setup>
 import { computed, ref, watch } from 'vue'
 import { useForm, usePage } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';
 
 
 const props = defineProps({
   user: Object,
 })
 
 const successMessage = ref('') // Use ref for reactive message
 
 const form = useForm({
   id: props.user.id,
   new_password: '',
   verify_password: '',
 });
 
 const update = () => {
   form.errors = {};
 
   form.put(route('users.updateForgotPassword', form.id), {
      onError: (errors) => {
         form.errors = errors;
         successMessage.value = ''; 
      },
      onSuccess: () => {
         successMessage.value = 'User Account Successfully updated.'
         form.reset()
      }
   });
 }

const back = () =>
{
    window.history.back();
}
 
 </script>