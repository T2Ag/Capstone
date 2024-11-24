<template>
   <TrainerLayout>
      <div class="p-6">
         <!-- Header Section -->
         <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Training List</h1>
            <button class="bg-red-700 text-white px-4 py-2 rounded-lg hover:bg-red-800 transition-colors">
               Add Training
            </button>
         </div>

         <!-- Training Cards Grid -->
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="training in trainings" :key="training.id" 
                 class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
               
               <!-- Card Header -->
               <div class="p-4 border-b border-gray-200">
                  <h2 class="text-xl font-semibold text-gray-800">{{ training.name }}</h2>
                  <p class="text-sm text-gray-600">
                     Coach: {{ training.coach.first_name }} {{ training.coach.last_name }}
                  </p>
               </div>
               
               <!-- Card Body -->
               <div class="p-4">
                  <div class="mb-4">
                     <p class="text-2xl font-bold text-red-700">₱{{ training.price }}</p>
                  </div>
                  
                  <!-- Action Buttons -->
                  <div class="flex justify-end space-x-2 mt-4">
                     <Link :href="route('trainings.view',  { training: training.id })" class="text-blue-500 text-[20px] ">
                        <i class="bi bi-eye text-xl"></i>
                     </lINK>
                     <button class="text-green-500 hover:text-green-700 transition-colors" :data-bs-toggle="'modal'" :data-bs-target="`#editModal-${training.id}`">
                        <i class="bi bi-pencil text-xl"></i>
                     </button>
                     <button class="text-red-500 hover:text-red-700 transition-colors" @click="openDeleteModal(training)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash text-xl"></i>
                     </button>
                  </div>
               </div>

               <EditTraining :training="training" :key="`editModal-${training.id}`"/>
            </div>
         </div>

         <!-- Empty State -->
         <div v-if="trainings.length === 0" 
              class="flex flex-col items-center justify-center py-12">
            <p class="text-xl text-gray-600">No trainings available</p>
            <p class="text-sm text-gray-500 mt-2">Start by adding a new training program</p>
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
   </TrainerLayout>
</template>

<script setup>
import TrainerLayout from '../../Layouts/TrainerLayout.vue';
import EditTraining from '../../Components/CoachPageModal/EditTraining.vue';
import { defineProps } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
   trainings: Array,
   coach: Object
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