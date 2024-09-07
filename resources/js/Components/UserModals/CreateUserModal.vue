<template>
   <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">
         <div class="modal-header flex justify-between">
           <h5 class="modal-title" id="createModalTitle">Create User</h5>
           <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <form @submit.prevent="submit">
           <div class="modal-body">
             <div class="mb-3">
               <label for="create-username" class="form-label">Username</label>
               <input type="text" class="form-control" id="create-username" name="username" v-model="form.username">
               <span class="text-red-500">{{ form.errors.username }}</span>
             </div>
             <div class="mb-3">
               <label for="create-password" class="form-label">Password</label>
               <input type="password" class="form-control" id="create-password" name="password" v-model="form.password">
               <span class="text-red-500">{{ form.errors.password }}</span>
             </div>
             <div class="mb-3">
               <label for="create-password_confirmation" class="form-label">Confirm Password</label>
               <input type="password" class="form-control" id="create-password_confirmation" name="password_confirmation" v-model="form.password_confirmation">
               <span class="text-red-500">{{ form.errors.password_confirmation }}</span>
             </div>
             <div class="mb-3">
               <label for="create-role" class="form-label">Roles</label>
               <div v-for="role in user_roles" :key="role.id" class="form-check">
                 <input class="form-check-input" type="radio" :id="'create-role-' + role.id" :value="role.name" v-model="form.role">
                 <label class="form-check-label capitalize" :for="'create-role-' + role.id">
                   {{ role.name }}
                 </label>
               </div>
               <span v-if="form.errors.role" class="text-red-500">{{ form.errors.role }}</span>
             </div>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
             <button type="submit" class="btn btn-primary">Create User</button>
           </div>
         </form>
       </div>
     </div>
   </div>
 </template>
 
 <script setup>
 import { ref } from 'vue';
 import { useForm } from '@inertiajs/vue3';
 
 const props = defineProps({
   user_roles: Array
 });
 
 const form = useForm({
   username: '',
   password: '',
   password_confirmation: '',
   role: ''
 });
 
 const errors = ref({});
 
 const submit = () => {
   errors.value = {};
   form.post(route('users.store'), {
     onError: (errors) => {
       errors.value = errors;
     },
     onSuccess: () => {
       form.reset();
       const modalElement = document.querySelector('#createModal');
       if (modalElement) {
         const modal = bootstrap.Modal.getInstance(modalElement);
         if (modal) {
           modal.hide();
         }
       }
     }
   });
  };
 </script>
 