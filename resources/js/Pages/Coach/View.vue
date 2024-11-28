<template>

<Layout>

<div class="m-4">
   <Link :href="route('coaches.index')" class="inline-flex items-center hover:text-gray-700">
      <i class="bi bi-arrow-left text-lg mr-2"></i>
   </Link>
   <div>
      <div class="mb-4">
         <div class="flex justify-between py-8 px-4 mb-4">
            <div class="text-[30px] font-bold mr-3">
               {{ coach.first_name }} {{ coach.middle_initial }}. {{ coach.last_name }}
            </div>
            <div class="text-[30px] font-semibold mr-3">
               Total Earnings: {{ earnings }}
            </div>            
         </div>

         <div class="bg-white mx-2 p-4 rounded-lg shadow-md mb-6">
            <div class="flex items-center justify-between">
               <div class="text-xl font-medium text-gray-800">
                  <span class="text-gray-600">Username:</span> {{ coach.user.username }}
               </div>
               <Link :href="route('users.view', { user: coach.user_id })"
                  class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-md hover:bg-blue-700 transition "
               >
                  Edit User Account
               </Link>
            </div>
         </div>

      </div>

      <div>

         <div class="px-2 py-2">

            <!-- Trainings Table -->
            <div class="bg-white shadow-md rounded p-3">
               <div class="text-[25px] font-semibold mr-3 mb-10 mx-2">
                  {{ coach.first_name + "'s" }} Trainings
               </div>
               <table class="w-full text-left text-gray-500 bg-white">
                  <thead class="text-lg uppercase bg-gray-100">
                     <tr class="text-center">
                        <th scope="col" class="px-4 py-3">Training ID</th>
                        <th scope="col" class="px-4 py-3">Training Name</th>
                        <th scope="col" class="px-4 py-3">Price</th>
                        <th scope="col" class="px-4 py-3">Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="training in trainings" :key="training.id" class="text-center border-b">
                        <td class="px-4 py-3">{{ training.id }}</td>
                        <td class="px-4 py-3">{{ training.name }}</td>
                        <td class="px-4 py-3 text-red-700 font-bold">₱{{ training.price }}</td>
                        <td class="px-4 py-3">
                           <Link
                              :href="route('trainings.view', { training: training.id })"
                              class="text-blue-500 text-md hover:underline"
                           >
                              View
                           </Link>

                           <button class="text-red-600 mx-2" type="button" @click="openDeleteModal(training)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                 <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                              </svg>  
                           </button>

                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
        
         
        

         <!-- Delete Log Modal -->
         <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="deleteModalTitle">Delete Training</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                     Are you sure you want to delete Training # - {{deleteForm.id}} - {{deleteForm.name}}?
                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                     <button type="button" class="btn btn-danger" @click="deleteTraining">Delete</button>
                  </div>
               </div>
            </div>
         </div>

      </div>
   </div>
</div>

</Layout>

</template>

<script setup>

import { useForm } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';

const props = defineProps({
   coach: Object,
   earnings: Number,
   trainings: Array
 });

 const deleteForm = useForm({
   id: null,
   name: '',
});

const openDeleteModal = (training) => {
   deleteForm.id = training.id;
   deleteForm.name = training.name;
};

const deleteTraining = () => {
deleteForm.delete(route('trainings.destroy', deleteForm.id), {
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
</script>