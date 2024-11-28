<template>
   <component :is="layout">
      <div class="px-2 py-2">
         <p class="text-[30px] text-gray-600">USER PROFILE</p>
      </div>
      <div class="px-10 py-3">
 
         <div class="flex bg-white p-5 rounded items-center justify-center ">
 
            <div class="flex flex-col items-center px-4">
               <!-- Only show when successMessage exists -->
               <div v-if="successMessage" class="px-10 py-2">
                  <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative">
                     {{ successMessage }}
                  </div>
               </div>
 
               <div class="mb-3 min-w-[20rem]">
                  <label for="create-username" class="form-label">Username</label>
                  <input type="text" class="form-control " id="create-username" name="username" v-model="form.username">
                  <span class="text-red-500">{{ form.errors.username }}</span>
               </div>
 
               <span class="mb-3">
                  <p class="text-[20px] text-gray-600">COACH PROFILE DETAILS</p>
               </span>
 
               <div v-if="form.coach_id" class="flex justify-between">
                  <div class="mb-3">
                     <label for="create-first_name" class="form-label">First Name</label>
                     <input type="text" class="form-control m-1 w-[10rem]" id="create-first_name" name="first_name" v-model="form.first_name">
                     <span class="text-red-500">{{ form.errors.first_name }}</span>
                  </div>
 
                  <div class="mb-3">
                     <label for="create-middle_initial" class="form-label">Middle Initial</label>
                     <input type="text" class="form-control m-1 w-[10rem]" id="create-middle_initial " name="middle_initial" v-model="form.middle_initial">
                     <span class="text-red-500">{{ form.errors.middle_initial }}</span>
                  </div>
 
                  <div class="mb-3">
                     <label for="create-last_name" class="form-label">Last Name</label>
                     <input type="text" class="form-control m-1 w-[10rem]" id="create-last_name" name="last_name" v-model="form.last_name">
                     <span class="text-red-500">{{ form.errors.last_name }}</span>
                  </div>
               </div>
               <div v-else class="mb-3">
                  <p class="text-gray-600 bg-gray-100 p-3 rounded">
                     This user doesn't have a coach account yet.
                  </p>
               </div>
 
               <div>
                  <button 
                     @click="update" 
                     class="bg-green-400 text-white font-bold border p-2 border-gray-400 rounded hover:bg-green-500 hover:transition duration-200 ease-in-out"
                  >
                     <i class="bi bi-save me-2"></i>Save Changes
                  </button>
               </div>
            </div>  
            
            <div class=" border">
               <div v-if="qrCode" v-html="qrCode" class="px-[3rem] pt-4"></div>
               <div v-else class="px-[3rem] pt-4 text-gray-500 text-center">
                  <!-- Placeholder when qrCode is null -->
                  No QR Code Available
               </div>
               <div class="text-center py-4">
                  <p>Personal QR Code</p>
               </div>
            </div>
         </div>
            
            
      </div>
   </component>
 </template>
 
 <script setup>
 import { computed, ref, watch } from 'vue'
 import { useForm, usePage } from '@inertiajs/vue3';
 import Layout from '../Layouts/Layout.vue';
 import TrainerLayout from '../Layouts/TrainerLayout.vue';
 import UserLayout from '../Layouts/UserLayout.vue';
 
 
 const props = defineProps({
   user: Object,
   qrCode: String 
 })
 
 const successMessage = ref('') // Use ref for reactive message
 
 const form = useForm({
   id: props.user.id,
   username: props.user.username,
   coach_id: props.user.coach?.id || null,
   first_name: props.user.coach?.first_name || '',
   middle_initial: props.user.coach?.middle_initial || '',
   last_name: props.user.coach?.last_name || '',
 });
 
 const update = () => {
   form.errors = {};
 
   form.put(route('editCoachProfile', form.id), {
      onError: (errors) => {
         form.errors = errors;
         successMessage.value = ''; // Clear success message on error
      },
      onSuccess: () => {
         successMessage.value = 'Profile Successfully updated.'
      }
   });
 }
 
 // Watch for changes in successMessage
 watch(successMessage, (newValue) => {
   if (newValue) {
      // Set a timer to clear the message after 5 seconds
      setTimeout(() => {
         successMessage.value = '';
      }, 2000);
   }
 });
 
 const layouts = {
  Layout,
  TrainerLayout,
  UserLayout
 }
 
 const layout = computed(() => {
  const userLayout = usePage().props.auth.user?.layout || 'Layout'
  return layouts[userLayout] || Layout
 })
 </script>