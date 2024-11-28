<template>
   <div class="modal fade" id="updateMembershipModal" tabindex="-1" role="dialog" aria-labelledby="updateMembershipModalTitle" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
       <div class="modal-content">
         <div class="modal-header flex justify-between">
           <h5 class="modal-title" id="updateMembershipModalTitle">Update Membership</h5>
           <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <form @submit.prevent="submit">
           <div class="modal-body">
 
               <div class="mb-3">
                  <!-- Buttons to toggle between User ID selection and Create User form -->
                  <button
                  type="button"
                  class="btn me-2"
                  :class="showUserIdSelection ? 'btn-primary text-white' : 'btn-outline-primary'"
                  @click="showUserIdSelection = true"
                  >
                  Select User
                  </button>
                  <button
                  type="button"
                  class="btn"
                  :class="!showUserIdSelection ? 'btn-primary text-white' : 'btn-outline-primary'"
                  @click="showUserIdSelection = false"
                  >
                  Create User
                  </button>
               </div>

               <!-- User ID -->
               <div v-if="showUserIdSelection" class="mb-3">
                  <div class="mb-3">
                  <label for="user_id" class="form-label">User ID</label>
                  <select class="form-control" id="user_id" name="user_id" v-model="form.user_id">
                     <option value="" disabled>Select User ID</option>
                     <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.username }}
                     </option>
                  </select>
                  <span class="text-red-500">{{ form.errors.user_id }}</span>
                  </div>
               </div>

               <!-- Create a user -->
               <div v-if="!showUserIdSelection" class="mb-1">
                  <div class="row">
                  <div class="col-md-12 mb-3">
                     <label for="username" class="form-label">Username</label>
                     <input type="text" class="form-control" id="username" name="username" v-model="form.username">
                     <span class="text-red-500">{{ form.errors.username }}</span>
                  </div>

                  <div class="col-md-6 mb-3">
                     <label for="password" class="form-label">Password</label>
                     <input type="password" class="form-control" id="password" name="password" v-model="form.password">
                  </div>

                  <div class="col-md-6 mb-1">
                     <label for="password_confirmation" class="form-label">Confirm Password</label>
                     <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" v-model="form.password_confirmation">
                     <span class="text-red-500">{{ form.errors.password_confirmation }}</span>
                  </div>
                  <div class="col-md-12 mb-3">
                     <span class="text-red-500">{{ form.errors.password }}</span>
                  </div>


                  </div>
               </div>

               <div class="flex justify-between items-center">
                  <div class="my-auto align-middle text-base font-medium">Membership fee</div> 
                  <div class="flex items-center">
                  <div class="text-2xl font-bold mr-2 text-gray-600">₱</div>
                  <input 
                     type="number" 
                     class="form-control w-24 text-2xl text-right" 
                     v-model.number="form.total_amount"
                     min="0"
                     step="100"
                  >
                  </div>
               </div>

           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Close</button>
             <button type="submit" class="btn btn-primary">Update Membership</button>
           </div>
         </form>
       </div>
     </div>
   </div>
 </template>
 
 <script setup>
 import { ref, watch } from 'vue';
 import { useForm } from '@inertiajs/vue3';
 
 const props = defineProps({
   users: Array,
   client: Object
 });
 
 const form = useForm({
   user_id: '',
   username: '',
   password: '',
   password_confirmation: '',
 
   total_amount: 200,

   userSelected: true,
 });
 
 const showUserIdSelection = ref(true);
 
  // Watch for changes in showUserIdSelection
  watch(showUserIdSelection, (value) => {
    if (value === true) {
      // Clear fields related to user creation and set userSelected to true
      form.username = '';
      form.password = '';
      form.password_confirmation = '';
      form.userSelected = true;
    } else if (value === false) {
      // Clear user_id and set userSelected to false
      form.user_id = '';
      form.userSelected = false;
    }
  });


 const submit = () => {
   form.put(route('clients.becomeMember', props.client.id), {
     onSuccess: () => {
       form.reset();
        const modalElement = document.querySelector('#updateMembershipModal');
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
 