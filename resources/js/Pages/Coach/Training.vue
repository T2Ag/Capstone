<template>
   <Layout>
      <div class="p-3">

         <div class="">
            <p class="text-3xl font-bold text-gray-900 ">Training List</p>
         </div>

         <div class="flex justify-end my-2">
            <button type="button" class="rounded px-4 py-2 bg-gradient-to-r from-red-500 to-red-700 text-white font-semibold shadow-md" data-bs-toggle="modal" data-bs-target="#createModal">
               Add Training
            </button>
         </div>

         <AddTraining :coaches="coaches"/>

         <div  class="px-2 py-2">
            <!-- Training Cards Grid -->
            <div class="bg-white shadow-md rounded p-3">
               <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  <div v-for="training in trainings.data" :key="training.id" class="bg-white border border-gray-400 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                     
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
                           <Link :href="route('trainings.view', { training: training.id })" class="text-blue-500 text-[20px]">
                              <i class="bi bi-plus"></i>
                           </Link>

                           <button
                              class="btn"
                              data-bs-toggle="modal"
                              :data-bs-target="'#trainingListModal' + training.id"
                           >
                              <i class="bi bi-eye"></i>
                           </button>

                           <button class="text-red-600 mx-2" type="button" @click="openDeleteModal(training)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                 <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                              </svg>  
                           </button>

                           <button type="button" class="rounded text-green-500 text-[15px]" :data-bs-toggle="'modal'" :data-bs-target="`#editModal-${training.id}`">
                              <i class="bi bi-pencil"></i>
                           </button>

                           <EditTrainingModal :training="training" :coaches="coaches" :key="`editModal-${training.id}`"/>
                        </div>
                     </div>

                     <!-- Clients Modal -->
                     <div class="modal fade" :id="'trainingListModal' + training.id" tabindex="-1" aria-labelledby="'trainingListModalLabel' + training.id" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <h5 class="modal-title" :id="'trainingListModalLabel' + training.id">
                                    Clients of {{ training.coach.first_name }} {{ training.coach.last_name }}
                                 </h5>
                                 <button
                                    type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                 ></button>
                              </div>
                              <div class="modal-body">
                                 <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-md">
                                    <ul class="list-group">
                                       <li
                                          v-for="client in training.clients"
                                          :key="client.id"
                                          class="list-group-item border-0 text-left"
                                       >
                                          {{ client.first_name }} {{ client.middle_initial }}. {{ client.last_name }}
                                       </li>
                                    </ul>
                                 </div>
                              </div>
                              <div class="modal-footer">
                                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Close
                                 </button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>   
               <Pagination class="flex mt-4 justify-end"  :links="trainings.links" />
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

   </Layout>
</template>
   
<script setup>
import Layout from '../../Layouts/Layout.vue';
import Pagination from '../../Components/Pagination.vue';
import AddTraining from '../../Components/UserModals/AddTraining.vue';
import EditTrainingModal from '../../Components/UserModals/EditTrainingModal.vue';
import {ref, computed} from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
   trainings: Array,
   coaches: Array
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