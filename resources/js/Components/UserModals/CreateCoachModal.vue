<template>
   <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">
         <div class="modal-header flex justify-between">
           <h5 class="modal-title" id="createModalTitle">Create Trainor</h5>
           <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <form @submit.prevent="submit">
           <div class="modal-body">
 
               <div class="row">
                 
                 <!-- First Name -->
                 <div class="col-6 mb-3">
                   <label for="first_name" class="form-label">First Name</label>
                   <input type="text" class="form-control" id="first_name" name="first_name" v-model="form.first_name">
                   <span class="text-red-500">{{ form.errors.first_name }}</span>
                 </div>
 
                 <!-- Last Name -->
                 <div class="col-6 mb-3">
                   <label for="last_name" class="form-label">Last Name</label>
                   <input type="text" class="form-control" id="last_name" name="last_name" v-model="form.last_name">
                   <span class="text-red-500">{{ form.errors.last_name }}</span>
                 </div>
 
                 <!-- Middle Initial -->
                 <div class="col-6 mb-3">
                   <label for="middle_initial" class="form-label">Middle Initial</label>
                   <input type="text" class="form-control" id="middle_initial" name="middle_initial" v-model="form.middle_initial">
                   <span class="text-red-500">{{ form.errors.middle_initial }}</span>
                 </div>
 
                 <!-- Gender -->
                 <div class="col-6 mb-3">
                   <label for="gender" class="form-label">Gender</label>
                   <select class="form-control" id="gender" name="gender" v-model="form.gender">
                     <option value="" disabled>Select Gender</option>
                     <option value="Male">Male</option>
                     <option value="Female">Female</option>
                   </select>
                   <span class="text-red-500">{{ form.errors.gender }}</span>
                 </div>

                 <div class="pb-2 pt-4">
                     <p class="font-bold text-[15px]">
                        Create Trainer Account
                     </p>
                 </div>

                 <div class="pl-5">
                     <div class="col-md-12 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" v-model="form.username">
                        <span class="text-red-500">{{ form.errors.username }}</span>
                     </div>
                     <div>
                        <div class="col-md-12 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" v-model="form.password">
                        <span class="text-red-500">{{ form.errors.password }}</span>
                     </div>

                     <div class="col-md-12 mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" v-model="form.password_confirmation">
                        <span class="text-red-500">{{ form.errors.password_confirmation }}</span>
                     </div>
                     </div>

                 </div>

               </div>

           </div>

           <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Close</button>
             <button type="submit" class="btn btn-primary">Create Trainor</button>
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

 });
 
 const form = useForm({
   username: '',
   password: '',
   password_confirmation: '',

   first_name: '',
   last_name: '',
   middle_initial: '',
   gender: '',
 });
 
 const submit = () => {
   form.post(route('coaches.store'), {
     onSuccess: () => {
       form.reset();
        const modalElement = document.querySelector('#createModal');
        if (modalElement) {
          const modal = bootstrap.Modal.getInstance(modalElement);
          if (modal) {
            modal.hide();
          }
        }
     },
     onError: (errors) => {
       errors.value = errors;
     },
   });
 };
 </script>
 