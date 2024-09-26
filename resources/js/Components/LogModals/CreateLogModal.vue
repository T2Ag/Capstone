
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
                
               <label for="create-client_id" class="form-label">Client</label>
               <select class="form-control" id="create-client_id" v-model="form.client_id">
                  <option value="">Select a Client</option>
                  <option v-for="client in clients" :key="client.id" :value="client.id">
                  {{ client.first_name }} {{ client.last_name }}
                  </option>
               </select>
               <span class="text-red-500">{{ form.errors.client_id }}</span>
            </div>
             
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
             <button type="submit" class="btn btn-primary">Create Log</button>
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
    clients: Array
 });
 
 const form = useForm({
   client_id: ''
 });
 
 const errors = ref({});
 
 const submit = () => {
   errors.value = {};
   form.post(route('logs.manualStore'), {
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
 