<template>
   <div
     class="modal fade"
     :id="`editModal-${user.id}`"
     tabindex="-1"
     aria-labelledby="editModalTitle"
     aria-hidden="true"
   >
     <div class="modal-dialog modal-dialog-centered">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title" id="editModalTitle">Edit User</h5>
           <button
             type="button"
             class="btn-close"
             data-bs-dismiss="modal"
             aria-label="Close"
           ></button>
         </div>
         <form @submit.prevent="updateUser" class="text-left">
           <div class="modal-body">
             <div class="mb-3">
               <label :for="'username-' + user.id" class="form-label">Username</label>
               <input
                 type="text"
                 class="form-control"
                 :id="'username-' + user.id"
                 v-model="user.username"
                 name="username"
               />
               <span class="text-red-500">{{ form.errors.username }}</span>
             </div>
 
             <div class="mb-3">
               <label :for="'password-' + user.id" class="form-label">Password</label>
               <input
                 type="password"
                 class="form-control"
                 :id="'password-' + user.id"
                 v-model="user.password"
                 name="password"
               />
               <span class="text-red-500">{{ form.errors.password }}</span>
             </div>
 
             <div class="mb-3">
               <label :for="'password_confirmation-' + user.id" class="form-label">Confirm Password</label>
               <input
                 type="password"
                 class="form-control"
                 :id="'password_confirmation-' + user.id"
                 v-model="user.password_confirmation"
                 name="password_confirmation"
               />
               <span class="text-red-500">{{ form.errors.password_confirmation }}</span>
             </div>
 
             <div class="mb-3">
               <label for="role" class="form-label">Roles</label>
               <div v-for="role in userRoles" :key="role.id" class="form-check">
                 <input
                   class="form-check-input"
                   type="radio"
                   :id="'role-' + role.id + '-' + user.id"
                   :value="role.name"
                   v-model="user.role"
                 />
                 <label class="form-check-label capitalize" :for="'role-' + role.id + '-' + user.id">
                   {{ role.name }}
                 </label>
               </div>
               <span v-if="form.errors.role" class="text-red-500">{{ form.errors.role }}</span>
             </div>
           </div>
 
           <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
               Close
             </button>
             <button type="submit" class="btn btn-primary">Update User</button>
           </div>
         </form>
       </div>
     </div>
   </div>
 </template>
 
 <script setup>
 import { ref, defineProps, defineEmits } from 'vue';
 import { useForm } from '@inertiajs/vue3';
 
 const props = defineProps({
   user: Object,
   userRoles: Array
 });
 
 
 const form = useForm({
   username: props.user.username || '',
   password: '',
   password_confirmation: '',
   role: props.user.roles && props.user.roles.length ? props.user.roles[0].name : ''
 });
 
 const updateUser = () => {
   form.put(route('users.update', props.user.id), {
     onError: (errors) => {
       form.errors = errors;
     },
     onSuccess: () => {
       const modalElement = document.querySelector(`#editModal-${props.user.id}`);
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
 