<template>
   <Layout>
      <div class="p-6">
            <!-- Back Button -->
         <div class="flex items-center mb-4">
            <button @click="back" class="inline-flex items-center text-black-600 hover:text-gray-800">
               <i class="bi bi-arrow-left text-xl mr-2"></i>
            </button>
         </div>
      
         <!-- Header Section -->
         <div class="flex justify-between items-center px-4 py-2 rounded-md mb-6">
            <p class="text-2xl font-semibold text-gray-700">
               {{ training.name }} - 
               <span class="font-medium text-gray-600">
               {{ training.coach.first_name }} {{ training.coach.middle_initial }}. {{ training.coach.last_name }}
               </span>
            </p>
            <button
               type="button"
               class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 focus:outline-none"
               data-bs-toggle="modal"
               data-bs-target="#createModal"
            >
               Add Client
            </button>
         </div>
      
         <!-- Add Client Modal -->
         <AddClientToTraining :clients="clients" :training="training" />
      
         <!-- Clients Table -->
         <div class="p-5 bg-white shadow rounded-md overflow-auto">
            <table class="w-full text-gray-600 rounded">
               <thead class="bg-gray-100 text-sm uppercase font-medium text-gray-600">
               <tr class="text-center">
                  <th class="px-4 py-3">Name</th>
                  <th class="px-4 py-3">Actions</th>
               </tr>
               </thead>
               <tbody>
               <tr
                  v-for="client in training.clients"
                  :key="client.id"
                  class="text-center border-b hover:bg-gray-50"
               >
                  <td class="px-4 py-3">
                     {{ client.first_name }} {{ client.middle_initial }} {{ client.last_name }}
                  </td>
                  <td class="px-4 py-3">
                     <button
                     class="text-red-600 hover:text-red-800"
                     type="button"
                     @click="openDeleteModal(client)"
                     data-bs-toggle="modal"
                     data-bs-target="#deleteModal"
                     >
                        <i class="bi bi-trash text-lg"></i>
                     </button>
                  </td>
               </tr>
               </tbody>
            </table>
         </div>
      
         <!-- Delete Modal -->
         <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
               <div class="modal-header bg-gray-100">
                  <h5 class="modal-title font-medium text-gray-700" id="deleteModalTitle">Delete Client</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body text-gray-600">
                  Are you sure you want to delete 
                  <strong class="text-gray-700">
                     {{ deleteForm.first_name }} {{ deleteForm.middle_initial }} {{ deleteForm.last_name }}
                  </strong>
                  from this training?
               </div>
               <div class="modal-footer bg-gray-50">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-danger" @click="deleteTraining">Delete</button>
               </div>
               </div>
            </div>
         </div>   
      </div>
     
   </Layout>
 </template>

<script setup>

import AddClientToTraining from '../../Components/UserModals/AddClientToTraining.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';

const props = defineProps({
   training: Object,
   clients: Array
})

const deleteForm = useForm({
   client_id: '',
   first_name: '',
   middle_initial: '',
   last_name: '',
   training_id: props.training.id
});

const openDeleteModal = (client) => {
   deleteForm.client_id = client.id;
   deleteForm.first_name = client.first_name;
   deleteForm.middle_initial = client.middle_initial;
   deleteForm.last_name = client.last_name;

};

const deleteTraining = () => {
   deleteForm.put(route('trainings.removeClient', deleteForm.training_id), {
      onError: (errors) => {
         console.error(errors);
      },
      onSuccess: () => {
         const modalElement = document.querySelector('#deleteModal');
         if(modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
               modal.hide();
            }
         }
      }
   });
};

const back = () =>
{
    window.history.back();
}

</script>